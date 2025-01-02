<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Jobs\RenderJob;
use App\Http\Cdn\Render;
use App\Http\Cdn\Thumbnail;

use Log;

class CdnController extends Controller
{
    public function render(Request $request)
    {
        if (!$request->has('type') || !$request->has('id'))
        {
            return response()->api(['success' => false]);
        }

        $threeDee = $request->has('3d');        
        $resolved;

        try
        {
            $resolved = Render::resolve($request->input('type'), $request->input('id'), $threeDee);
        }
        catch (Exception)
        {
            return response()->api(['success' => false]);
        }

        return response()->api($resolved);
    }

    public function thumbnail(Request $request)
    {        
        if (!$request->has('type') || !$request->has('id'))
        {
            return response()->api([ 'status' => 0 ]);
        }

        $threeDee = (bool) $request->has('3d');
        $admin = $request->has('admin');
        
        return response()->api(Thumbnail::resolve($request->input('type'), $request->input('id'), $threeDee, $admin));
    }

    public function file(Request $request, $file)
    {
        /**
         * Three operations:
         * - Is the file name secure? (i.e. sanitize it before passing it to actual file operatons)
         * - Does the file exist?
         * - Return the file.
         */

        if (!ctype_alnum($file))
        {
            return abort(404);
        }

        if (!Storage::disk('cdn')->has($file))
        {
            return redirect(asset('images/thumbnail/blank.png'));
        }

        // mime types
        // really disgusting.
        // type;length
        $headers = explode(';', Storage::disk('cdn')->get($file . '.mime'));

        return response(Storage::disk('cdn')->get($file))
            ->header('Content-Type', 'application/octet-stream')
            ->header('Content-Encoding', 'gzip')
            ->header('Access-Control-Allow-Origin', '*');
    }
}