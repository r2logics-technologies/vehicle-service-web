<?php

use App\Http\Controllers\Backend\Admin\BannerController;
use App\Http\Controllers\Backend\Admin\ScannerController;
use App\Http\Controllers\Backend\Admin\BookingController;
use App\Http\Controllers\Backend\Admin\CategoryController;
use App\Http\Controllers\Backend\Admin\CenterController;
use App\Http\Controllers\Backend\Admin\CustomerController;
use App\Http\Controllers\Backend\Admin\DashboardController;
use App\Http\Controllers\Backend\Admin\DriverController;
use App\Http\Controllers\Backend\Admin\HelpDeskController;
use App\Http\Controllers\Backend\Admin\InstructionController;
use App\Http\Controllers\Backend\Admin\MainController;
use App\Http\Controllers\Backend\Admin\NotificationController;
use App\Http\Controllers\Backend\Admin\PackageController;
use App\Http\Controllers\Backend\Admin\PolicyController;
use App\Http\Controllers\Backend\Admin\ProfileController;
use App\Http\Controllers\Backend\Admin\ServiceController;
use App\Http\Controllers\Backend\Admin\SettingController;
use App\Http\Controllers\Backend\Admin\TermController;
use App\Http\Controllers\Backend\ServiceCenter\ServiceBookingController;
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

Route::get('/main', [MainController::class, 'getAuthData']);

Route::get('/dashboard', [DashboardController::class, 'getData']);


Route::prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'getData']);
    Route::post('/change_profile', [ProfileController::class, 'chanegProfile']);
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'getData']);
    Route::post('/save/update', [CategoryController::class, 'submitData']);
    Route::post('/change/status/{category}', [CategoryController::class, 'changeStatusData']);
});
Route::prefix('services')->group(function () {
    Route::get('/', [ServiceController::class, 'getData']);
    Route::get('/{category}', [ServiceController::class, 'getServices']);
    Route::post('/save/update', [ServiceController::class, 'submitData']);
    Route::get('/edit/{service}', [ServiceController::class, 'getEditData']);
    Route::post('/change/status/{category}', [ServiceController::class, 'changeStatusData']);
});

Route::prefix('service-centers')->group(function () {
    Route::get('/', [CenterController::class, 'getData']);
    Route::post('/save/update', [CenterController::class, 'submitData']);
    Route::get('/edit/{service_center}', [CenterController::class, 'getEditData']);
    Route::post('/recommend/{service_center}', [CenterController::class, 'changeStatusRecommend']);
    Route::post('/spotlight/{service_center}', [CenterController::class, 'changeStatusSpotlight']);
    Route::post('/featured/{service_center}', [CenterController::class, 'changeStatusFeatured']);
    Route::post('/popular/{service_center}', [CenterController::class, 'changeStatusPopular']);
    Route::post('/change/status/{service_center}', [CenterController::class, 'changeStatusData']);
    Route::get('/activity-status/{service_center}', [CenterController::class, 'changeActivityStatusData']);
    Route::post('/upload_document', [CenterController::class, 'uploadDocument']);
    Route::post('/delete_document/{user_document}', [CenterController::class, 'deleteDocument']);
});


Route::prefix('drivers')->group(function () {
    Route::get('/', [DriverController::class, 'getData']);
    Route::post('/save/update', [DriverController::class, 'submitData']);
    Route::get('/edit/{driver}', [DriverController::class, 'getEditData']);
    Route::post('/change/status/{driver}', [DriverController::class, 'changeStatusData']);
});


Route::prefix('packages')->group(function () {
    Route::get('/', [PackageController::class, 'getData']);
    Route::post('/save/update', [PackageController::class, 'submitData']);
    Route::get('/edit/{package}', [PackageController::class, 'getEditData']);
    Route::post('/change/status/{package}', [PackageController::class, 'changeStatusData']);
});

Route::prefix('banners')->group(function () {
    Route::get('/', [BannerController::class, 'getData']);
    Route::post('/save/update', [BannerController::class, 'submitData']);
    Route::post('/change/status/{banner}', [BannerController::class, 'changeStatusData']);
});
Route::prefix('scanner')->group(function () {
    Route::get('/', [ScannerController::class, 'getData']);
    Route::post('/save/update', [ScannerController::class, 'submitData']);
});
Route::prefix('policy')->group(function () {
    Route::get('/', [PolicyController::class, 'getData']);
    Route::post('/save/update', [PolicyController::class, 'submitData']);
});
Route::prefix('terms')->group(function () {
    Route::get('/', [TermController::class, 'getData']);
    Route::post('/save/update', [TermController::class, 'submitData']);
});

Route::prefix('/reports')->group(function () {
    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'getData']);
        Route::get('/service-details/{user}', [CustomerController::class, 'getCustomerServiceDetails']);
        Route::get('/service-invoice/{booking}', [CustomerController::class, 'getCustomerServiceInvoice']);
    });

    Route::prefix('bookings')->group(function () {
        Route::get('/', [BookingController::class, 'getData']);
        Route::get('/booking_invoice',[BookingController::class,'getBookingInvoice']);
    });

    Route::prefix('service_centers')->group(function () {
        Route::get('/', [CenterController::class, 'getData']);
        Route::get('/service_details/{service_center}', [CenterController::class, 'getServiceDetails']);
        Route::get('/services/{booking}',[CenterController::class,'getbookingServiceDetails']);
    });
});

Route::prefix('setting')->group(function () {
    Route::get('/', [SettingController::class, 'getData']);
    Route::post('/change_setting', [SettingController::class, 'changeSetting']);
});

Route::prefix('help_desk')->group(function () {
    Route::get('/', [HelpDeskController::class, 'getData']);
    Route::post('/save/update', [HelpDeskController::class, 'submitData']);
    Route::post('/change/status/{helpDesk}', [HelpDeskController::class, 'changeStatusData']);
});
Route::prefix('instruction')->group(function () {
    Route::get('/', [InstructionController::class, 'getData']);
    Route::post('/save/update', [InstructionController::class, 'submitData']);
    Route::post('/change/status/{instruction}', [InstructionController::class, 'changeStatusData']);
});

Route::get('/notifications', [NotificationController::class, 'getNotificationData']);
Route::post('/send/notification', [NotificationController::class, 'sendNotification']);
