<?php

use App\Helpers\PaginationTransformer;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

function parse_bytes($size)
{
    $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $size > 0 ? floor(log($size, 1000)) : 0;
    $size = number_format($size / pow(1000, $power), 2, '.', ',') . ' ' . $units[$power];
    return $size;
}

if (!function_exists('paginate'))
{
    /**
     * Paginates a given collection.
     *
     * @param \Illuminate\Support\Collection $collection
     * @param int $show_per_page
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    function paginate(Collection $collection, int $show_per_page): LengthAwarePaginator
    {
        return PaginationTransformer::paginate($collection, $show_per_page);
    }
}

function get_directory_size($directory)
{
    if (!ctype_alnum(str_replace('/', '', $directory)) && config('app.env') !== 'local')
    {
        // YOURE DUMB AS SHIT IF YOU END UP ON THIS
        throw new Exception('Bad exception occurred' . str_replace('/', '', $directory) . ' ' . $directory);
    }

    if (str_starts_with(strtoupper(PHP_OS), 'WIN'))
    {
        if (config('app.env') === 'local' && extension_loaded('com_dotnet'))
        {
            // we can do a COM object
            $obj = new \COM('scripting.filesystemobject');
            if (is_object($obj))
            {
                $ref = $obj->getfolder($directory);
                $size = $ref->size;
                $obj = null;

                return $size;
            }
        }

        // just be recursive like an asshole
        try
        {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory));
        }
        catch (Exception)
        {
            throw new Exception("Error occurred on getting size of '$directory'");
        }

        $size = 0;
        foreach ($files as $file)
        {
            $size += $file->getSize();
        }

        return $size;
    }
    else
    {
        // I pray that this isn't exploitable
        // CARROT: IT IS
        $shell = `du -sb {$directory} | awk '{print $1}'`;
        if (!is_null($shell))
        {
            return $shell;
        }
    }

    return 0;
}
