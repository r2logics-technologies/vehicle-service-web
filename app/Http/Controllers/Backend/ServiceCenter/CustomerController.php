<?php

namespace App\Http\Controllers\Backend\ServiceCenter;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Admin\BookingResource;
use App\Http\Resources\Backend\ServiceCenter\UserResource;
use App\Models\Booking;
use App\Models\DeviceLog;
use App\Models\Driver;
use App\Models\ServiceCenter;
use App\Models\ServiceReject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PushNotification;

class CustomerController extends Controller
{
    public function getData()
    {
        $center = ServiceCenter::where('user_id', Auth::id())->first();

        $customers = User::with('bookings')->whereHas('bookings', function ($query) use ($center) {
            $query->where('service_center_id', $center->id);
        })->role('customer')->get();

        if ($customers && count($customers) > 0) {
            return response(['status' => 'success', 'customers' => UserResource::collection($customers), 'message' => "",], 200);
        }
        return response([
            'status' => 'warning',
            'status_code' => 500,
            'customers' => null,
        ]);
    }
    public function getCustomerServiceDetails(User $user)
    {
        $center = ServiceCenter::where('user_id', Auth::id())->first();
        $services = Booking::where('user_id', $user->id)->where('service_center_id', $center->id)->get();
        if ($services && count($services) > 0) {
            return response([
                'status_code' => 200,
                'status' => 'success',
                'services' => BookingResource::collection($services),
            ]);
        }

        return response([
            'status_code' => 300,
            'status' => 'warning',
            'services' => null,
        ]);
    }
    public function getCustomerServiceInvoice(Booking $booking)
    {
        return response([
            'status_code' => 200,
            'status' => 'success',
            'service' => new BookingResource($booking),
        ]);
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $nowDate = Carbon::now()->format('Y-m-d H:i:s');
        $message = "";
        if ($request->status == 'accepted') {
            $message = 'inproccessed';
            $updated = $booking->update(['status' => 'proccessed', 'proccessed_at' => $nowDate]);
        } else if ($request->status == 'rejected') {
            $message = 'rejected';
            $updated = $booking->update(['status' => 'rejected', 'rejected_at' => $nowDate]);
            $rejectBy = ServiceReject::create([
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'rejection_notes' => $request->rejection_notes,
            ]);
        } else if ($request->status == 'completed') {
            $message = 'completed';
            $updated = $booking->update(['status' => 'service_completed', 'service_completed_at' => $nowDate]);
        }

        if ($updated) {
            $deviceLogs = DeviceLog::where('user_id', $booking->user_id)->get();
            if ($deviceLogs) {
                $registration_ids = array();
                foreach ($deviceLogs as $deviceLog) {
                    if ($deviceLog->fcm_token != null) {
                        array_push($registration_ids, $deviceLog->fcm_token);
                    }
                }
                $click_actions = [
                    "click_action" => "SHOW_BOOKING",
                    'booking' => "Booking : " . $booking->booking_number . $message,
                    'status' => $message
                ];

                if ($registration_ids != null) {
                    $title = $message . ":" . $booking->booking_number;
                    $message = 'Service ' . $message;
                    $notify = PushNotification::notifyToToken($registration_ids, $title, $message, null, $click_actions);
                    Log::info("STATUS CHANGE CUSTOMER>>>>>" . json_encode($notify));
                }
            }

            if ($request->status == "service_completed") {
                $driver = Driver::find($booking->driver_id);
                $deviceLogsDrivers = DeviceLog::where('user_id', $driver->user_id)->get();
                if ($deviceLogsDrivers) {
                    $registration_ids = array();
                    foreach ($deviceLogsDrivers as $deviceLog) {
                        if ($deviceLog->fcm_token != null) {
                            array_push($registration_ids, $deviceLog->fcm_token);
                        }
                    }
                    $click_actions = [
                        "click_action" => "SHOW_BOOKING",
                        'booking' => "Booking : " . $booking->booking_number . $message,
                        'status' => $message
                    ];

                    if ($registration_ids != null) {
                        $title = ucfirst($booking->status) . ":" . $booking->booking_number;
                        $message = $message;
                        $notify = PushNotification::notifyToToken($registration_ids, $title, $message, null, $click_actions);
                        Log::info("DRIVER PICK FORM VENDOR>>>>" . json_encode($notify));
                    }
                }
            }
            $updated = Booking::find($booking->id);
            return response([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Service successfully' . $message,
                'booking' => new BookingResource($updated),
            ]);
        }

        return response([
            'status_code' => 500,
            'status' => 'error',
            'message' => 'Somthing went wrong!',
        ]);
    }
}
