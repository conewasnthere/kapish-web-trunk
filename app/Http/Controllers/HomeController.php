<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use App\Models\ForumThread;
use App\Models\ForumPost;
use Illuminate\Support\Facades\Cache;
use App\Models\Server;

class HomeController extends Controller
{
    public function landing(Request $request)
    {
        return view('landing')->with(['landing' => true]);
    }

    public function document(Request $request, string $document)
    {
        $documents = ['credits', 'privacy', 'rules', 'service'];
        if (!in_array($document, $documents)) {
            abort(404);
        }

        return view("documents.{$document}");
    }

    public function stats(Request $request)
    {
        // cached so we don't murder ourselves
        $richest = Cache::remember('richest', (60 * 10), fn() => User::where('admin', '0')->orderBy('money', 'desc')->take(15)->get());
        $poorest = Cache::remember('poorest', (60 * 10), fn() => User::where('admin', '0')->orderBy('money', 'asc')->take(15)->get());
        $userCount = Cache::remember('userCount', (60 * 10), fn() => User::count());
        $itemCount = Cache::remember('itemCount', (60 * 10), fn() => Item::count());
        $serverCount = Cache::remember('serverCount', (60 * 10), fn() => Server::count());
        $threadCount = Cache::remember('threadCount', (60 * 10), fn() => ForumThread::count());
        $postCount = Cache::remember('postCount', (60 * 10), fn() => ForumPost::count());
        $latestUser = User::orderBy('joined', 'desc')->first();
        $mostVisited = Server::orderBy('visits', 'desc')->first();

        return view('documents.stats')->with([
            'richest' => $richest,
            'poorest' => $poorest,
            'userCount' => $userCount,
            'itemCount' => $itemCount,
            'serverCount' => $serverCount,
            'threadCount' => $threadCount,
            'postCount' => $postCount,
            'latestUser' => $latestUser,
            'mostVisited' => $mostVisited
        ]);
    }
}