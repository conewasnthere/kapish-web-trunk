<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MauerScribble;

class MauerController extends Controller
{
    public function index(Request $request)
    {
        return view('mauer.index')->with('scribbles', MauerScribble::query()->orderBy('created_at', 'desc')->paginate(10));
    }

    public function scribble(Request $request)
    {
        $user = $request->user();
        if (!$user->scribbler && !$user->isAdmin())
        {
            abort(403);
        }

        if ($request->isMethod('post'))
        {
            $request->validate([
                'title' => ['required', 'max:64'],
                'body' => ['required']
            ]);

            MauerScribble::create([
                'title' => $request->title,
                'body' => $request->body,
                'user_id' => $user->id,
                'anonymous' => isset($request['anonymous'])
            ]);

            return redirect(route('mauer'))->with('message', 'Successfully scribbled on the wall.');
        }

        return view('mauer.scribble');
    }

    public function edit(Request $request, int $id)
    {
        $scribble = MauerScribble::findOrFail($id);
        $user = $request->user();

        if (!$user->isAdmin() && ($user->id != $scribble->user_id || !$user->scribbler))
        {
            abort(403);
        }

        if ($request->isMethod('post'))
        {
            $request->validate([
                'title' => ['required', 'max:64'],
                'body' => ['required']
            ]);

            $scribble->title = $request['title'];
            $scribble->body = $request['body'];
            $scribble->anonymous = isset($request['anonymous']);

            $scribble->save();

            return redirect(route('mauer'))->with('message', 'Successfully updated scribble.');
        }

        return view('mauer.edit')->with('scribble', $scribble);
    }
}
