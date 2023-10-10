<?php

namespace App\Http\Controllers\Mobile\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\Api\V1\PackageResource;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    public function getPackages(Request $request)
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $packageType = $request->package_type;
        if ($packageType == 'four_wheeler') {
            $packages = Package::where('package_type', 'car')->active()->get();
        } elseif ($packageType == 'two_wheeler') {
            $packages = Package::where('package_type', 'bike')->active()->get();
        } else if ($packageType == 'both') {
            $packages = Package::where('package_type', 'both')->active()->get();
        } else {
            $packages = Package::active()->get();
        }

        $package_color = [
            [
                "one" => "0, 102, 101, 1",
                "two" => "63, 197, 173, 1",
                "three" => "16, 126, 119, 1",
            ], [
                "one" => '251, 90, 124, 1',
                "two" => '254, 101, 133, 1',
                "three" => '254, 24, 74, 1'
            ],
        ];

        $colorArray = array();
        $num_loops_completed = 0;

        while ($num_loops_completed < count($packages)) {
            foreach ($package_color as $colors) {
                array_push($colorArray, (object) $colors);
                $num_loops_completed++;
                if ($num_loops_completed == count($packages)) {
                    break;
                }
            }
        }

        if ($packages && count($packages) > 0) {
            return response([
                'status' => 'success',
                'message' => "",
                'packages' => PackageResource::collection($packages),
                'package_color' => $colorArray,
            ]);
        } else {
            return response([
                'status' => 'warning',
                'status_code' => 500,
                'message' => 'No packages found.',
                'packages' => null,
            ]);
        }

        return response([
            'status' => 'error',
            'message' => 'Packages not found...',
        ]);
    }

    public function getAllPackages()
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $packages = Package::active()->get();


        $package_color = [
            [
                "one" => "0, 102, 101, 1",
                "two" => "63, 197, 173, 1",
                "three" => "16, 126, 119, 1",
            ], [
                "one" => '251, 90, 124, 1',
                "two" => '254, 101, 133, 1',
                "three" => '254, 24, 74, 1'
            ],
        ];

        $colorArray = array();
        $num_loops_completed = 0;

        while ($num_loops_completed < count($packages)) {
            foreach ($package_color as $colors) {
                array_push($colorArray, (object) $colors);
                $num_loops_completed++;
                if ($num_loops_completed == count($packages)) {
                    break;
                }
            }
        }

        if ($packages && count($packages) > 0) {
            return response([
                'status' => 'success',
                'message' => "",
                'packages' => PackageResource::collection($packages),
                'package_color' => $colorArray,
            ]);
        } else {
            return response([
                'status' => 'warning',
                'status_code' => 500,
                'message' => 'No packages found.',
                'packages' => null,
            ]);
        }

        return response([
            'status' => 'error',
            'message' => 'Packages not found...',
        ]);
    }
}
