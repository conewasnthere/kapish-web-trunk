<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\BodyColors;
use App\Models\InviteKey;
use App\Models\RenderQueue;
use App\Models\OwnedItems;
use App\Rules\InviteKeyRule;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Jobs\RenderJob;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validate = [
            'username' => ['required', 'string', 'min:3', 'max:20', 'unique:users', 'alpha_num', 'not_regex:/[\xCC\xCD]/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
            'invite_key' => ['string', new InviteKeyRule()]
        ];

        if (config('app.use_captcha'))
        {
            $validate['h-captcha-response'] = ['required', 'HCaptcha'];
        }

        return Validator::make($data, $validate);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        if (!config('app.registration_enabled')) {
            abort(403);
        }

        if (config('app.invite_keys_required') && !isset($data['invite_key'])) {
            abort(403);
        }

        if (!ctype_alnum($data['username'])) {
            abort(403);
        }

        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $ip = $_SERVER['REMOTE_ADDR'];
        $associatedUsers = User::where('register_ip', $ip)
            ->orWhere('last_ip', $ip)
            ->count();

        /*if ($associatedUsers >= config('app.max_accounts_per_ip')) {
            abort(403);
        }*/

        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'register_ip' => '0.0.0.0',
            'last_ip' => '0.0.0.0',
            'added_servers' => collect([])
        ]);

        BodyColors::create([
            'user_id' => $user->id,
            'head_color' => 1,
            'torso_color' => 1,
            'left_arm_color' => 1,
            'right_arm_color' => 1,
            'left_leg_color' => 1,
            'right_leg_color' => 1
        ]);

        if (isset($data['invite_key'])) {
            $invitekey = InviteKey::where('token', $data['invite_key'])->first();
            $user->invite_key = $data['invite_key'];
            $user->save();

            $invitekey->uses = $invitekey->uses - 1;
            $invitekey->save();
        }

        OwnedItems::create([
            'user_id' => $user->id,
            'item_id' => 1294,
            'wearing' => true
        ]);

        OwnedItems::create([
            'user_id' => $user->id,
            'item_id' => 1296,
            'wearing' => true
        ]);

        OwnedItems::create([
            'user_id' => $user->id,
            'item_id' => 1566,
            'wearing' => true
        ]);

        $renderJob = new RenderJob("user", $user->id);
        $this->dispatch($renderJob);

        return $user;
    }
}
