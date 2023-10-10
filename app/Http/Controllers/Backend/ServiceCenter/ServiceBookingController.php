<?php

namespace App\Http\Controllers\Backend\ServiceCenter;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Admin\BookingResource;
use App\Models\Booking;
use App\Models\ServiceCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceBookingController extends Controller
{
    public function getCompletedBookings()
    {
        $center = ServiceCenter::where('user_id', Auth::id())->first();
        $pending_bookings = Booking::where('service_center_id', $center->id)->completed()->orWhere('status', 'service_completed')->latest()->get();
        if ($pending_bookings && count($pending_bookings) > 0) {
            return response([
                'status' => 'success',
                'message' => '',
                'status_code' => 200,
                'completed_bookings' => BookingResource::collection($pending_bookings),
            ]);
        }
        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'No bookings found.',
            'bookings' => null,
        ]);
    }
    public function getPendingBookings()
    {
        $center = ServiceCenter::where('user_id', Auth::id())->first();
        $pending_bookings = Booking::where('service_center_id', $center->id)->where('status', 'dropped_at_vendor')->latest()->get();

        if ($pending_bookings && count($pending_bookings) > 0) {
            return response([
                'status' => 'success',
                'message' => '',
                'status_code' => 200,
                'pending_bookings' => BookingResource::collection($pending_bookings),
            ]);
        }
        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'No bookings found.',
            'bookings' => null,
        ]);
    }
    public function getInProgressBookings()
    {
        $center = ServiceCenter::where('user_id', Auth::id())->first();
        $progress_bookings = Booking::where('service_center_id', $center->id)->proccessed()->latest()->get();
        if ($progress_bookings && count($progress_bookings) > 0) {
            return response([
                'status' => 'success',
                'message' => '',
                'status_code' => 200,
                'progress_bookings' => BookingResource::collection($progress_bookings),
            ]);
        }
        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'No bookings found.',
            'bookings' => null,
        ]);
    }
    public function addServiceLink(Request $request)
    {
        $booking = Booking::find($request->booking);
        if ($booking->url == null) {
            $updated = $booking->update([
                'url' => $request->link,
            ]);
            if ($updated) return response(['status' => 'success', 'status_code' => 200, 'message' => 'Link added successfully...']);
        } else {
            return response([
                'status' => 'warning',
                'status_code' => 500,
                'message' => 'Link already added!',
            ]);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'Something went wrong...',
        ]);
    }
}
