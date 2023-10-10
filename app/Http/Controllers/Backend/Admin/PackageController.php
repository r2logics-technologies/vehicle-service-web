<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Admin\PackageResource;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function getData()
    {
        $packages = Package::allowed()->get();
        if ($packages && count($packages) > 0) {
            return response([
                'status' => 'success',
                'message' => '',
                'status_code' => 200,
                'packages' => PackageResource::collection($packages),
            ]);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'No packages found.',
            'packages' => null,
        ]);
    }
    public function submitData(Request $request)
    {
        $benefits = array();
        foreach ($request->benefit_name as $key => $name) {
            $benefit = [
                'name' => $name,
                'time' => $request->benefit_time[$key],
            ];
            array_push($benefits, $benefit);
        }
        if ($request->edited == -1) {
            $create = Package::create([
                'name' => $request->name,
                'package_type' => $request->package_type,
                'price' => $request->price,
                'duration' => $request->duration,
                'benefits' => json_encode($benefits),
            ]);
            return response(
                [
                    'status' => 'success',
                    'package' => new PackageResource($create),
                    'status_code' => 200,
                    'message' => 'Successfully added...'
                ],
                200
            );
        } else {
            $package = Package::find($request->edited);
            $update = $package->update([
                'name' => $request->name,
                'package_type' => $request->package_type,
                'price' => $request->price,
                'duration' => $request->duration,
                'benefits' => json_encode($benefits),
            ]);
            if ($update) {
                $update = Package::find($request->edited);
                return response(
                    [
                        'status' => 'success',
                        'package' => new PackageResource($update),
                        'status_code' => 200,
                        'message' => 'Successfully updated...'
                    ],
                    200
                );
            }
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'something went wrong...',
            'categories' => null,
        ], 500);
    }

    public function getEditData(Package $package)
    {
        return response(['status' => 'success', 'status_code' => 200, 'message' => "", 'package' => new PackageResource($package)]);
    }

    public function changeStatusData(Request $request, Package $package)
    {
        $message = "";
        if ($request->status == 'deleted') {
            $message = "Deleted";
        } else if ($request->status == 'activated') {
            $message = "Activated";
        } else {
            $message = "Deactivated";
        }
        $updated =  $package->update(['status' => $request->status]);

        if ($updated) {
            $package =  Package::find($package->id);
            return response(['status' => 'success', 'message' => $message, 'package' => new PackageResource($package)], 200);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'something want wrong. unable to ' . $message,

        ]);
    }
}
