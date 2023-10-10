<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::view('/','frontend.pages.index');
Route::view('/about','frontend.pages.about');
Route::view('/bike-repair','frontend.pages.bike_repair');
Route::view('/car-wash','frontend.pages.car_wash');
Route::view('/packages','frontend.pages.packages');
Route::view('/contact','frontend.pages.contact');
//Route::post('/email','frontend.pages.email');

Route::get('/migrate-refresh', function () {
    Artisan::call('migrate:fresh --seed');
});
Route::get('/generate-key', function () {
    Artisan::call('key:generate');
});

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});

Route::get('/dump-autoload', function () {
    Artisan::call('clear-compiled');
    exec('composer dump-autoload');
    Artisan::call('optimize');
    echo 'dump-autoload complete';
});
Route::get('/composer-update', function () {
    echo 'please wait!! composer updating';
    exec('composer update');
    echo 'composer updated';
});

Route::get('/config-clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    return "Cache is cleared";
});

Route::get('/config/{command}', function ($command) {
    Artisan::call("'" . $command . "'");
    return "Cache is cleared";
});
Auth::routes();

Route::get('/login', function () {
    return redirect(url('/login'));
})->name('main');


Route::get('/login', function () {
    return view('backend.index');
})->name('login');




Route::middleware(['auth'])->group(
    function () {
        Route::get('/admin', function () {
			date_default_timezone_set('Asia/Kolkata');
            if (auth()->user()->user_type == 'admin') {
                return view('backend.admin.layouts.app');
            } else if (auth()->user()->user_type == 'service_center') {
                return redirect(url('/service-center/dashboard'));
            } else {
                return redirect(url('/login'));
            }
        });
        Route::any('/admin/{page?}', function () {
          date_default_timezone_set('Asia/Kolkata');
            if (auth()->user()->user_type == 'admin') {
                return view('backend.admin.layouts.app');
            } else if (auth()->user()->user_type == 'service_center') {
                return redirect(url('/service-center/dashboard'));
            } else {
                return redirect(url('/login'));
            }
        })->where('page', '(.*)');

        Route::any('/admin/{page?}', function () {
          date_default_timezone_set('Asia/Kolkata');
            if (auth()->user()->user_type == 'service_center') {
                return redirect(url('/service-center/dashboard'));
            }
            return view('backend.admin.layouts.app');
        })->where('page', '(.*)');

        Route::any('/service-center/{page?}', function () {
          date_default_timezone_set('Asia/Kolkata');
            if (auth()->user()->user_type == 'admin') {
                return redirect(url('/service-center/dashboard'));
            }
            return view('backend.service_center.layouts.app');
        })->where('page', '(.*)');
    }
);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

