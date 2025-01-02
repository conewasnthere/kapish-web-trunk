<?php

namespace App\Http\Cdn;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Http\Cdn\Render;
use App\Http\Cdn\CdnManager;
use App\Models\User;
use App\Models\Item;
use App\Models\Server;

use Illuminate\Support\Facades\Storage;

class Thumbnail
{
    public static function resolve($type, $id, $threeDee = false, $returnUnapproved = false) : bool|array
    {
        /**
         * Four different states:
         * -1: Invalid
         * 0: Success
         * 1: Currently Rendering. We don't have anything to show. Only returned on items and places, as users always have a render.
         * 2: Asset has been banned or moderated.
         * 3: (nullified for admin thumbnail endpoint) Asset is currently in the moderation queue. Only returned on items and places, as user thumbnails aren't moderated.
         * 4: The 3D thumbnail is not here yet. This gets returned for all types.
         */

        // like Render::resolve except we check for queues, types, and if banned
        $types = ['place', 'user', 'item'];
        if (!in_array($type, $types))
        {
            return [ 'status' => -1 ];
        }

        $asset;

        try
        {
            $asset = match ($type)
            {
                'user' => User::findOrFail($id),
                'item' => Item::findOrFail($id),
                'place' => Server::where('uuid', $id)->firstOrFail()
            };
        }
        catch (ModelNotFoundException | \UnhandledMatchError)
        {            
            return [ 'status' => -1 ];
        }

        $response = [ 'status' => 0 ];

        // this doesn't match the spec whatsoever
        if ($type == 'user')
        {
            if ($asset->banned)
            {
                return [ 'status' => 2 ];
            }
        }
        elseif ($type == 'item')
        {
            // we need renders of unapproved assets for moderation...
            if(!$asset->approved && !$returnUnapproved)
            {
                return [ 'status' => 3 ];
            }
        }
        
        if ($threeDee)
        {
            // yes, the %ss isn't a typo
            if (!Storage::disk('local')->exists(sprintf('renders/3d/%ss/%d', $type, $id)))
            {
                return [ 'status' => 4 ];
            }
        }

        if ($type == 'place')
        {
            $id = $asset->id;
        }

        if (!Storage::disk('local')->exists(sprintf('renders/%ss/%d.png', $type, $id)) && $asset->type == 'T-Shirt')
        {
            return [ 'status' => 1 ];
        }
        
        $overrideUrl = null;
        if ($type == 'item')
        {
            if (!empty($asset->thumbnail_url))
            {
                $overrideUrl = $asset->thumbnail_url;
            }
            elseif ($asset->type == 'T-Shirt')
            {
                $overrideUrl = CdnManager::resolve(Storage::disk('local')->path(sprintf('renders/items/%d.png', $id))); // shit
            }

            if ($asset->type == 'T-Shirt' || $asset->type == 'Model')
            {
                $threeDee = false;
            }
        }
        
        if (!Storage::disk('local')->exists(sprintf('renders/%ss/%d.png', $type, $id)) && $overrideUrl !== null)
        {
            return [ 'status' => 1 ];
        }
        
        if ($type == 'place')
        {
            $threeDee = false;
        }
        
        $response['result'] = Render::resolve($type, $id, $threeDee, $overrideUrl);
        return $response;
    }

    public static function static_image($image)
    {
        return url(asset(sprintf('images/thumbnail/%s', $image)));
    }
}
