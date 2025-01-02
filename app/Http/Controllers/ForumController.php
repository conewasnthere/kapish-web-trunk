<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ForumCategory;
use App\Models\ForumThread;
use App\Models\ForumPost;
use App\Models\AdminLog;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index(Request $request)
    {
        $categories = ForumCategory::orderBy('priority', 'desc')->take(50)->get();

        return view('forum.index')->with('categories', $categories);
    }

    public function getcategory(Request $request, $id)
    {
        $categories = ForumCategory::orderBy('priority', 'desc')->take(50)->get();
        $category = ForumCategory::findOrFail($id);
        $posts = ForumThread::where('category_id', $id)->orderBy('stickied', 'desc')->orderBy('updated_at', 'desc');

        return view('forum.category')->with(['posts' => $posts->paginate(15), 'category' => $category, 'categories' => $categories]);
    }

    public function createthread(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $category = ForumCategory::findOrFail($id);

        if ($category->admin_only) {
            if (!$request->user()->isAdmin()) {
                abort(404);
            }
        }

        return view('forum.newpost')->with('category', $category);
    }

    public function docreatethread(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $category = ForumCategory::findOrFail($id);

        if ($category->admin_only) {
            if (!$request->user()->isAdmin()) {
                abort(404);
            }
        }

        if (Cache::has('user-posted' . $request->user()->id)) {
            return redirect(route('forum.createthread', $category->id))->with('error', 'Posts can only be made every ' . config('app.posting_cooldown') . ' seconds.');
        }

        $max = ($request->user()->isAdmin() ? 'max:2147483647' : 'max:2000');

        $request->validate([
            'title' => ['required', 'string', 'max:100', 'not_regex:/[\xCC\xCD]/'],
            'body' => ['required', 'string', 'min:3', $max, 'not_regex:/[\xCC\xCD]/']
        ]);

        Cache::put('user-posted' . $request->user()->id, true, Carbon::now()->addSeconds(config('app.posting_cooldown')));

        $thread = ForumThread::create([
            'category_id' => $category->id,
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'body' => $request->body,
            'stickied' => false,
            'locked' => false
        ]);

        $category->touch();

        return redirect(route('forum.getthread', $thread->id))->with('message', 'Successfully posted thread.');
    }

    public function getthread(Request $request, $id)
    {
        $thread = ForumThread::findOrFail($id);
        $replies = ForumPost::where('thread_id', $thread->id)->orderBy('created_at', 'asc');
        $categories = ForumCategory::orderBy('priority', 'desc')->take(50)->get();
        $category = $thread->category;

        return view('forum.post', ['categories' => $categories, 'post' => $thread, 'replies' => $replies->paginate(14), 'category' => $category]);
    }

    public function editthread(Request $request, $id)
    {
        $thread = ForumThread::findOrFail($id);

        if($request->user()->id != $thread->user_id) {
            abort(404);
        }

        if(!$request->user()->isAdmin() && $request->user()->id != $thread->user_id) {
            abort(404);
        }

        return view('forum.editpost')->with('post', $thread);
    }

    public function doeditthread(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $thread = ForumThread::findOrFail($id);

        if($request->user()->id != $thread->user_id) {
            abort(404);
        }

        if(!$request->user()->isAdmin() && $request->user()->id != $thread->user_id) {
            abort(404);
        }

        $max = ($request->user()->isAdmin() ? 'max:2147483647' : 'max:2000');

        $request->validate([
            'title' => ['required', 'string', 'max:100', 'not_regex:/[\xCC\xCD]/'],
            'body' => ['required', 'string', 'min:3', $max, 'not_regex:/[\xCC\xCD]/']
        ]);

        $thread->title = $request->title;
        $thread->body = $request->body;
        $thread->save();

        $thread->category->touch();

        if ($request->user()->isAdmin()) {
            AdminLog::log($request->user(), sprintf('Edited thread "%s". (THREAD ID: %s)', $thread->title, $thread->id));
        }

        return redirect(route('forum.getthread', $thread->id))->with('message', 'Successfully edited thread.');
    }

    public function deletethread(Request $request, $id)
    {
        if (!$request->user()->isAdmin()) {
            abort(404);
        }

        $thread = ForumThread::findOrFail($id);
        $category = $thread->category;

        foreach ($thread->replies as $reply) {
            $reply->delete();
        }

        if ($request->user()->isAdmin()) {
            AdminLog::log($request->user(), sprintf('Deleted thread "%s". (THREAD ID: %s)', $thread->title, $thread->id));
        }

        $thread->delete();

        return redirect(route('forum.category', $category->id))->with('message', 'Successfully deleted thread.');
    }

    public function togglestickythread(Request $request, $id)
    {
        if (!$request->user()->isAdmin()) {
            abort(404);
        }

        $thread = ForumThread::findOrFail($id);

        $thread->stickied = !$thread->stickied;
        $thread->save();

        if ($request->user()->isAdmin()) {
            AdminLog::log($request->user(), sprintf('Stickied thread "%s". (THREAD ID: %s)', $thread->title, $thread->id));
        }

        return redirect(route('forum.getthread', $thread->id))->with('message', 'Successfully toggled thread sticky.');
    }

    public function togglelock(Request $request, $id)
    {
        if (!$request->user()->isAdmin()) {
            abort(404);
        }

        $thread = ForumThread::findOrFail($id);

        $thread->locked = !$thread->locked;
        $thread->save();

        if ($request->user()->isAdmin()) {
            AdminLog::log($request->user(), sprintf('Locked thread "%s". (THREAD ID: %s)', $thread->title, $thread->id));
        }

        return redirect(route('forum.getthread', $thread->id))->with('message', 'Successfully toggled thread lock.');
    }

    public function createreply(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $thread = ForumThread::findOrFail($id);

        if ($thread->locked) {
            abort(403);
        }

        return view('forum.newreply')->with('post', $thread);
    }

    public function docreatereply(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $thread = ForumThread::findOrFail($id);

        if ($thread->locked) {
            abort(403);
        }

        if (Cache::has('user-posted' . $request->user()->id)) {
            return redirect(route('forum.createreply', $thread->id))->with('error', 'Posts can only be made every ' . config('app.posting_cooldown') . ' seconds.');
        }

        $request->validate([
            'body' => ['required', 'string', 'min:3', 'max:2000', 'not_regex:/[\xCC\xCD]/']
        ]);

        Cache::put('user-posted' . $request->user()->id, true, Carbon::now()->addSeconds(config('app.posting_cooldown')));

        $reply = ForumPost::create([
            'thread_id' => $thread->id,
            'category_id' => $thread->category->id,
            'user_id' => $request->user()->id,
            'body' => $request->body,
            'stickied' => false
        ]);

        $thread->touch();
        $thread->category->touch();

        return redirect(route('forum.getthread', $thread->id))->with('message', 'Successfully posted reply.');
    }

    public function editreply(Request $request, $id)
    {
        if (!$request->user()->isAdmin()) {
            abort(404);
        }

        $reply = ForumPost::findOrFail($id);

        return view('forum.editreply')->with('reply', $reply);
    }

    public function doeditreply(Request $request, $id)
    {
        if (!$request->user()->isAdmin()) {
            abort(404);
        }

        $reply = ForumPost::findOrFail($id);

        $request->validate([
            'body' => ['required', 'string', 'min:3', 'max:2000', 'not_regex:/[\xCC\xCD]/']
        ]);

        $reply->body = $request->body;
        $reply->save();

        $reply->category->touch();

        if ($request->user()->isAdmin()) {
            AdminLog::log($request->user(), sprintf('Edited reply by %s. (USER ID: ) (REPLY ID: )', $reply->user->username, $reply->user->id, $reply->id));
        }

        return redirect(route('forum.getthread', $reply->thread->id))->with('message', 'Successfully edited reply.');
    }

    public function deletereply(Request $request, $id)
    {
        if (!$request->user()->isAdmin()) {
            abort(404);
        }

        $reply = ForumPost::findOrFail($id);
        $thread = $reply->thread;

        if ($request->user()->isAdmin()) {
            AdminLog::log($request->user(), sprintf('Deleted reply by %s. (USER ID: ) (REPLY ID: )', $reply->user->username, $reply->user->id, $reply->id));
        }

        $reply->delete();

        return redirect(route('forum.getthread', $thread->id))->with('message', 'Successfully deleted reply.');
    }
}
