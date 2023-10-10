<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    // protected $redirectTo = RouteServiceProvider::ADMIN_DASHBOARD;
    protected function redirectTo()
    {
        if (auth()->user()->user_type == 'admin' && auth()->user()->status != 'deleted' ||  auth()->user()->status != 'deactivated') {
            return '/admin/dashboard';
        } else if (auth()->user()->user_type == 'service_center' && auth()->user()->status != 'deleted' ||  auth()->user()->status != 'deactivated') {
            return '/service-center/dashboard';
        } else {
            return '/login';
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
