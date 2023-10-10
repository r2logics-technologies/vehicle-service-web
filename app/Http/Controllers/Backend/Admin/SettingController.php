<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Admin\SettingResource;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function getData()
    {
        $setting = Setting::first();
        if ($setting) {
            return response([
                'status' => 'success',
                'message' => '',
                'setting' => new SettingResource($setting)
            ]);
        }

        return response([
            'status' => 'error',
            'message' => 'setting not created yet',
            'setting' => null
        ]);
    }

    public function changeSetting(Request $request)
    {
        $message = "";
        $logo = "";
        if ($request->hasFile('logo') && $request->logo != null && $request->logo != "") {
            $logo = '/storage/' . $request->logo->store('setting/logo');
        }

        $setting = Setting::first();
        if ($setting) {
            if (File::exists((public_path($setting->logo)))) {
                File::delete(public_path($setting->logo));
            }
            $update = $setting->update([
                'logo' => $logo,
                'app_name' => $request->app_name,
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'current_version' => $request->version,
            ]);
            $message = 'updated';
        } else {
            $create = Setting::create([
                'logo' => $logo,
                'app_name' => $request->app_name,
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'current_version' => $request->version,
            ]);
            $message = "created";
        }

        return response([
            'status' => 'success',
            'message' => 'Setting ' . $message . ' successfully',
        ]);
    }
}
