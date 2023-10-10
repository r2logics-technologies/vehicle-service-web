<?php

use App\Http\Controllers\Mobile\Api\V1\AddressController;
use App\Http\Controllers\Mobile\Api\V1\AuthController;
use App\Http\Controllers\Mobile\Api\V1\BookingController;
use App\Http\Controllers\Mobile\Api\V1\CustomerController;
use App\Http\Controllers\Mobile\Api\V1\DashboardController;
use App\Http\Controllers\Mobile\Api\V1\NotificationController;
use App\Http\Controllers\Mobile\Api\V1\PackageController;
use App\Http\Controllers\Mobile\Api\V1\PolicyController;
use App\Http\Controllers\Mobile\Api\V1\SettingController;
use App\Http\Controllers\Mobile\Api\V1\VehicleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login-check', [AuthController::class, 'loginCheck']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/sms', [BookingController::class, 'sendSMS']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('user-profile')->group(function () {
        Route::get('/', [CustomerController::class, 'getProfileData']);
        Route::post('update', [CustomerController::class, 'profileUpdate']);
    });

    //Customers API
    Route::prefix('customers')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'customerDashboardData']);
        Route::post('/active/packages', [CustomerController::class, 'activePackages']);
        Route::get('/transactions', [CustomerController::class, 'transactionHistory']);
        Route::get('/instructions', [CustomerController::class, 'bookingInstruction']);
      Route::get('/free_accessories', [CustomerController::class, 'bookingFreeAccessories']);
        // Customer Address
        Route::prefix('user-addresses')->group(function () {
            Route::get('/', [AddressController::class, 'getAddresses']);
            Route::post('/save', [AddressController::class, 'saveAddressData']);
            Route::post('/update/{user_address}', [AddressController::class, 'updateAddressData']);
            Route::get('/delete/{user_address}', [AddressController::class, 'deleteAddressData']);
        });
        Route::prefix('packages')->group(function () {
            Route::post('/', [PackageController::class, 'getPackages']);
          	Route::get('/', [PackageController::class, 'getAllPackages']);
        });
        Route::get('/bookings', [BookingController::class, 'customerBookings']);
        Route::post('/booking/create', [BookingController::class, 'createBooking']);

        // customer vehicle
        Route::prefix('vehicles')->group(function () {
            Route::post('/', [VehicleController::class, 'getData']);
            Route::post('/create', [VehicleController::class, 'createVehicle']);
            Route::post('/update/{vehicle}', [VehicleController::class, 'updateVehicle']);
            Route::get('/delete/{vehicle}', [VehicleController::class, 'deleteVehicle']);
            Route::get('/request/{booking}', [VehicleController::class, 'requestVehicle']);
        });
    });

    //Drivers API
    Route::prefix('drivers')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'driverDashboardData']);
        Route::get('/service-centers', [BookingController::class, 'getServiceCenters']);
        Route::prefix('bookings')->group(function () {
            Route::post('/', [BookingController::class, 'driverBookings']);
            Route::post('/assing-center/{booking}', [BookingController::class, 'assignCenter']);
            Route::get('/detail/{booking}', [BookingController::class, 'getBookingDetail']);
            Route::post('/change-status/{booking}', [BookingController::class, 'changeBookingStatus']);
            Route::post('/change-payment-status/{booking}', [BookingController::class, 'changePaymentStatus']);
        });
    });

    //Service Center
    Route::prefix('service-center')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'centerDashboardData']);
        Route::prefix('bookings')->group(function () {
            Route::post('/', [BookingController::class, 'centerBookings']);
            Route::post('/serivce-link/save/{booking}', [BookingController::class, 'addServiceLink']);
            Route::get('/detail/{booking}', [BookingController::class, 'getBookingDetail']);
            Route::post('/change-status/{booking}', [BookingController::class, 'changeBookingStatus']);
        });
    });

     //COMMON API
    Route::get('/settings', [SettingController::class, 'getData']);
    Route::get('/policy',[PolicyController::class, 'getPolicy' ]);
    Route::get('/terms',[PolicyController::class, 'getTerms' ]);
    Route::get('/notifications',[NotificationController::class,'getData']);
});
