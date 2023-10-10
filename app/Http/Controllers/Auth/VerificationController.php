<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
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
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
}
