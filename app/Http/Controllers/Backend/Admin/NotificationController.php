<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Admin\NotificationResource;
use Illuminate\Http\Request;
use App\Models\DeviceLog;
use App\Models\Notification;
use App\Models\ServiceCenter;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PushNotification;

class NotificationController extends Controller
{
    public function centerNotification()
    {
        $authId = Auth::id();
        $notifications = Notification::whereRaw("find_in_set($authId,user_ids)")->latest()->get();
        if ($notifications && count($notifications)) return response([
            'status' => 'success',
            'message' => '',
            'notifications' => NotificationResource::collection($notifications),
        ]);

        return response([
            'status' => 'error',
            'message' => 'No Data Found',
            'notifications' => '',
        ]);
    }
    public function deleteData(Request $request, Notification $Notification)
    {
        $update = $Notification->update(['status' => $request->status]);
        $newNotification = Notification::find($Notification->id);

        if ($update) return response([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Notification data successfully ' . $request->status,
        ]);

        if ($request->status == 'activated') $message =
            'activated';
        else if ($request->status == 'deactivated') $message =
            'deactivated';
        else if ($request->status == 'deleted') $message = 'delete';

        return response([
            'status' => 'error',
            'status_code' => 400,
            'message' => 'Oops!! Something went wrong. Unable to ' . $message,
        ]);
    }

    public function getNotificationData()
    {
        Notification::whereDate('created_at', '<=', now()->subDays(2))->delete();
        $notifications = Notification::latest()->allowed()->active()->get();
        if ($notifications && count($notifications)) return response([
            'status' => 'success',
            'message' => '',
            'notifications' => NotificationResource::collection($notifications),
        ]);

        return response([
            'status' => 'error',
            'message' => 'No Data Found',
            'notifications' => '',
        ]);
    }

    public function sendNotification(Request $request)
    {
        $title = $request->title;
        $message = $request->description;

        if ($request->message == 'all') {
            $notify = PushNotification::notifyToTopic('everyone', $title, $message, null, null);
        } else if ($request->message == 'customers') {
            $notify = PushNotification::notifyToTopic('customer', $title, $message, null, null);
        } else if ($request->message == 'driver') {
            $notify = PushNotification::notifyToTopic('driver', $title, $message, null, null);
        } else if ($request->message == 'center') {
            $notify = PushNotification::notifyToTopic('service_center', $title, $message, null, null);
        } else {
            $ids = array();
            $ids = explode(",", $request->message);
            $users = User::whereIn('id', $ids)->active()->get();
            $registration_ids = array();
            foreach ($users as $key => $user) {
                $deviceLogs = DeviceLog::where('user_id', $user->id)->get();
                foreach ($deviceLogs as $deviceLog) {
                    array_push($registration_ids, $deviceLog->fcm_token);
                }
            }
            $title = $request->title;
            $message = $request->description;
            $notify = PushNotification::notifyToToken($registration_ids, $title, $message, "", "");
        }

        if ($notify) return response([
            'status' => 'success',
            'message' => 'Notification send successfully'
        ]);

        return response([
            'status' => 'warning',
            'message' => 'something want wrong'
        ]);
    }
}
