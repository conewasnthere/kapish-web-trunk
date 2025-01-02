<?php
namespace App\Helpers;

class ScriptSigner
{
    public function sign($script, $version, $assetId = false)
    {
        if ($assetId) {
            if ($version == "new") {
                $script = "--rbxassetid%" . $assetId . "%\r\n" . $script;
            } else {
                $script = "%" . $assetId . "%\r\n" . $script;
            }
        }

        $privkey = file_get_contents(resource_path('keys/PrivateKey.pem'));
        
        if ($version == "new") {
            openssl_sign("\r\n" . $script, $signature, $privkey);
        } else {
            openssl_sign($script, $signature, $privkey);
        }
        
        $base64Signature = base64_encode($signature);

        if ($version === "new")
        {
            // preferably don't get rid of \r\n here
            return "--rbxsig%" . $base64Signature . "%\r\n" . $script;
        }
        elseif ($version === "old")
        {
            return "%" . $base64Signature . "%" . $script;
        }
        elseif ($version === false)
        {
            return $base64Signature;
        }
        else
        {
            return null;
        }
    }

    public static function instance()
    {
        return new ScriptSigner();
    }
}
