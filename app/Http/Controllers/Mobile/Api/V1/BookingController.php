<?php

namespace App\Http\Controllers\Mobile\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\Api\V1\BookingResource;
use App\Http\Resources\Mobile\Api\V1\CenterResource;
use App\Http\Resources\Mobile\Api\V1\PackageResource;
use App\Models\ActivePackage;
use App\Models\Booking;
use App\Models\DeviceLog;
use App\Models\Driver;
use App\Models\Package;
use App\Models\ServiceCenter;
use App\Models\ServiceReject;
use App\Models\TransactionPoints;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PushNotification;

class BookingController extends Controller
{
    public function customerBookings()
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $bookings = Booking::where('user_id', Auth::id())->orderBy('id', 'desc')->where('status', '!=', 'pending')->where('status', '!=', 'cancelled')->get();
        $cancelBookings = Booking::where('user_id', Auth::id())->orderBy('id', 'desc')->where('status', 'cancelled')->get();
        $inProccessBooking = Booking::where('user_id', Auth::id())->orderBy('id', 'desc')->where('status', 'pending')->get();
        return response([
            'status' => 'success',
            'status_code' => 200,
            'message' => '',
            'bookings' => BookingResource::collection($bookings),
            'cancelled_bookings' => BookingResource::collection($cancelBookings),
            'on_going_bookings' => BookingResource::collection($inProccessBooking),
        ]);
    }
    public function driverBookings(Request $request)
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $driver = Driver::where('user_id', $auth->id)->first();

        if ($request->bookings == 'today_new_bookings') {
            $bookings = Booking::whereNull('driver_id')->whereDate('created_at', Carbon::now())->orderBy('id', 'desc')->get();
        } else if ($request->bookings == 'today_my_bookings') {
            $bookings = Booking::where('driver_id', $driver->id)->whereDate('created_at', Carbon::now())->orderBy('id', 'desc')->get();
        } else if ($request->bookings == 'total_bookings') {
            $bookings = Booking::where('driver_id', $driver->id)->orderBy('id', 'desc')->get();
        } else if ($request->bookings == 'accepted') {
            $bookings = Booking::where('driver_id', $driver->id)->accepted()->orderBy('id', 'desc')->get();
        } else if ($request->bookings == 'picked') {
            $bookings = Booking::where('driver_id', $driver->id)->picked()->orderBy('id', 'desc')->get();
        } else if ($request->bookings == 'dropped_at_vendor') {
            $bookings = Booking::where('driver_id', $driver->id)->where('status', 'dropped_at_vendor')->whereDate('created_at', Carbon::now())->orderBy('id', 'desc')->get();
        } else if ($request->bookings == 'picked_at_vendor') {
            $bookings = Booking::where('driver_id', $driver->id)->where('status', 'picked_at_vendor')->whereDate('created_at', Carbon::now())->orderBy('id', 'desc')->get();
        } else if ($request->bookings == 'drop_to_customer') {
            $bookings = Booking::where('driver_id', $driver->id)->where('status', 'completed')->whereDate('created_at', Carbon::now())->orderBy('id', 'desc')->get();
        }


        if ($bookings != null && count($bookings) > 0)  return response(['status' => 'success', 'status_code' => 200, 'message' => '', 'bookings' => BookingResource::collection($bookings),]);

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'No Bookings Found',
            'bookings' => null
        ]);
    }
    public function centerBookings(Request $request)
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $center = ServiceCenter::where('user_id', $auth->id)->first();

        if ($request->bookings == 'new_bookings') {
            $bookings = Booking::where('service_center_id', $center->id)
                ->where(function ($query) {
                    $query->where('created_at', Carbon::now())
                        ->orWhereIn('status', ['pending', 'accepted', 'picked', 'dropped_at_vendor']);
                })
                ->get();
        } elseif ($request->bookings == 'today_inprocess') {
            $bookings = Booking::where('service_center_id', $center->id)->where('status', 'proccessed')->whereDate('proccessed_at', Carbon::now())->orderBy('id', 'desc')->get();
        } elseif ($request->bookings == 'today_completed') {
            $bookings = Booking::where('service_center_id', $center->id)->where('status',  'service_completed')->whereDate('service_completed_at', Carbon::now())->orderBy('id', 'desc')->get();
        } elseif ($request->bookings == 'today_cancelled') {
            $bookings = Booking::where('service_center_id', $center->id)->where('status',  'cancelled')->whereDate('cancelled_at', Carbon::now())->orderBy('id', 'desc')->get();
        } elseif ($request->bookings == 'total_bookings') {
            $bookings = Booking::where('service_center_id', $center->id)->orderBy('id', 'desc')->get();
        } elseif ($request->bookings == 'total_inprocess') {
            $bookings = Booking::where('service_center_id', $center->id)->where('status',  'proccessed')->orderBy('id', 'desc')->get();
        } elseif ($request->bookings == 'total_completed') {
            $bookings = Booking::where('service_center_id', $center->id)->where('status',  'service_completed')->orderBy('id', 'desc')->get();
        }

        if ($bookings != null && count($bookings) > 0)  return response(['status' => 'success', 'status_code' => 200, 'message' => '', 'bookings' => BookingResource::collection($bookings),]);

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'No Bookings Found',
            'bookings' => null
        ]);
    }
    public function createBooking(Request $request)
    {
        Log::info("BOOKING :::" . json_encode($request->all()));
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);


        $orderLast = Booking::orderBy('id', 'desc')->first();
        $orderNum = 1;
        if ($orderLast) $orderNum = (int) $orderLast->id + 1;
        $orderNumber = strlen($orderNum);
        if ($orderNumber == 1) {
            $newNum = '0000' . $orderNum;
        } elseif ($orderNumber == 2) {
            $newNum = '000' . $orderNum;
        } elseif ($orderNumber == 3) {
            $newNum = '00' . $orderNum;
        } elseif ($orderNumber == 4) {
            $newNum = '0' . $orderNum;
        } else {
            $newNum = $orderNum;
        }

        $resIdlen = strlen($request->store_id);
        if ($resIdlen == 1) {
            $newResNum = '000' . $request->store_id;
        } elseif ($resIdlen == 2) {
            $newResNum = '00' . $request->store_id;
        } elseif ($resIdlen == 3) {
            $newResNum = '0' . $request->store_id;
        } else {
            $newResNum = $request->store_id;
        }

        $currentDate =  explode(" ", Carbon::now()->format("dmy"));

        $booking_number = $currentDate[0] . $newResNum . $newNum;

        //Rewards
        $isFirstBooking = Booking::where('user_id', $auth->id)->first();
        if (!$isFirstBooking) {
            $user = User::find($auth->id);
            $refUser = User::where('referral_id', $user->reference_id)->first();
            $rewardPointUser = 50;
            $rewardPointRefPerson = 25;
            $userRewardPoints = $user->reward_points + $rewardPointUser;
            $refPersonRewardPoints = $refUser->reward_points + $rewardPointRefPerson;

            $userUpdate = $user->update([
                'reward_points' => $userRewardPoints,
            ]);
            if ($userUpdate) {
                $refTransCredit = TransactionPoints::create([
                    'user_id' => $auth->id,
                    'reward_points' => $rewardPointUser,
                    'transaction_type' => 'cr'
                ]);
            }
            if ($refUser) {
                $refUserUpdate = $refUser->update([
                    'reward_points' => $refPersonRewardPoints,
                ]);

                if ($refUserUpdate) {
                    $refTransCredit = TransactionPoints::create([
                        'user_id' => $refUser->id,
                        'reward_points' => $rewardPointRefPerson,
                        'transaction_type' => 'cr'
                    ]);
                }
            }
        }

        $packActive = false;
        $packActiveUpdate = false;
        $isActivePackage = ActivePackage::where('user_id', $auth->id)->where('vehicle_type', $request->vehicle_type)->where('status', 'activated')->first();
        if (!$isActivePackage) {
            $package = Package::find($request->package_id);
            $packageBenefits = json_decode($package->benefits);
            $maxPackageBenefit = collect($packageBenefits)->max('time');
            $maxBenefit = (int) $maxPackageBenefit - 1;

            $benifitArr = array();
            foreach ($packageBenefits as $key => $value) {
                $obj = [
                    'name' => $value->name,
                    'time' => (int) $value->time - 1
                ];
                array_push($benifitArr, json_encode($obj));
            }
            $packActive = ActivePackage::create([
                'user_id' => $auth->id,
                'package_id' => $request->package_id,
                'available_benefits' => json_encode($benifitArr),
                'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'end_date' => Carbon::now()->addMonths($package->duration)->format('Y-m-d H:i:s'),
                'vehicle_type' => $request->vehicle_type,
                'max_benifit' => (string) $maxBenefit
            ]);
            $packActive = ActivePackage::find($packActive->id);

            if ($packActive && $maxBenefit == 0) {
                $packageCompleted = $packActive->update([
                    'status' => 'completed'
                ]);
            }
            $user_points = $auth->reward_points;
            $package_price = Package::find($request->package_id)->price;
          	$booking_price = (float)$package_price;
            if ($user_points > 50) {
                $points_usable_pers = 50;
                $points_usable = ($user_points * $points_usable_pers) / 100;
                $booking_price = (float) $package_price - $points_usable;
              	$userPoint =(float) $user_points -(float) $points_usable;
				$user = User::find($auth->id);
              	$user->update(['reward_points'=> $userPoint]);
                $rewardTransDr = TransactionPoints::create([
                    'user_id' => $auth->id,
                    'reward_points' => $points_usable,
                    'transaction_type' => 'dr'
                ]);
            }
        } else {
            $booking_price = 0;
            $packagePrice = Package::find($request->package_id);
            $package_price = (float) $packagePrice->price;
            $isActivePackageValid = (int) $isActivePackage->max_benifit != 0 ? true : false;
            if (!$isActivePackageValid) return response(['status' => 'warning', 'message' => 'This package is completed']);

            $actviePackageBenifits = json_decode($isActivePackage->available_benefits);
            $benifitArr = array();
            foreach ($actviePackageBenifits as $key => $value) {
                $value = json_decode($value);
                $obj = [
                    'name' => $value->name,
                    'time' => (int) $value->time == 0 ? 0 : (int) $value->time  - 1
                ];
                array_push($benifitArr, json_encode($obj));
            }
            $maxActviePackageBenifit = (int) $isActivePackage->max_benifit == 0 ? 0 : (int) $isActivePackage->max_benifit - 1;

            $packActiveUpdate = $isActivePackage->update([
                'available_benefits' => json_encode($benifitArr),
                'max_benifit' => (string) $maxActviePackageBenifit
            ]);

            if ($maxActviePackageBenifit == 0) {
                $packageCompleted = $isActivePackage->update([
                    'status' => 'completed'
                ]);
            }
        }

        if ($packActive || $packActiveUpdate) {
            $details = [
                'user_address' => $request->address,
            ];

            $created = Booking::create([
                'user_id' => $auth->id,
                'user_address_id' => $request->has("user_address_id") ? $request->user_address_id : null,
              	'active_package_id' => $isActivePackage ? $isActivePackage->id : $packActive->id,
                'package_id' => $request->package_id,
                'booking_number' => $booking_number,
                'booking_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'booked_date' => $request->booked_date,
                'booked_time' => $request->booked_time,
                'vehicle_name' => $request->vehicle_name,
                'vehicle_number' => $request->vehicle_number,
                'instructions' => $request->instructions,
                'vehicle_type' => $request->vehicle_type,
                'payable_amount' => $booking_price ? $booking_price : $package_price,
                'reward_amount' =>  0,
                'payment_method' => $request->has('payment_method') ? $request->payment_method : 'cash',
                'payment_status' => $request->has('payment_status') ? $request->payment_status : "due",
                // 'detail' => json_encode($details),
            ]);
        }



        if ($created) {

            $drivers = Driver::allowed()->get();
            foreach ($drivers as $key => $driver) {
                $deviceLogs = DeviceLog::where('user_id', $driver->user_id)->get();
                if ($deviceLogs) {
                    Log::info("CRAETE ORDER11");
                    $registration_ids = array();
                    foreach ($deviceLogs as $deviceLog) {
                        if ($deviceLog->fcm_token != null) {
                            array_push($registration_ids, $deviceLog->fcm_token);
                        }
                    }
                    $click_actions = [
                        "click_action" => "SHOW_NEW_BOOKING",
                        'booking' => $created->id,
                        'status' => 'pending'
                    ];

                    if ($registration_ids != null) {
                        $title = "New_Booking" . ":" . $created->booking_number;
                        $message = "new Booking..";
                        $notify = PushNotification::notifyToToken($registration_ids, $title, $message, null, $click_actions);
                        Log::info("CRAETE ORDER" . json_encode($notify));
                    }
                }
            }
            return response([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Your service booked successfully...',
                'booking' => new BookingResource($created),
            ]);
        }

        return response([
            'status' => 'error',
            'status_code' => 404,
            'message' => 'Something went wrong...',
        ]);
    }
    public function getServiceCenters()
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $serviceCenters = ServiceCenter::isActive()->get();
        if ($serviceCenters) {
            return response([
                'status' => 'success',
                'status_code' => 200,
                'message' => '',
                'service_centers' => CenterResource::collection($serviceCenters),
            ]);
        }

        return response([
            'status' => 'error',
            'status_code' => 404,
            'message' => 'Something went wrong...',
        ]);
    }
    public function changeBookingStatus(Request $request, Booking $booking)
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);
        $nowDate = Carbon::now()->format('Y-m-d H:i:s');
        $isTodayBooking = Booking::where('id', $booking->id)->whereDate('booked_date', '<=', $nowDate)->first();
        if (!$isTodayBooking) return response(['status' => 'error', 'message' => 'this booking not for today']);
        $message = "";
        if ($request->status == 'accepted') {
            $driver = Driver::where('user_id', $auth->id)->first();
            if ($booking->driver_id == null) {
                $message = 'booking accepted';
                $updated = $booking->update(['status' => 'accepted', 'accepted_at' => $nowDate, 'driver_id' => $driver->id]);
            } else {
                return response([
                    'status' => 'warning',
                    'status_code' => 500,
                    'message' => 'this booking already accepted by other driver',
                ]);
            }
        } elseif ($request->status == 'driver_rejected') {
            $rejectBy = ServiceReject::create([
                'booking_id' => $booking->id,
                'user_id' => $auth->id,
                'driver_id' => $auth->id,
                'rejection_notes' => $request->rejection_notes,
            ]);
            if ($rejectBy) return response(['status' => 'success', 'status_code' => 200, 'message' => 'booking is rejected',]);
        } elseif ($request->status == 'rejected') {
            $message = 'booking rejected';
            $updated = $booking->update(['status' => 'rejected', 'rejected_at' => $nowDate]);
        } elseif ($request->status == 'cancelled') {
            $message = 'booking cancelled';
            $updated = $booking->update(['status' => 'cancelled', 'cancelled_at' => $nowDate]);
        } elseif ($request->status == 'picked') {
            $message = 'vehicle picked successfully ';
            $updated = $booking->update(['status' => 'picked', 'picked_at' => $nowDate]);
        } elseif ($request->status == 'proccessed') {
            $message = 'service in proccessed';
            $updated = $booking->update(['status' => 'proccessed', 'proccessed_at' => $nowDate, 'url' => $request->video]);
        } elseif ($request->status == 'dropped_at_vendor') {
            $message = 'vehicle drop to vendor';
            $updated = $booking->update(['status' => 'dropped_at_vendor', 'dropped_at_vendor_at' => $nowDate]);
        } elseif ($request->status == 'service_completed') {
            $message = ' service completed';
            $updated = $booking->update(['status' => 'service_completed', 'service_completed_at' => $nowDate]);
        } elseif ($request->status == 'picked_at_vendor') {
            $message = 'vehicle pick From vendor';
            $updated = $booking->update(['status' => 'picked_at_vendor', 'picked_at_vendor_at' => $nowDate]);
        } elseif ($request->status == 'completed') {
            $message = 'service completed';
            $updated = $booking->update(['status' => 'completed', 'completed_at' => $nowDate]);
        } elseif ($request->status == 'payment_update') {
            $updated = $booking->update([
                'payment_method' => $request->has('method') ? $request->method : 'cash',
                'payment_status' => $request->has('payment_status') ? $request->payment_status : 'paid',
                'payment_detail' => $request->has('detail') ? json_encode($request->detail) : null
            ]);
            $message = 'payment updated successfully...';
        }

        if ($request->status == 'cancelled' || $request->status == 'rejected') {
            $isActivePackage = ActivePackage::where('user_id', $booking->user_id)->where('vehicle_type', $booking->vehicle_type)->where('status', 'activated')->first();
            if ($isActivePackage) {
                $actviePackageBenifits = json_decode($isActivePackage->available_benefits);
                $benifitArr = array();
                foreach ($actviePackageBenifits as $key => $value) {
                    $value = json_decode($value);
                    $obj = [
                        'name' => $value->name,
                        'time' => (int) $value->time  + 1
                    ];
                    array_push($benifitArr, json_encode($obj));
                }

                $maxActviePackageBenifit =  (int) $isActivePackage->max_benifit + 1;
                $packActiveUpdate = $isActivePackage->update([
                    'available_benefits' => json_encode($benifitArr),
                    'max_benifit' => (string) $maxActviePackageBenifit,
                    'status' => 'activated'
                ]);
            }
            if ($request->rejection_notes) {
                $rejectBy = ServiceReject::create([
                    'booking_id' => $booking->id,
                    'user_id' => $auth->id,
                    'rejection_notes' => $request->rejection_notes,
                ]);
            }
        }


        if ($request->status == 'completed') {
            $isActivePackage = ActivePackage::where('user_id', $booking->user_id)->where('vehicle_type', $booking->vehicle_type)->where('status', 'activated')->first();
            if ($isActivePackage) {
                $actviePackageBenifits = json_decode($isActivePackage->available_benefits);
                $benifitArr = array();
                foreach ($actviePackageBenifits as $key => $value) {
                    $value = json_decode($value);
                    if ($value->time != 0) {
                        $obj = [
                            'name' => $value->name,
                            'time' => (int) $value->time
                        ];
                        array_push($benifitArr, json_encode($obj));
                    }
                }

                if (count($benifitArr) > 0) {
                    $packActiveUpdate = $isActivePackage->update([
                        'available_benefits' => json_encode($benifitArr)
                    ]);
                } else {
                    $packActiveUpdate = $isActivePackage->update([
                        'status' => 'completed'
                    ]);
                }
            }
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
                    'status' => $request->status
                ];

                if ($registration_ids != null) {
                    $title = ucfirst($booking->status) . ":" . $booking->booking_number;
                    $message = $message;
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
                        'booking' => "Booking : " . $booking->booking_number .  $message,
                        'status' => $request->status
                    ];

                    if ($registration_ids != null) {
                        $title = ucfirst($booking->status) . ":" . $booking->booking_number;
                        $message = $message;
                        $notify = PushNotification::notifyToToken($registration_ids, $title, $message, null, $click_actions);
                        Log::info("DRIVER PICK FORM VENDOR>>>>" . json_encode($notify));
                    }
                }
            }


            return response([
                'status' => 'success',
                'status_code' => 200,
                'message' => $message,
            ]);
        }
        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'Something went wrong...',
        ]);
    }
    public function getBookingDetail(Booking $booking)
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        return response([
            'status' => 'success',
            'status_code' => 200,
            'message' => '',
            'booking_detail' => new BookingResource($booking),
        ]);
    }
    public function assignCenter(Request $request, Booking $booking)
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $updated = $booking->update(['service_center_id' => $request->center_id]);


        if ($updated) {
            $center = ServiceCenter::find($request->center_id);
            $driver = Driver::find($booking->driver_id);

            $deviceLogs = DeviceLog::where('user_id', $center->user_id)->get();
            if ($deviceLogs) {
                $registration_ids = array();
                foreach ($deviceLogs as $deviceLog) {
                    if ($deviceLog->fcm_token != null) {
                        array_push($registration_ids, $deviceLog->fcm_token);
                    }
                }
                $click_actions = [
                    "click_action" => "SHOW_BOOKING",
                    'booking' => "Booking : " . $booking->booking_number . ' assign by ' . $driver->name,
                    'status' => 'assign center'
                ];

                if ($registration_ids != null) {
                    $title = "Vendor_assign" . ":" . $booking->booking_number;
                    $message = "vendor assign...";
                    $notify = PushNotification::notifyToToken($registration_ids, $title, $message, null, $click_actions);
                    Log::info("VEMDOR ASSIGN" . json_encode($notify));
                }
            }
            $deviceLogsCustomer = DeviceLog::where('user_id', $booking->user_id)->get();
            if ($deviceLogsCustomer) {
                $registration_ids = array();
                foreach ($deviceLogsCustomer as $deviceLog) {
                    if ($deviceLog->fcm_token != null) {
                        array_push($registration_ids, $deviceLog->fcm_token);
                    }
                }
                $click_actions = [
                    "click_action" => "SHOW_BOOKING",
                    'booking' => "Booking : " . $booking->booking_number . ' assign by ' . $driver->name,
                    'status' => 'assign center'
                ];

                if ($registration_ids != null) {
                    $title = "Vendor_assign" . ":" . $booking->booking_number;
                    $message = "vendor assign...";
                    $notify = PushNotification::notifyToToken($registration_ids, $title, $message, null, $click_actions);
                    Log::info("VEMDOR ASSIGN cUSTOMER"  . json_encode($notify));
                }
            }
            return  response(['status' => 'success', 'status_code' => 200, 'message' => 'Assign service center successfully...']);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'something went wrong! please try again.',
        ]);
    }
    public function checkIsActivePackages()
    {
        $activePackages = ActivePackage::where('status', 'activated')->get();

        foreach ($activePackages as $key => $aPackage) {
            $activetedDate = $aPackage->start_date;
            $expireDate = $aPackage->end_date;
            if ($activetedDate == $expireDate) {
                $aPackage->update(['status', 'completed']);
            }
        }
        return response([
            'status' => 'success',
            'status_code' => 204,
            'message' => 'successfully updated',
        ]);
    }
    public function addServiceLink(Request $request, Booking $booking)
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $updated = $booking->update(['url' => $request->url]);

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
                    "click_action" => "SHOW_LIVE_SERVICE",
                    'booking' => "Booking : " . $booking->booking_number . 'servicing watch live.',
                    'status' => 'link added'
                ];

                if ($registration_ids != null) {
                    $title = "Link_added" . ":" . $booking->booking_number;
                    $message = "live link added...";
                    $notify = PushNotification::notifyToToken($registration_ids, $title, $message, null, $click_actions);
                    Log::info("LINK ADDED" . json_encode($notify));
                }
            }
            return  response(['status' => 'success', 'status_code' => 200, 'message' => 'Link saved successfully...']);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'something went wrong! please try again.',
        ]);
    }
    public function changePaymentStatus(Request $request, Booking $booking)
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $update = $booking->update([
            'payment_method' => $request->has('method') ? $request->method : 'cash',
            'payment_status' => $request->status,
            'payment_detail' => $request->has('detail') ? json_encode($request->detail) : null
        ]);

        if ($update) return response(['status' => 'success', 'message' => 'payment status successfully updated', 'status_code' => 200], 200);

        return response([
            'status' => 'warning',
            'message' => 'something want wrong..',
            'status_code' => 503
        ]);
    }
    public function sendSMS()
    {
        $deviceLogs = DeviceLog::where('user_id', Auth::id())->get();
        if ($deviceLogs) {
            $registration_ids = array();
            foreach ($deviceLogs as $deviceLog) {
                if ($deviceLog->fcm_token != null) {
                    array_push($registration_ids, $deviceLog->fcm_token);
                }
            }
            $click_actions = [
                "click_action" => "SHOW_NEW_BOOKING",
                'booking' => 1,
                'status' => 'pending'
            ];

            if ($registration_ids != null) {
                $title = "New_booking" . ":" . 020202215;
                $message = "new Booking..";
                $notify = PushNotification::notifyToToken($registration_ids, $title, $message, null, $click_actions);
                Log::info("CRAETE ORDER" . json_encode($notify));
            }
        }
    }
}
