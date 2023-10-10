<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Admin\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BannerController extends Controller
{
    public function getData()
    {
        $banners = Banner::allowed()->get();
        if ($banners && count($banners) > 0) {
            return response([
                'status' => 'success',
                'message' => '',
                'status_code' => 200,
                'banners' => BannerResource::collection($banners),
            ]);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'No banners found.',
            'banners' => null,
        ]);
    }

    public function submitData(Request $request)
    {
        $image = "";
        if ($request->hasFile('image') && $request->image != null && $request->image != "") {
            $image = '/storage/' . $request->image->store('banners/images');
        }

        $createBanner = Banner::create([
            'image' => $image,
            'status' => 'activated',
        ]);

        if ($createBanner) {
            return response(['status' => 'success', 'banner' => new BannerResource($createBanner), 'status_code' => 200, 'message' => 'successfully added..'], 200);
        }
       
        return response(['status' => 'warning', 'status_code' => 500, 'message' => 'something went wrong'], 500);
    }

    public function changeStatusData(Request $request, Banner $banner)
    {
        $message = "";
        if ($request->status == 'deleted') {
            if (File::exists((public_path($banner->image)))) {
                File::delete(public_path($banner->image));
            }
            $message = "Deleted";
            $deleted =  $banner->delete(); 
            if ($deleted) {
                return response(['status' => 'success', 'message' => 'Deleted Successfully' ,200]);
            }

        } else if ($request->status == 'activated') {
            $message = "Activated";
        } else {
            $message = "Deactivated";
        }

        if($request->status  != 'deleted'){
        $updated =  $banner->update(['status' => $request->status]);
        }

       
        if ($updated) {
            $banner =  Banner::find($banner->id);
            return response(['status' => 'success', 'message' => $message, 'banner' => new BannerResource($banner)], 200);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'something want wrong. unable to ' . $message,
        ]);
    }
}
