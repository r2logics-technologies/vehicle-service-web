<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Admin\CenterResource;
use App\Http\Resources\Backend\Admin\UserResource;
use App\Models\ServiceCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function getAuthData()
    {
        $auth = Auth::user(); 
        if($auth){
            $center = ServiceCenter::where("user_id",$auth->id)->first();
        } 
        if ($auth) return response([
            'status' => 'success',
            'message' => '',
            'center' => !$center ? null :new CenterResource($center),
            'user' => new UserResource($auth),
            'status_code' => 200,
        ]);

        return response([
            'status' => 'error',
            'message' => 'You are not logged in.',
            'status_code' => 401
        ]);
    }
}
