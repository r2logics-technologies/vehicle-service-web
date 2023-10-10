<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Admin\TermResource;
use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function getData()
    {
        $terms = Term::first();
        if ($terms) {
            return response([
                'status' => 'success',
                'message' => '',
                'terms' => new TermResource($terms)
            ]);
        }

        return response([
            'status' => 'error',
            'message' => 'terms not created yet',
            'terms' => null
        ]);
    }

    public function submitData(Request $request)
    {
        $message = "";
        $terms = Term::first();
        if ($terms) {
            $update = $terms->update([
                'description' => $request->description,
            ]);
            $message = 'updated';
        } else {
            $create = Term::create([
                'description' => $request->description,
            ]);
            $message = "created";
        }

        return response([
            'status' => 'success',
            'message' => 'Terms ' . $message . ' successfully',
        ]);
    }
}
