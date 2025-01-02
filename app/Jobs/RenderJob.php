<?php

namespace App\Jobs;

use Exception;
use Log;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use claviska\SimpleImage;
use App\Roblox\Grid\Rcc;
use App\Http\Cdn\Render;

class RenderJob implements ShouldQueue, ShouldBeUnique
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $assetType;
    protected $assetJob;
    protected $assetId;
    protected $rccIp;

    const TYPES = [
        'user',
        'xml',
        'clothing',
        'hat',
        'shirts',
        'pants',
        'gear',
        'head',
        'package',
        'mesh',
        'model',
        'place',
        'serverplace'
    ];
    
    const JOBS = [
        'user' =>        ['folder' => 'users'],
        'serverplace' => ['folder' => 'places', '3d' => false, 'x' => 768, 'y' => 432],
        'place' =>       ['folder' => 'places', '3d' => false, 'x' => 768, 'y' => 432],
        'xml' =>         ['folder' => 'items'],
        'mesh' =>        ['folder' => 'items'],
        'clothing' =>    ['folder' => 'items'],
        'head' =>        ['folder' => 'items'],

        'model' =>       ['folder' => 'items', 'script' => 'xml', '3d' => false],
        'hat' =>         ['folder' => 'items', 'script' => 'xml'],
        'gear' =>        ['folder' => 'items', 'script' => 'xml'],
        'shirts' =>      ['folder' => 'items', 'script' => 'clothing'],
        'pants' =>       ['folder' => 'items', 'script' => 'clothing'],
        'head' =>        ['folder' => 'items', 'script' => 'head']
    ];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type, $assetId, $overrideIp = null)
    {
        if (!in_array(Str::lower($type), self::TYPES))
        {
            Log::error(sprintf('Received invalid type %s when starting RenderJob', $type));
            $this->fail();

            return;
        }

        $this->assetType = Str::lower($type);
        $this->assetJob = self::JOBS[$this->assetType];
        $this->assetId = $assetId;

        if (!isset($this->assetJob['script']))
        {
            $this->assetJob['script'] = $this->assetType;
        }

        if (!isset($this->assetJob['3d']))
        {
            // turn on 3D thumbnails by default
            $this->assetJob['3d'] = true;
        }

        $this->rccIp = $overrideIp == null ? config('app.rcc_ip') : $overrideIp;
    }

    public function uniqueId()
    {
        return $this->assetId;
    }

    public function decode($base64)
    {
        return base64_decode($base64);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * Notes:
         * - The Lua script argument order from left to right is as follows: "jobId, type, format, x, y, baseUrl, assetId"
         * - You may fetch the baseUrl with url()
         * - Place scripts require the RCC Key as an additional argument
         * - Jobs assume that the script name is equal to the key name *unless otherwise specified within the array itself - it will not check if the value is different*
         * - You may set key '3d' to false if you do not want to render an OBJ/MTL
         */

        $server = new Rcc\RCCServiceSoap(
            resource_path('wsdl/RCCService.wsdl'),
            $this->rccIp,
            config('app.rcc_port')
        );

        $base_url = (string) config('app.url');
        $x = isset($this->assetJob['x']) ? $this->assetJob['x'] : 420;
        $y = isset($this->assetJob['y']) ? $this->assetJob['y'] : 420;

        $job_id;
        $arguments;
        $script;
        $result;

        // Run first PNG thumbnail job:
        $job_id = (string) Str::uuid();
        $arguments = [$job_id, $this->assetType, 'PNG', $x * 2, $y * 2, $base_url, $this->assetId];
        if ($this->assetType == 'place' || $this->assetType == 'serverplace')
        {
            // array_push($arguments, config('app.rcc_key'));
        }
        if ($this->assetType == 'user')
        {
            $job_id = (string) Str::uuid();

            try
            {
                $headshot = file_get_contents('http://arbiter/render/user/'.$this->assetId.'/headshot?key=arbiterkey');
		$bodyshot = file_get_contents('http://arbiter/render/user/'.$this->assetId.'/bodyshot?key=arbiterkey');
                $image = new SimpleImage();
                $image = $image
                    ->fromString($this->decode($headshot))
                    ->resize($x, $y)
                    ->toString();

                $bodyshot_image = new SimpleImage();
                $bodyshot_image = $bodyshot_image
                        ->fromString($this->decode($bodyshot))
                        ->resize($x, $y)
                        ->toString();
                
                Render::save(sprintf('%s/headshots/%d.png', $this->assetJob['folder'], $this->assetId), $image);
                Render::save(sprintf('%s/%d.png', $this->assetJob['folder'], $this->assetId), $bodyshot_image);
            }
            catch (Exception $e)
            {
                Log::error($e);
                $this->fail();
            }
        }

        try
        {
	    $type = "";
            if ($this->assetJob['folder'] == "users") { return; }
            $result = file_get_contents('http://arbiter/render/asset/'.$this->assetId.'?key=arbiterkey');
            $asset = $result;
            $image = new SimpleImage();
            $image = $image
                ->fromString($this->decode($asset))
                ->resize($x, $y)
                ->toString();
            Render::save(sprintf('%s/%d.png', $this->assetJob['folder'], $this->assetId), $image);
        }
        catch (Exception $e)
        {
            Log::error($e);
            $this->fail();
        }

        // NOT ALWAYS!
        // Run optional second job for user headshots:


        // NOT ALWAYS!
        // Run optional third job for 3D:
        if ($this->assetJob['3d'])
        {
            $job_id = (string) Str::uuid();
            $arguments = [$job_id, $this->assetType, 'OBJ', $x, $y, $base_url, $this->assetId];
            if ($this->assetType == 'place' || $this->assetType == 'serverplace') // this is coded in but we'll never enable it...
            {
               // array_push($arguments, config('app.rcc_key'));
            }

            try
            {
                if ($this->assetType == 'user' || $this->assetJob['folder'] == 'users') {
                    $result = file_get_contents('http://arbiter/render/user/'.$this->assetId.'/3d?key=arbiterkey');
                }
                else {
                    $result = file_get_contents('http://arbiter/render/asset/'.$this->assetId.'/3d?key=arbiterkey');
                }
                $result = json_decode($result);

                // save manifest first
                $manifest = [];
                $manifest['camera'] = $result->camera;
                $manifest['camera']->fov = 70;
                $manifest['aabb'] = $result->AABB;

                Render::save(sprintf('3d/%s/%d/manifest.json', $this->assetJob['folder'], $this->assetId), json_encode($manifest));

                // objs and mtls
                $obj = $this->decode($result->files->{'scene.obj'}->content);
                $mtl = $this->decode($result->files->{'scene.mtl'}->content);

                // we simply need to point the textures correctly
                // we'll do this by saving all the files, hashing them, and then start renaming the mtl files
                unset($result->files->{'scene.obj'});
                unset($result->files->{'scene.mtl'});

                File::ensureDirectoryExists(storage_path(sprintf('renders/3d/%s/%d', $this->assetJob['folder'], $this->assetId)));
                File::ensureDirectoryExists(storage_path(sprintf('renders/3d/%s/%d/textures', $this->assetJob['folder'], $this->assetId)));

                Render::clearThreeDeeTextures($this->assetJob['folder'], $this->assetId);
                
                foreach ($result->files as $file_name => $content)
                {
                    $content = $content->content; // lol, wtf

                    $hash = Render::save(sprintf('3d/%s/%d/textures/%s', $this->assetJob['folder'], $this->assetId, $file_name), $this->decode($content));
                    $mtl = str_replace($file_name, $hash, $mtl);
                }

                // remove diffuse maps from mtl because it makes opacity fucked up
				$mtl = preg_replace('/^map_d.+\n/im', '', $mtl);
				// make 3d renders not look like they're made of ice
				$mtl = preg_replace('/^Ns \d+/im', 'Ns 0', $mtl);
				$mtl = preg_replace('/^Ks.+/im', 'Ks 0.0627451 0.0627451 0.0627451', $mtl);

                // save
                Render::save(sprintf('3d/%s/%d/scene.obj', $this->assetJob['folder'], $this->assetId), $obj);
                Render::save(sprintf('3d/%s/%d/scene.mtl', $this->assetJob['folder'], $this->assetId), $mtl);

                $server->CloseJob($job_id);
            }
            catch (Exception $e)
            {
                Log::error($e);
                $this->fail();
            }
        }
    }
}