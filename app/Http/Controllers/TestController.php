<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Helpers\ScriptSigner;
use Illuminate\Support\Str;
use App\Models\User;

class TestController extends Controller
{
    public function jointest(Request $request)
    {
        if (!$request->has('username'))
        {
            abort(404);
        }

        $user = User::where('username', $request->input('username'))->firstOrFail();

        /*if (!$user->qa && !$user->isStaff())
        {
            abort(401);
        }*/

        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }

        $ip = $_SERVER['REMOTE_ADDR'];

        if ($user->last_ip != $ip)
        {
            abort(404);        }

        $joinscript = [
            'ClientPort' => 0,
            'MachineAddress' => $request->ip ?? 'localhost',
            'ServerPort' => intval($request->port) ?? 53640,
            'PingUrl' => 'http://api.tadah.rocks/ping',
            'PingInterval' => 120,
            'UserName' => $user->username,
            'SeleniumTestMode' => false,
            'UserId' => $user->id,
            'SuperSafeChat' => false,
            'ClientTicket' => '',
            'GameId' => Str::uuid()->toString(),
            'PlaceId' => intval($request->placeId) ?? 1818,
            'BaseUrl' => 'http://www.tadah.rocks/',
            'ChatStyle' => 'ClassicAndBubble',
            'VendorId' => 0,
            'ScreenshotInfo' => '',
            'VideoInfo' => '',
            'CreatorId' => 0,
            'CreatorTypeEnum' => 'User',
            'MembershipType' => 'None',
            'AccountAge' => 0,
            'CookieStoreFirstTimePlayKey' => 'rbx_evt_ftp',
            'CookieStoreFiveMinutePlayKey' => 'rbx_evt_fmp',
            'CookieStoreEnabled' => true,
            'IsRobloxPlace' => $request->trust ?? false,
            'GenerateTeleportJoin' => false,
            'InUnknownOrUnder13' => false,
            'SessionId' => Str::uuid()->toString() . '|' . Str::uuid()->toString() . '|0|' . $request->ip ?? 'localhost' . '|0|2022-01-01T24:00:00.0000000Z|0|null|null|0|0|0',
            'DataCenterId' => 0,
            'UniverseId' => 0,
            'BrowserTrackerId' => 0,
            'UsePortraitMode' => false,
            'FollowUserId' => 0
        ];

        $joinscript['CharacterAppearance'] = 'https://tadah.rocks/Asset/CharacterFetch.ashx?userId=' . $joinscript['UserId'];
        $joinscript['characterAppearanceId'] = $joinscript['UserId'];

        $response = Response::make(ScriptSigner::instance()->sign(json_encode($joinscript, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK), 'new'));
        $response->header('Content-Type', 'text/plain');
        return $response;
    }

    public function sha256fail(Request $request)
    {
        $script = "print('hello from sha256fail!')\r\ngame:SetMessage('hello from sha256fail!')";
        $privkey = file_get_contents(resource_path('keys/PrivateKey.pem'));
        openssl_sign("\r\n" . $script, $signature, null, OPENSSL_ALGO_SHA256);

        $base64Signature = base64_encode($signature);

        return response()->make("%" . $base64Signature . "%\r\n" . $script)->header('Content-Type', 'text/plain');
    }

    public function sha256succ(Request $request)
    {
        $script = "print('hello from sha256succ!')\r\ngame:SetMessage('hello from sha256succ!')";
        $privkey = file_get_contents(resource_path('keys/PrivateKey.pem'));
        openssl_sign("\r\n" . $script, $signature, $privkey, OPENSSL_ALGO_SHA256);

        $base64Signature = base64_encode($signature);

        return response()->make("%" . $base64Signature . "%\r\n" . $script)->header('Content-Type', 'text/plain');
    }

    public function sha1fail(Request $request)
    {
        $script = "print('hello from sha1fail!')\r\ngame:SetMessage('hello from sha1fail!')";
        $privkey = file_get_contents(resource_path('keys/PrivateKey.pem'));
        openssl_sign("\r\n" . $script, $signature, null, OPENSSL_ALGO_SHA1);

        $base64Signature = base64_encode($signature);

        return response()->make("%" . $base64Signature . "%\r\n" . $script)->header('Content-Type', 'text/plain');
    }

    public function sha1succ(Request $request)
    {
        $script = "print('hello from sha1succ!')\r\ngame:SetMessage('hello from sha1succ!')";
        $privkey = file_get_contents(resource_path('keys/PrivateKey.pem'));
        openssl_sign("\r\n" . $script, $signature, $privkey, OPENSSL_ALGO_SHA1);

        $base64Signature = base64_encode($signature);

        return response()->make("%" . $base64Signature . "%\r\n" . $script)->header('Content-Type', 'text/plain');
    }

    public function sha512fail(Request $request)
    {
        $script = "print('hello from sha512fail!')\r\ngame:SetMessage('hello from sha512fail!')";
        $privkey = file_get_contents(resource_path('keys/PrivateKey.pem'));
        openssl_sign("\r\n" . $script, $signature, null, OPENSSL_ALGO_SHA512);

        $base64Signature = base64_encode($signature);

        return response()->make("%" . $base64Signature . "%\r\n" . $script)->header('Content-Type', 'text/plain');
    }

    public function sha512succ(Request $request)
    {
        $script = "print('hello from sha512succ!')\r\ngame:SetMessage('hello from sha512succ!')";
        $privkey = file_get_contents(resource_path('keys/PrivateKey.pem'));
        openssl_sign("\r\n" . $script, $signature, $privkey, OPENSSL_ALGO_SHA512);

        $base64Signature = base64_encode($signature);

        return response()->make("%" . $base64Signature . "%\r\n" . $script)->header('Content-Type', 'text/plain');
    }
}
