<?php

namespace App\Http\Cdn;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Gzip;

class CdnManager
{
    public static function hash($file_path)
    {
        return hash_file('sha256', $file_path);
    }

    public static function resolve($file)
    {
        return route('cdn.file', self::hash($file));
    }

    public static function place($origin_path)
    {
        $headers = [filesize($origin_path), mime_content_type($origin_path)];

        Gzip::compress($origin_path);
        $hash = self::hash($origin_path);

        Storage::disk('cdn')->put($hash . '.mime', join(';', $headers));
        File::copy($origin_path, storage_path(sprintf('cdn/%s', $hash)));

        return $hash;
    }

    public static function get($file)
    {
        return Gzip::decompress(Storage::disk('local')->path($file));
    }
}
