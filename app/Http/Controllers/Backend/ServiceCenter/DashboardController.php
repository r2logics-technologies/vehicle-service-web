<?php

namespace App\Http\Controllers\Backend\ServiceCenter;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Admin\BookingResource;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\Package;
use App\Models\ServiceCenter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
   public function getData(){
    $auth = Auth::user();

        $center = ServiceCenter::where('user_id',Auth::id())->first();

        $topCustomers = User::activeCustomer()->with(['bookings' => function ($query) {
            return $query->where('status', 'completed');
        }])->whereHas('bookings', function ($query) {
            return $query->where('status', 'completed');
        })->withCount('bookings')->orderBy('bookings_count', 'desc')->take(10)->get();
        $allBookings = Booking::where('service_center_id',$center->id)->get();
        $pendBookings = Booking::pending()->where('service_center_id', $center->id)->get();
        $todaybookingCount = booking::where('service_center_id', $center->id)->whereDate("created_at", "=", Carbon::now()->toDateString())->count();
        $prosBookings = Booking::where('service_center_id', $center->id)->proccessed()->get();
        $compBookings = Booking::where('service_center_id', $center->id)->completed()->get();
        $drivers = Driver::allowed()->get();
        $customers = User::role('service_center')->get();
        $packages = Package::allowed()->get();
        $state = [
            'all_bookings' => count($allBookings),
            'pend_bookings' => count($pendBookings),
            'pros_bookings' => count($prosBookings),
            'comp_bookings' => count($compBookings),
            'drivers' => count($drivers),
            'customers' => count($customers),
            'packages' => count($packages),
            'today_booking' => $todaybookingCount,
        ];

        return response([
            'status' => 'success',
            'state' => $state,
            'topCustomers' => $topCustomers,
            'message' => "",
        ], 200);
    }
}

