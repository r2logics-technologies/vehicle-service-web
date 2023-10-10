<?php

namespace App\Http\Controllers\Mobile\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\Api\V1\SettingResource;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function getData()
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $settings = Setting::first();
        if ($settings) {
            return response([
                'status' => 'success',
                'message' => '',
                'settings' => new SettingResource($settings),
            ]);
        } else {
            return response([
                'status' => 'warning',
                'status_code' => 500,
                'message' => 'No settings found.',
                'settings' => null,
            ]);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'No settings found...',
        ]);
    }
}
