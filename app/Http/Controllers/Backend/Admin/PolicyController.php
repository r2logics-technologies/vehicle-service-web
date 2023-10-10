<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Admin\PolicyResource;
use App\Models\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function getData()
    {
        $policy = Policy::first();
        if ($policy) {
            return response([
                'status' => 'success',
                'message' => '',
                'policy' => new PolicyResource($policy)
            ]);
        }

        return response([
            'status' => 'error',
            'message' => 'policy not created yet',
            'policy' => null
        ]);
    }

    public function submitData(Request $request)
    {
        $message = "";
        $policy = Policy::first();
        if ($policy) {
            $update = $policy->update([
                'description' => $request->description,
            ]);
            $message = 'updated';
        } else {
            $create = Policy::create([
                'description' => $request->description,
            ]);
            $message = "created";
        }

        return response([
            'status' => 'success',
            'message' => 'Policy ' . $message . ' successfully',
        ]);
    }
}
