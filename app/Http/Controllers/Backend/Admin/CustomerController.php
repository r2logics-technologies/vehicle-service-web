<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Admin\BookingResource;
use App\Http\Resources\Backend\Admin\UserResource;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function getData()
    {
        $actCustomers = count(User::active()->customers()->get());
        $inActCustomers = count(User::Inactive()->customers()->get());
        $allCustomers = count(User::customers()->get());
        $states = [
            'allCustomers' => $allCustomers,
            'actCustomers' => $actCustomers,
            'inActCustomers' => $inActCustomers,
        ];
        $customers = User::with('bookings')->allowed()->customers()->latest()->get();
        if ($customers && count($customers) > 0) {
            return response([
                'status' => 'success',
                'message' => '',
                'status_code' => 200,
                'states' => $states,
                'customers' => UserResource::collection($customers),
            ]);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'No customers found.',
            'customers' => null,
        ]);
    }

    public function getCustomerServiceDetails(User $user)
    {
        $services = Booking::where('user_id', $user->id)->allowed()->get();
        if (count($services) > 0) {
            return response([
                'status_code' => 200,
                'status' => 'success',
                'customer' => new UserResource($user),
                'services' => BookingResource::collection($services),
            ]);
        }
        return response([
            'status_code' => 500,
            'status' => 'warning',
            'services' => null,
        ]);
    }
    public function getCustomerServiceInvoice(Booking $booking)
    {
        $service = Booking::find($booking->id);
        if ($service) {
            return response([
                'status_code' => 200,
                'status' => 'success',
                'service' => new BookingResource($service),
            ]);
        }
        return response([
            'status_code' => 300,
            'status' => 'warning',
            'services' => null,
        ]);
    }
}
