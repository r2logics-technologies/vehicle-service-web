<?php
use App\Http\Controllers\Backend\Admin\CenterController;
use App\Http\Controllers\Backend\Admin\NotificationController;
use App\Http\Controllers\Backend\ServiceCenter\CustomerController;
use App\Http\Controllers\Backend\ServiceCenter\ServiceBookingController;
use App\Http\Controllers\Backend\ServiceCenter\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('service_center_bookings')->group(function () {
    Route::get('/completed_bookings', [ServiceBookingController::class, 'getCompletedBookings']);
    Route::get('/dashboard',[DashboardController::class,'getData']);
    Route::get('/pending_bookings', [ServiceBookingController::class, 'getPendingBookings']);
    Route::get('/inprogress_bookings', [ServiceBookingController::class, 'getInProgressBookings']);
    Route::post('/save', [ServiceBookingController::class, 'addServiceLink']);
    Route::get('/profile', [CenterController::class, 'getProfileData']);
    Route::get('/notifications',[NotificationController::class,'centerNotification']);
});

Route::prefix('customers')->group(function () {
    Route::get('/',[CustomerController::class,'getData']);
    Route::get('/service-details/{user}',[CustomerController::class,'getCustomerServiceDetails']);
    Route::get('/service-invoice/{booking}',[CustomerController::class,'getCustomerServiceInvoice']);
    Route::post('/booking/status/{booking}',[CustomerController::class,'updateStatus']);
    Route::post('/booking/statuscomplete/{booking}',[CustomerController::class,'updateStatus']);
});
