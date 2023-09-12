<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;

use Session;

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
    // protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'contact_no', 'password');

        if ($this->auth->attempt($credentials) || $this->auth->attempt(['contact_no' => $credentials['contact_no'], 'password' => $credentials['password']])) {
            if ($this->auth->user()->user_role_id == 1) {
                return redirect()->route('admin.dashboard');
            } else if ($this->auth->user()->user_role_id == 3) {
                return redirect()->route('health-worker.dashboard');
            } else {
                if ($this->auth->user()->is_active == 1) {
                    return redirect()->route('user.dashboard');
                } else {
                    $this->logout($request);

                    Session::flash('account-verification-error', 'Your account is not verified by the admin yet. Please come back later.');
                    return redirect()->route('login.page');
                }
            }
        } else {
            $this->logout($request);

            Session::flash('login-error', 'Invalid user credential, please try again.');
            return redirect()->route('login.page');
        }
    }
}
