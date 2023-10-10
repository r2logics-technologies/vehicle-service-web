<?php

namespace App\Http\Controllers\Mobile\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\Api\V1\VehicleResource;
use App\Models\Vehicle;
use App\Models\VehicleRequest;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DeviceLog;
use App\Models\Driver;
use PushNotification;

class VehicleController extends Controller
{
    public function getData(Request $request)
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $vehicles = Vehicle::where('user_id', $auth->id)->where('category_id', $request->category_id)->allowed()->get();

        if ($vehicles && count($vehicles) > 0) {
            return response([
                'status' => 'success',
                'status_code' => 201,
                'message' => '',
                'vehicles' => VehicleResource::collection($vehicles)
            ]);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'no vehicle yet',
            'vehicles' => null
        ]);
    }

    public function createVehicle(Request $request)
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $created = Vehicle::create([
            'user_id' => $auth->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'ragistration_nummber' => $request->ragistration_nummber,
            'menufacturer' => $request->menufacturer,
            'model' => $request->model,
            'chasis_number' => $request->chasis_number,
            'owner_name' => $request->owner_name,
            'is_insured' => $request->is_insured,
        ]);

        if ($created) return response(['status' => 'success', 'status_code' => 201, 'message' => 'vehicle created successfully', 'vehicle' => new VehicleResource($created)]);

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'something went wrong..',
        ]);
    }

    public function updateVehicle(Request $request, Vehicle $vehicle)
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $udpated = $vehicle->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'ragistration_nummber' => $request->ragistration_nummber,
            'menufacturer' => $request->menufacturer,
            'model' => $request->model,
            'chasis_number' => $request->chasis_number,
            'owner_name' => $request->owner_name,
            'is_insured' => $request->is_insured,
        ]);

        if ($udpated) {
            $udpated = Vehicle::find($vehicle->id);
            return response(['status' => 'success', 'status_code' => 201, 'message' => 'vehicle udpated successfully', 'vehicle' => new VehicleResource($udpated)]);
        }
        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'something went wrong..',
        ]);
    }

    public function deleteVehicle(Vehicle $vehicle)
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $udpated = $vehicle->update([
            'status' => 'deleted'
        ]);

        if ($udpated) {
            $udpated = Vehicle::find($vehicle->id);
            return response(['status' => 'success', 'status_code' => 201, 'message' => 'vehicle deleted successfully']);
        }
        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'something went wrong..',
        ]);
    }
     public function requestVehicle(Booking $booking)
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $updated = $booking->update([
            'is_request_vehicle' => true,
        ]);
        if ($updated) {
            $createdRequest = VehicleRequest::create([
                'user_id' =>  $auth->id,
                'booking_id' =>  $booking->id,
            ]);
            $drivers = Driver::allowed()->get();
            foreach ($drivers as $key => $driver) {
                $deviceLogs = DeviceLog::where('user_id', $driver->user_id)->get();
                if ($deviceLogs) {
                    $registration_ids = array();
                    foreach ($deviceLogs as $deviceLog) {
                        if ($deviceLog->fcm_token != null) {
                            array_push($registration_ids, $deviceLog->fcm_token);
                        }
                    }
                    $click_actions = [
                        "click_action" => "SHOW_VEHICLE_REQUEST",
                        'booking' => $booking->id,
                        'status' => 'pending'
                    ];

                    if ($registration_ids != null) {
                        $title = "Vehicle_Request" . ":" . $booking->booking_number;
                        $message = "Vehicle request...";
                        $notify = PushNotification::notifyToToken($registration_ids, $title, $message, null, $click_actions);
                    }
                }
            }
            return response([
                'status' => 'success',
                'status' => 'vehicle request send successfully',
            ]);
        }
        return response([
            'status' => 'warning',
            'status' => 'somthing went wrong!',
        ]);
    }
}
