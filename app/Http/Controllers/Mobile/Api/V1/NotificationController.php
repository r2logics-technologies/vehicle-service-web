<?php

namespace App\Http\Controllers\Mobile\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\Api\V1\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getData()
    { 
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

         
        $notifications = Notification::whereRaw("find_in_set($auth->id,user_ids)")->latest()->get();
        if ($notifications && count($notifications)) return response([
            'status' => 'success',
            'message' => '',
            'notifications' => NotificationResource::collection($notifications),
        ]);

        return response([
            'status' => 'warning',
            'message' => 'No Data Found',
            'notifications' => '',
        ]);
    }
}
