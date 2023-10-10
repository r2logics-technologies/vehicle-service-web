<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Admin\ServiceResource;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ServiceController extends Controller
{
    public function getData()
    {
        $services = Service::allowed()->latest()->get();
        if ($services && count($services) > 0) {
            return response([
                'status' => 'success',
                'message' => '',
                'status_code' => 200,
                'services' => ServiceResource::collection($services),
            ]);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'No service services found.',
            'services' => null,
        ]);
    }

    public function submitData(Request $request)
    {
        $image = "";
        if ($request->hasFile('icon') && $request->icon != null && $request->icon != "") {
            $image = '/storage/' . $request->icon->store('services/images');
        }
        if ($request->edited == -1) {
            $created = Service::create([
                'category_id' => $request->cat_id,
                'icon' => $image,
                'name' => $request->name,
                'price' => $request->price,
            ]);

            if ($created) {
                return response(['status' => 'success', 'service' => new ServiceResource($created), 'status_code' => 200, 'message' => 'Successfully added..'], 200);
            }
        } else {
            $shop = Service::find($request->edited);
            if ($image != "") {
                if ($shop->icon != null && $shop->icon != "") {
                    if (File::exists((public_path($shop->icon)))) {
                        File::delete(public_path($shop->icon));
                    }
                }
            } else {
                $image = $shop->icon;
            }
            $updated = $shop->update([
                'category_id' => $request->cat_id,
                'icon' => $image,
                'name' => $request->name,
                'price' => $request->price,
            ]);

            $shop = Service::find($request->edited);
            if ($updated) {
                return response(['status' => 'success', 'service' => new ServiceResource($shop), 'status_code' => 200, 'message' => 'Successfully updated..'], 200);
            }
        }

        return response(['status' => 'warning', 'status_code' => 500, 'message' => 'something went wrong'], 500);
    }

    public function getEditData(Service $service)
    {
        return response(['status' => 'success', 'status_code' => 200, 'message' => "", 'service' => new ServiceResource($service)]);
    }

    public function changeStatusData(Request $request, Service $service)
    {
        $message = "";
        if ($request->status == 'deleted') {
            $message = "Deleted";
        } else if ($request->status == 'activated') {
            $message = "Activated";
        } else {
            $message = "Deactivated";
        }
        $updated =  $service->update(['status' => $request->status]);
        if ($updated) {
            $service =  Service::find($service->id);
            return response(['status' => 'success', 'message' => $message, 'service' => new ServiceResource($service)], 200);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'something want wrong. unable to ' . $message,

        ]);
    }
    public function getServices(Category $category)
    {
        $services = Service::where('category_id',$category->id)->get();
        if ($services && count($services) > 0) {
            return response([
                'status' => 'success',
                'message' => '',
                'status_code' => 200,
                'services' => ServiceResource::collection($services),
            ]);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'No service services found.',
            'services' => null,
        ]);
    }
}
