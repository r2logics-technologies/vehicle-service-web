<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\Package;
use App\Models\ServiceCenter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getData()
    {
        $topCustomers = User::activeCustomer()->with(['bookings' => function($query){
            return $query->where('status', 'completed');
        }])->whereHas('bookings', function($query){
            return $query->where('status', 'completed');
        })->withCount('bookings')->orderBy('bookings_count', 'desc')->take(10)->get();
        $allBookings = Booking::get();
        $pendBookings = Booking::pending()->get();
        $todaybookingCount = booking::whereDate("created_at", "=", Carbon::now()->toDateString())->count();
        $prosBookings = Booking::proccessed()->get();
        $compBookings = Booking::completed()->get();
        $centers = ServiceCenter::allowed()->get();
        $drivers = Driver::allowed()->get();
        $customers = User::role('customer')->get();
        $packages = Package::allowed()->get();
        $topThreeSerCen = ServiceCenter::withCount('bookings')->orderBy('bookings_count', 'desc')->take(3)->get();
        $state = [
            'all_bookings' => count($allBookings),
            'pend_bookings' => count($pendBookings),
            'pros_bookings' => count($prosBookings),
            'comp_bookings' => count($compBookings),
            'centers' => count($centers),
            'drivers' => count($drivers),
            'customers' => count($customers),
            'packages' => count($packages),
            'today_booking' => $todaybookingCount,
        ];

        return response([
            'status' => 'success',
            'state' => $state,
            'service_centers' => $topThreeSerCen,
            'topCustomers'=> $topCustomers,
            'message' => "",
        ], 200);
    }
}
