<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }
public function login(Request $request)
{
    $this->validateLogin($request);
    $credentials = $request->only('email', 'password');
    // Get the user by email
    $user = User::where('email', $request->email)->first();

    // If the user has 2FA enabled, verify the code
    if ($user && $user->twofa && Auth::validate($credentials)) {
    if (!$request->twofa) { return redirect()->back()->withErrors(['twofa' => 'The two-factor authentication code is invalid.']); }
        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey($user->twofa_secret, $request->twofa);
 
        if (!$valid) {
            return redirect()->back()->withErrors(['twofa' => 'The two-factor authentication code is invalid.']);
        }
    }

    if ($this->attemptLogin($request)) {
        return $this->sendLoginResponse($request);
    }

    return $this->sendFailedLoginResponse($request);
}

}
