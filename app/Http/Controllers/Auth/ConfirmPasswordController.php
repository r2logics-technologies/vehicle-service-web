<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ConfirmsPasswords;

class ConfirmPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Confirm Password Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password confirmations and
    | uses a simple trait to include the behavior. You're free to explore
    | this trait and override any functions that require customization.
    |
    */

    use ConfirmsPasswords;

    /**
     * Where to redirect users when the intended url fails.
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
        $this->middleware('auth');
    }
}
