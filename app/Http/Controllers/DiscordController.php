<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Ban;
use App\Http\Cdn\Thumbnail;

class DiscordController extends Controller
{
    public function authenticated_user_account(Request $request)
    {
        $user = $request->user();
        $discord_id = $user->discord_id;

        if (!$user) {
            abort(404);
        }

        if ($discord_id == null)
        {
            return response()->api([]);
        }

        $api = Http::withHeaders(['Authorization' => sprintf('Bot: %s', env('DISCORD_BOT_TOKEN'))])
            ->get(sprintf('https://discord.com/api/v9/users/%d', $discord_id));
        
        $api = json_decode($api);
        $api->avatar_url = sprintf('https://cdn.discordapp.com/avatars/%d/%s', $discord_id, $api->avatar);

        return response()->api($api);
    }

    public function match(Request $request)
    {
        if (!$request->discordId) {
            abort(400);
        }

        $user = User::where('discord_id', $request->discordId)->first();

        if (!$user) {
            return ['success' => false];
        }

        $ban = Ban::where(['user_id' => $user->id, 'banned' => true])->first();
        if ($ban) {
            return ['success' => false];
        }

        return ['success' => true, 'username' => $user->username, 'userId' => $user->id];
    }

    public function user_info(Request $request)
    {
        if (!$request->userId) {
            abort(400);
        }

        $user = User::findOrFail($request->userId);
        $thumbnail = Thumbnail::resolve('user', $user->id);

        $thumbnailUrl = match ($thumbnail['status'])
        {
            -1, 1, 3 => Thumbnail::static_image('blank.png'),
            2 => Thumbnail::static_image('disapproved.png'),
            0 => $thumbnail['result']['body']
        };

        return ['username' => $user->username, 'userid' => $user->id, 'discordid' => $user->discord_id, 'blurb' => $user->blurb, 'thumbnail' => $thumbnailUrl];
    }
}