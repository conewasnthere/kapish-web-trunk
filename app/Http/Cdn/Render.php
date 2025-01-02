<?php

namespace App\Http\Cdn;

use App\Http\Cdn\CdnManager;
use App\Helpers\Gzip;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Log;

class Render
{
    public static function save($name, $content)
    {
        $name = str_replace('renders/', '', $name);
        $path = storage_path(sprintf('app/renders/%s', $name));

        // delete the old render
        if (Storage::disk('local')->has(sprintf('renders/%s', $name)))
        {
            $hash = CdnManager::hash($path);
            Storage::disk('cdn')->delete([$hash, $hash . '.mime']);
        }

        Storage::disk('local')->put(sprintf('renders/%s', $name), $content);
        $hash = CdnManager::place($path);
        return $hash;
    }

    public static function clearThreeDeeTextures($type, $id)
    {
        $files = Storage::disk('local')->files(sprintf('renders/3d/%s/%d/textures', $type, $id));

        // first clear their equivalents on the CDN
        foreach ($files as $file)
        {
            $hash = CdnManager::hash(storage_path(sprintf('app/%s', $file)));
            Storage::disk('cdn')->delete([$hash, $hash . '.mime']);
        }

        // then clear the directory
        Storage::disk('local')->delete($files);
    }

    public static function resolve($type, $id, $threeDee = false, $overrideUrl = null) : string|array
    {
        $type .= 's';
        if ($threeDee)
        {
            // we do an entirely different process here
            $structure = ['obj' => '', 'mtl' => '', 'textures' => [], 'position' => []];
            $folder = storage_path(sprintf('app/renders/3d/%s/%d', $type, $id));

            $structure['obj'] = CdnManager::resolve(join(DIRECTORY_SEPARATOR, [ $folder, 'scene.obj' ]));
            $structure['mtl'] = CdnManager::resolve(join(DIRECTORY_SEPARATOR, [ $folder, 'scene.mtl' ]));
            $structure['position'] = json_decode(CdnManager::get(sprintf('renders/3d/%s/%d/manifest.json', $type, $id))); // hardcoded but im lazy
            
            // populate textures
            // this is unnecessary for our use case, and we can remove it entirely, but perhaps some people want UVs?
            $textures = Storage::disk('local')->files(sprintf('renders/3d/%s/%d/textures', $type, $id));
            foreach ($textures as $texture)
            {
                $structure['textures'][basename($texture)] = CdnManager::resolve(storage_path(sprintf('app/%s', $texture)));
            }

            if ($type == 'users')
            {
                $structure = [
                    '3d' => $structure,
                    'body' => CdnManager::resolve(Storage::disk('local')->path(sprintf('renders/%s/%d.png', $type, $id))),
                    'headshot' => CdnManager::resolve(Storage::disk('local')->path(sprintf('renders/%s/headshots/%d.png', $type, $id)))
                ];
            }
            else
            {
                $structure = [
                    '3d' => $structure,
                    'url' => CdnManager::resolve(Storage::disk('local')->path(sprintf('renders/%s/%d.png', $type, $id)))
                ];
            }

            if ($overrideUrl != null)
            {
                $structure['url'] = $overrideUrl;
            }

            return $structure;
        }

        if ($type == 'users')
        {
            return [
                'body' => CdnManager::resolve(Storage::disk('local')->path(sprintf('renders/%s/%d.png', $type, $id))),
                'headshot' => CdnManager::resolve(Storage::disk('local')->path(sprintf('renders/%s/headshots/%d.png', $type, $id)))
            ];
        }
        
        if ($overrideUrl != null)
        {
            return [ 'url' => $overrideUrl ];
        }
        
        return [ 'url' => CdnManager::resolve(Storage::disk('local')->path(sprintf('renders/%s/%d.png', $type, $id))) ];
    }
}