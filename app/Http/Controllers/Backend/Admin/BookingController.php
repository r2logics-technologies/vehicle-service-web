<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Admin\BookingResource;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function getData()
    {
        $allBooking = count(Booking::get());
        $pendBooking = count(Booking::pending()->get());
        $acptBooking = count(Booking::accepted()->get());
        $pickBooking = count(Booking::picked()->get());
        $progBooking = count(Booking::proccessed()->get());
        $cancBooking = count(Booking::cancelled()->get());
        $compBooking = count(Booking::completed()->get());
        $rejcBooking = count(Booking::rejected()->get());
        $cashBooking = Booking::cash()->with('package')->get();
        $onlineBooking = Booking::online()->with('package')->get(); 
        $twoWBooking = count(Booking::twowheel()->get());
        $fourWBooking = count(Booking::fourwheel()->get());
        $states = [
            'allBooking' => $allBooking,
            'pendBooking' => $pendBooking,
            'acptBooking' => $acptBooking,
            'pickBooking' => $pickBooking,
            'progBooking' => $progBooking,
            'cancBooking' => $cancBooking,
            'compBooking' => $compBooking,
            'rejcBooking' => $rejcBooking,
            'cashBooking' => $cashBooking,
            'onlineBooking' => $onlineBooking,
            'twoWBooking' => $twoWBooking,
            'fourWBooking' => $fourWBooking,
        ];

        $bookings = Booking::latest()->get();
        if ($bookings && count($bookings) > 0) {
            return response([
                'status' => 'success',
                'message' => '',
                'status_code' => 200,
                'states' => $states,
                'bookings' => BookingResource::collection($bookings),
            ]);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'No bookings found.',
            'bookings' => null,
        ]);
    }
}
