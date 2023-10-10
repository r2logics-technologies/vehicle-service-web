<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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
}
