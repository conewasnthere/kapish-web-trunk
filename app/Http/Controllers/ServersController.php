<?php

namespace App\Http\Controllers;

use App\Jobs\RenderJob;
use App\Models\Server;
use App\Models\User;
use App\Models\AdminLog;
use Illuminate\Support\Facades\Cookie;
use App\Rules\VersionRule;
use App\Rules\PlaceValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Gzip;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;

class ServersController extends Controller
{
    public function caution(Request $request)
    {
        if (Cookie::has('server_consent'))
        {
            return abort(404);
        }

        if ($request->isMethod('post'))
        {
            $request->validate(['certify' => 'accepted']);

            return redirect()->route('servers.index')->withCookie(cookie()->forever('server_consent', 'set'));
        }

        return view('servers.caution');
    }

    public function guest_passthrough(Request $request)
    {
        if (Cookie::has('guest_passthrough'))
        {
            return abort(404);
        }

        if ($request->isMethod('post'))
        {
            return redirect()->route('servers.index')->withCookie(cookie()->forever('guest_passthrough', 'set'));
        }

        return view('servers.guestpassthrough');
    }

    public function index(Request $request)
    {
        // it's extremely late and i am tired. the method of doing this sucks but it works so
        // if anyone figures out a better method feel free to write it
        // i use this to sort servers by player count, which is cached and not in db
        // I hate this lol
        // turns out it was simpler than I thought.. fuck you laravel
        $servers = Server::orderBy('updated_at', 'DESC')
            ->where('unlisted', false)
            ->paginate(12);

        $sorted_servers = $servers->sort(function($s1, $s2){
            $player_count1 = Cache::get(sprintf('server_online%d', $s1->id));
            $player_count2 = Cache::get(sprintf('server_online%d', $s2->id));
            if($player_count1 == $player_count2)
            {
                return 0;
            };
            return ($player_count1 > $player_count2 ? -1 : 1);
        });
        $sorted_servers->values()->all();
        $sorted_servers = new LengthAwarePaginator($sorted_servers, $servers->total(), $servers->perPage(), $servers->currentPage(), ['path' => $servers->path(), 'pageName' => 'page']);

        return view('servers.index')->with('servers', $sorted_servers);
    }

    public function connect(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $request->validate(['code' => 'required']);

            if (($server = Server::where('uuid', $request->input('code'))->first()) === null)
            {
                return redirect()->back()->withErrors(['code' => 'That server does not exist.']);
                return;
            }

            if ($request->user()->added_servers->contains($server->uuid))
            {
                return redirect()->route('servers.server', $server->uuid);
            }

            $request->user()->added_servers->push($server->uuid);
            $request->user()->save();

            return redirect()->route('servers.server', $server->uuid);
        }

        return view('servers.connect');
    }

    public function server(Request $request, $uuid)
    {
        $server = Server::where('uuid', $uuid)->firstOrFail();

        if ($request->user())
        {
            if (!$request->user()->added_servers->contains($server->uuid))
            {
                $request->user()->added_servers->push($server->uuid);
                $request->user()->save();
            }
        }
        

        return view('servers.server')->with('server', $server);
    }

    public function create(Request $request)
    {
        $user = $request->user();

        if (!config('app.server_creation_enabled') && !$user->isStaff()) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:40', 'not_regex:/[\xCC\xCD]/'],
            'description' => ['nullable', 'string', 'max:250', 'not_regex:/[\xCC\xCD]/'],
            'version' => ['required', new VersionRule()],
            'maxplayers' => ['required', 'integer', 'max:4096', 'min:1'],
            'chattype' => ['nullable', 'integer', Rule::in([0, 1, 2])],
            'place' => ['required', 'max:51200', new PlaceValidator()]
        ]);

        $server = Server::create([
            'uuid' => Str::uuid(),
            'name' => $request['name'],
            'description' => $request['description'] ?? 'No description.',
            'creator' => $user->id,
            'version' => $request['version'],
            'unlisted' => $request->has('unlisted'),
            'allow_guests' => $request->has('guest'),
            'friends_only' => $request->has('friends_only'),
            'maxplayers' => $request['maxplayers'],
            'chat_type' => $request['chattype'],
            'ip' => "127.0.0.1",
	    'port' => 12345,
            'secret' => Str::random(20)
        ]);

        $request->file('place')->storeAs("public/serverplaces", $server->id);

        $renderJob = new RenderJob("serverplace", $server->id);
        $this->dispatch($renderJob);

        return redirect(route('servers.server', $server->uuid));
    }

    public function delete(Request $request, $uuid)
    {
        $user = $request->user();
        $server = Server::where('uuid', $uuid)->firstOrFail();

        if ($user->id != $server->creator && !$user->isAdmin()) {
            abort(403);
        }

        if ($user->isAdmin()) {
            AdminLog::log($user, sprintf('Deleted server %s. (SERVER ID: %s)', $server->name, $server->uuid), true);
        }

        $server->delete();

        return redirect(route('servers.index'))->with('message', 'Server successfully deleted.');
    }

    public function configure(Request $request, $uuid)
    {
        $server = Server::where('uuid', $uuid)->firstOrFail();
        $user = $request->user();

        if (!$server) {
            abort(404);
        }

        if ($user->id != $server->creator && !$user->isAdmin()) {
            abort(403);
        }
		
		$revealip = session()->get('revealIp');
		
        if ($user->isAdmin() && is_null($revealip)) {
            AdminLog::log($user, sprintf('Viewed configure page for server %s. (SERVER ID: %s)', $server->name, $server->uuid));
        }

        return view('servers.configure')->with('server', $server)->with('revealIp', ($revealip ? true : false));
    }

    public function processconfigure(Request $request, $uuid)
    {
        $server = Server::where('uuid', $uuid)->firstOrFail();
        $user = $request->user();

        if (!$server) {
            abort(404);
        }

        if ($user->id != $server->creator && !$user->isAdmin()) {
            abort(403);
        }
		
		$rules = [
            'name' => ['required', 'string', 'max:40'],
            'description' => ['nullable', 'string', 'max:250'],
            'version' => ['required', new VersionRule()],
            'maxplayers' => ['required', 'integer', 'max:4096', 'min:1'],
            'chattype' => ['nullable', 'integer', Rule::in([0, 1, 2])],
            'place' => ['max:900200', new PlaceValidator()],
			'revealip' => 'sometimes'
        ];
		
		if ($user->isAdmin()) {
			if ($request->exists('revealip')) {
				AdminLog::log($user, sprintf('Unmasked IP of server %s. (SERVER ID: %s)', $server->name, $server->uuid), true);
				return redirect(route('servers.configure', $server->uuid))->with('revealIp', true);
			}
			
			$rules = array_merge($rules,[
				'ipaddress' => ['sometimes', 'string', 'ipv4'],
				'loopbackip' => ['nullable', 'string', 'ipv4'],
			]);
		}
		else
		{
			$rules = array_merge($rules,[
				'ipaddress' => ['required', 'string', 'ipv4'],
				'loopbackip' => ['nullable', 'string', 'ipv4'],
				'port' => ['required', 'integer']
			]);
		}
		
        $request->validate($rules);
		
        $server->name = $request['name'];
        $server->description = $request['description'] ?? 'No description.';
		
		
        $server->version = $request['version'];
        $server->unlisted = $request->has('unlisted');
        $server->allow_guests = $request->has('guest');
        $server->friends_only = $request->has('friends_only');
        $server->maxplayers = $request['maxplayers'];
        $server->chat_type = $request['chattype'];

        $server->save();

        if ($user->isAdmin()) {
            AdminLog::log($user, sprintf('Edited server %s. (SERVER ID: %s)', $server->name, $server->uuid));
        }

        if ($request->hasFile('place')) {
            $request->file('place')->storeAs("public/serverplaces", $server->id);

            $renderJob = new RenderJob("serverplace", $server->id);
            $this->dispatch($renderJob);
        }

        return redirect(route('servers.server', $server->uuid))->with('success', 'Changes saved successfully.');
    }
}
