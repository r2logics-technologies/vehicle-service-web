<?php

namespace App\Http\Controllers\Mobile\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\Api\V1\ActivePackageResource;
use App\Http\Resources\Mobile\Api\V1\BookingResource;
use App\Http\Resources\Mobile\Api\V1\BannerResource;
use App\Http\Resources\Mobile\Api\V1\ScannerResource;
use App\Http\Resources\Mobile\Api\V1\CategoryResource;
use App\Http\Resources\Mobile\Api\V1\PackageResource;
use App\Http\Resources\Mobile\Api\V1\UserResource;
use App\Models\ActivePackage;
use App\Models\Banner;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Driver;
use App\Models\Package;
use App\Models\Scanner;
use App\Models\ServiceCenter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function customerDashboardData()
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $response = array();
        $response['status'] = "success";
        $response['message'] = "";
        $response['reward_points'] = $auth->reward_points;
        $response['banners'] = array();
        $response['happy_customers'] = array();
        $response['active_packages'] = array();
        $response['today_bookings'] = 0;
        $response['last_services'] = [];
        $response['total_bookings'] = 0;
        $response['active_packages'] = [];
        $response['popular_package'] = [];
        $response['service_categories'] = [];
        $response['service_categories'] = CategoryResource::collection(Category::allowed()->get());
        $banners = Banner::where('status', '!=', 'deleted')->get();
        if ($banners && count($banners) > 0) {
            $response['banners'] = BannerResource::collection($banners);
        }

        $happyCustomer = User::activeCustomer()->with(['bookings' => function ($query) {
            return $query->where('status', 'completed');
        }])->whereHas('bookings', function ($query) {
            return $query->where('status', 'completed');
        })->withCount('bookings')->orderBy('bookings_count', 'desc')->take(10)->get();
        if ($happyCustomer && count($happyCustomer) > 0) {
            $response['happy_customers'] = UserResource::collection($happyCustomer);
        }

        $todayBooking = Booking::where('user_id', Auth::id())->whereDate('created_at', Carbon::now())->get();
        if ($todayBooking && count($todayBooking) > 0) {
            $response['today_bookings'] = count($todayBooking);
        }

        $lastBookings = Booking::where('user_id', Auth::id())->orderBy('id', 'desc')->take(3)->get();

        if ($lastBookings && count($lastBookings) > 0) {
            $response['last_services'] = BookingResource::collection($lastBookings);
        }

        $totalBooking = Booking::where('user_id', Auth::id())->get();
        if ($totalBooking && count($totalBooking) > 0) {
            $response['total_bookings'] = count($totalBooking);
        }

        // get active packages
        $activePackages  = ActivePackage::where('user_id', Auth::id())->where('status', 'activated')->where('max_benifit', '!=', '0')->orderBy('vehicle_type', 'desc')->get();
        if ($activePackages && count($activePackages) > 0) {
            $response['active_packages'] = ActivePackageResource::collection($activePackages);
        }


        $popularPackage = Package::withCount('bookings')->orderBy('bookings_count', 'desc')->first();
        if ($popularPackage) {
            $response['popular_package'] = new PackageResource($popularPackage);
        }


        return response($response);
    }
    public function driverDashboardData()
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $response = [];
        $response['status'] = "success";
        $response['message'] = "";
        $response['new_services'] = [];
        $response['today_bookings'] = 0;
        $response['today_my_bookings'] = 0;
        $response['today_picked_bookings'] = 0;
        $response['today_delivered_bookings'] = 0;
        $response['total_bookings'] = 0;

        $driver = Driver::where('user_id', $auth->id)->first();
        if (!$driver) {
            return response(['status' => 'warning', 'message' => "this user type not a driver"]);
        }
        $driverId = $driver->id;
        $todayBooking = Booking::whereNull('driver_id')->whereDate('created_at', Carbon::now())->orderBy('id', 'desc')->get();
        if ($todayBooking && count($todayBooking) > 0) {
            $response['today_bookings'] = count($todayBooking);
        }

        $todayMyBooking = Booking::where('driver_id', $driver->id)->whereDate('created_at', Carbon::now())->get();
        if ($todayMyBooking && count($todayMyBooking) > 0) {
            $response['today_my_bookings'] = count($todayMyBooking);
        }

        $todayPickedBooking = Booking::where('driver_id', $driver->id)->picked()->orWhere('status', 'picked_at_vendor')->whereDate('created_at', Carbon::now())->get();
        if ($todayPickedBooking && count($todayPickedBooking) > 0) {
            $response['today_picked_bookings'] = count($todayPickedBooking);
        }

        $todayDeliveredBooking = Booking::where('driver_id', $driver->id)->completed()->whereDate('created_at', Carbon::now())->get();

        if ($todayDeliveredBooking && count($todayDeliveredBooking) > 0) {
            $response['today_delivered_bookings'] = count($todayDeliveredBooking);
        }

        $newBookings = Booking::whereNull('driver_id')->orderBy('id', 'desc')->take(10)->get();



        if ($newBookings && count($newBookings) > 0) {
            $response['new_services'] = BookingResource::collection($newBookings);
        }

        $totalBooking = Booking::where('driver_id', $driver->id)->orderBy('id', 'desc')->get();
        if ($totalBooking && count($totalBooking) > 0) {
            $response['total_bookings'] = count($totalBooking);
        }

        return response($response);
    }

    public function centerDashboardData()
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $response = [];
        $response['status'] = "success";
        $response['message'] = "";
        $response['new_services'] = 0;
        $response['total_services'] = 0;
        $response['in_proccess_services'] = 0;
        $response['completed_services'] = 0;
        $response['today_rejected_services'] = 0;
        $response['next_services'] = [];

        $center = ServiceCenter::where('user_id', $auth->id)->first();
        if (!$center) {
            return response(['status' => 'warning', 'message' => "this user type not a service-center"]);
        }


        $totalBookings = Booking::where('service_center_id', $center->id)->get();
        $newBookings = Booking::where('service_center_id', $center->id)
            ->where(function ($query) {
                $query->where('created_at', Carbon::now())
                    ->orWhereIn('status', ['pending', 'accepted', 'picked', 'dropped_at_vendor']);
            })
            ->get();
        $inProccessServices = Booking::where('service_center_id', $center->id)->whereDate('proccessed_at', Carbon::now())->proccessed()->count();
        $completed = Booking::where('service_center_id', $center->id)->whereDate('service_completed_at', Carbon::now())->where('status', 'service_completed')->count();
        $rejected = Booking::where('service_center_id', $center->id)->whereDate('rejected_at', Carbon::now())->rejected()->count();

        $response['new_services'] = count($newBookings);
        $response['total_services'] = count($totalBookings);
        $response['in_proccess_services'] = $inProccessServices;
        $response['completed_services'] = $completed;
        $response['next_services'] = BookingResource::collection($newBookings);
        $response['today_rejected_services'] = $rejected;

        return response($response);
    }

    public function scannerImage()
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);
        $scanner = Scanner::allowed()->first();
        if ($scanner) {
            return response([
                'status' => 'success',
                'status_code' => 200,
                'message' => '',
                'scanner' => new ScannerResource($scanner),
            ]);
        }

        return response([
            'status' => 'error',
            'message' => 'scanner not available',
            'scanner' => null
        ]);
    }
}
