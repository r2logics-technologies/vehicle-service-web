<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Admin\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function getData()
    {
        $categories = Category::allowed()->get();
        if ($categories && count($categories) > 0) {
            return response([
                'status' => 'success',
                'message' => '',
                'status_code' => 200,
                'categories' => CategoryResource::collection($categories),
            ], 200);
        }


        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'No categories found.',
            'categories' => null,
        ]);
    }

    public function submitData(Request $request)
    {
        if ($request->edited == -1) {
            $category = Category::where('name', $request->name)->first();
            if (!$category) {
                $created = Category::create([
                    'name' => Str::headline($request->name),
                    'status' => 'activated',
                ]);
                if ($created) return response(['status' => 'success', 'message' => "data successFully saved..", 'category' => new CategoryResource($created)], 200);

                return response(['status' => 'error', 'status_code' => 500, 'message' => 'something want wrong', 'category' => null,]);
            } else {
                return response(['status' => 'warning', 'status_code' => 505, 'message' => 'category already created..', 'category' => null]);
            }
        } else {
            $category = Category::find($request->edited);
            $updated = $category->update([
                'name' => $request->name,
            ]);
            if ($updated) {
                $category = Category::find($request->edited);
                return response(['status' => 'success', 'message' => "data successFully updated..", 'category' => new CategoryResource($category)], 200);
            }

            return response(['status' => 'warning', 'status_code' => 500, 'message' => 'something want wrong unable to updated', 'category' => null,]);
        }
    }

    public function changeStatusData(Request $request, Category $category)
    {
        $message = "";
        if ($request->status == 'deleted') {
            $message = "Deleted";
        } else if ($request->status == 'activated') {
            $message = "Activated";
        } else {
            $message = "Deactivated";
        }
        $updated =  $category->update(['status' => $request->status]);

        if ($updated) {
            $category =  Category::find($category->id);
            return response(['status' => 'success', 'message' => $message, 'category' => new CategoryResource($category)], 200);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'something want wrong. unable to' . $message,

        ]);
    }
}
