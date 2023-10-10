<?php

namespace App\Http\Controllers\Mobile\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\Api\V1\UserResource;
use App\Models\DeviceLog;
use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PushNotification;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        Log::info('Registration  Log:::' . json_encode($request->all()));
        $user = User::where('mobile', $request->mobile)->role($request->user_type)->first();
        $registrationIds = array();
        $deviceRegistered = false;
        Log::info("REGISTRATION USER LOG ::" . json_encode($user));
        if ($user) {
            $deviceLog = DeviceLog::where('user_id', $user->id)->where('device_type', $request->device_type)->where('device_id', $request->device_id)->first();
            if ($deviceLog) {
                $deviceRegistered = true;
                Log::info('Device registered Log:::' . json_encode($deviceLog));
            }

            if (!$deviceRegistered) {
                Log::info('Device not registered Log:::' . json_encode($deviceLog));
                $deviceLog = DeviceLog::create([
                    'user_id' => $user->id,
                    'device_type' => $request->device_type,
                    'device_id' => $request->device_id,
                    'fcm_token' => $request->fcm_token,
                ]);
                $fcm_topics = json_decode($user->fcm_topics);
                if ($fcm_topics == null) {
                    $fcm_topics = array("everyone");
                }
                if (!in_array($request->device_type, $fcm_topics)) {
                    array_push($fcm_topics, $request->device_type && $request->device_type != null ? $request->device_type : "android");
                }
                if (!in_array($request->user_type, $fcm_topics)) {
                    array_push($fcm_topics, $request->user_type && $request->user_type != null ? $request->user_type : "customer");
                }

                $userUpdate = $user->update(['fcm_topics' => json_encode($fcm_topics)]);
            } else {
                return response(['status' => 'error', 'message' => 'already registered'], 200);
            }
        } else {
            $user = User::create([
                'country_code' => $request->country_code,
                'mobile' => $request->mobile,
                'user_type' => 'customer',
                'fcm_topics' => json_encode(["everyone", $request->device_type, $request->user_type]),
            ]);

            if ($user) {
                $deviceLog = DeviceLog::create([
                    'user_id' => $user->id,
                    'device_type' => $request->device_type,
                    'device_id' => $request->device_id,
                    'fcm_token' => $request->fcm_token,
                ]);
            }
        }

        if (!$user) {
            return response(['status' => 'error', 'message' => 'Something went wrong'], 200);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => new UserResource($user),
            'token' => $token,
            'status' => 'success',
            'message' => 'register successfully'
            // 'fcm_token' => $registrationIds,
        ];
        return response($response, 201);
    }

    public function loginCheck(Request $request)
    {
        $users = User::where('mobile', $request->mobile)->mobileUser()->active()->get();
        if (count($users) == 0) {
            return response(['status' => 'not-registered', 'message' => ""]);
        }
        $response = array();
        $response['status'] = "success";
        $response['user_types'] = array();
        if ($users && count($users) > 0) {
            foreach ($users as $key => $user) {
                if (!in_array($user->user_type, $response['user_types'])) {
                    array_push($response['user_types'], $user->user_type);
                }
            }
        }
        return response($response);
    }

    public function login(Request $request)
    {
        Log::info('Login  Log:::' . json_encode($request->all()));

        $demoNumber = array("9876543201", "9876543202", "9876543203", "9876543204", "9876543205", "9876543206", "9876543207", "9876543208", "9876543209");

        $request->validate([
            'country_code' => 'required|string',
            'mobile' => 'required|regex:/^[0-9]{10}$/',
            'device_id' => 'required|string',
            'device_type' => 'required|string',
            'user_type' => 'required|string'
        ]);



        $user = User::where('mobile', $request->mobile)->role($request->user_type)->first();
        if (!$user) {
            return response([
                'status' => 'warning',
                'message' => 'user not register',
            ], 200);
        }

        $deviceRegistered = false;
        if ($user) {
            Log::info('Check USER ::' . json_encode($user->deviceLogs));
            if ($user->status == 'activated' || $user->status == 'pending') {
                $deviceUser = User::where('id', $user->id)->with('deviceLogs')->whereHas('deviceLogs', function ($query) use ($request) {
                    $query->where('device_id', $request->device_id)->where('device_type', $request->device_type);
                })->first();
                Log::info('Check DEVICE ::' . json_encode($deviceUser));
                if (!$deviceUser) {
                    return response([
                        'status' => 'device-not-register',
                        'message' => 'not register',
                        'user' => new UserResource($user),
                    ], 200);
                }
            } else {
                if ($user->status == 'blocked') {
                    $statusMessage = "This account has been block by Administrator";
                } else {
                    $statusMessage = "Your account is deactivated. Please contact Administrator to re-activate your account";
                }
                LoginLog::create([
                    'mobile' => $request->mobile,
                    'device_id' => $request->device_id,
                    'otp_response' => NULL,
                    'remark' => $statusMessage,
                ]);
                $response = [
                    'user' => new UserResource($user),
                    'token' => null,
                    'status' => 'unauthorize',
                    'message' => $statusMessage,
                ];
                return response($response, 200);
            }
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        $fcm_topics = json_decode($user->fcm_topics);
        if ($fcm_topics == null) {
            $fcm_topics = array("everyone");
        }
        if (!in_array($request->device_type, $fcm_topics)) {
            array_push($fcm_topics, $request->device_type && $request->device_type != null ? $request->device_type : "android");
        }
        if (!in_array($request->user_type, $fcm_topics)) {
            array_push($fcm_topics, $request->user_type && $request->user_type != null ? $request->user_type : "customer");
        }
        if ($request->fcm_token)
            $user->update(['fcm_token' => $request->fcm_token, 'fcm_topics' => json_encode($fcm_topics)]);
        Log::info("FCM::" . json_encode($user));
        $deviceLogs = DeviceLog::where('user_id', $user->id)->where('device_type', $request->device_type)->where('device_id', $request->device_id)->first();
        if ($deviceLogs) $deviceLogs->update(['fcm_token' => $request->fcm_token]);

        LoginLog::create([
            'mobile' => $request->mobile,
            'device_id' => $request->device_id,
            'otp_response' => NULL,
            'remark' => 'login success',
        ]);

        $response = [
            'user' => new UserResource($user),
            'token' => $token,
            'status' => 'success',
            'message' => 'login success'
        ];

        return response($response, 200);
    }

    public function verifyUser($device)
    {
        if ($device != 'android' && $device != 'ios') {
            return response(['message' => 'Route not found'], 404);
        }

        $user = auth()->user();
        $user = User::allowed($user->mobile, $user->role_id, $device)->first();

        if (!$user) {
            return response([
                'status' => 'warning',
                'message' => 'Your account is deactivated.. Please contact administrator'
            ], 401);
        }

        return response([
            'status' => 'success',
            'message' => ''
        ], 200);
    }

    public function logout()
    {
        $user = auth()->user();
        $user->currentAccessToken()->delete();

        return response(['message' => 'user logged out'], 200);
    }

    public function updateFCMToken(Request $request)
    {
        $authUser = auth()->user();

        $user = User::where('id', $authUser->id)->with(['deviceLogs' => function ($query) use ($request) {
            return $query->where('device_id', $request->device_id);
        }])->first();

        if (!$user) return response([
            'status' => 'error',
            'message' => 'User not registered'
        ]);

        if ($user->deviceLogs && count($user->deviceLogs) > 0) {
            foreach ($user->deviceLogs as $key => $deviceLog) {
                $deviceLog->update(['fcm_token' => $request->fcm_token]);
            }
        } else {
            $deviceLog = DeviceLog::create([
                'user_id' => $user->id,
                'device_type' => $request->device_type,
                'device_id' => $request->device_id,
                'fcm_token' => $request->fcm_token,
            ]);
        }
        return response([
            'status' => 'success',
            'message' => 'fcm token updated'
        ], 200);
    }

    public function fcmTokenRegister(Request $request, $device)
    {
        $user = auth()->user();
        if ($device != 'android' && $device != 'ios') {
            return response(['message' => 'Route not found'], 404);
        }

        if ($user->device_type != $device) {
            return response(['message' => 'Wrong route'], 401);
        }

        $user = User::where('id', $user->id)->first();

        $deviceLog = DeviceLog::create([
            'user_id' => $user->id,
            'device_type' => $user->device_type,
            'device_id' => $request->device_id,
            'fcm_token' => $request->fcm_token,
        ]);

        $user->update(['fcm_token' => $request->fcm_token]);

        return response([
            'message' => 'fcm token updated',
            'fcm_topics' => $user->fcm_topics
        ], 200);
    }

    public function otpResent(Request $request)
    {
        $demoNumber = array("9876543201", "9876543202", "9876543203", "9876543204", "9876543205", "9876543206", "9876543207", "9876543208", "9876543209");
        $mobile = ($request->country_code != null ? $request->country_code : '+91') . $request->mobile;
        if (!in_array($request->mobile, $demoNumber)) {
            $sms = PushNotification::sendSMS($mobile, $request->otp);
        } else {
            $otp = "111111";
        }
        return response([
            'status' => 'success',
            'otp' => "" . $request->otp,
        ]);
    }

    public function deviceLogout(Request $request)
    {
        $user = auth()->user();
        $deviceLog = DeviceLog::where('user_id', $user->id)->where('device_id', $request->device_id)->delete();
        if ($deviceLog) return response([
            'status' => 'success',
            'message' => 'Device logout sccessfully..',
        ]);

        return response([
            'status' => 'error',
            'message' => 'something went wrong..',
        ]);
    }

    public function getLoginLogs($mobile)
    {
        $log = LoginLog::where('mobile', $mobile)->latest()->first();

        if ($log) return response([
            'status' => 'success',
            'log' => $log
        ]);

        return response([
            'status' => 'error',
            'message' => 'no log found..',
        ]);
    }
}
