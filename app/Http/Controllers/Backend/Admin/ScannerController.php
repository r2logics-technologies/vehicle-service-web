<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Admin\ScannerResource;
use App\Models\Scanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ScannerController extends Controller
{
    public function getData()
    {
        $scanner = Scanner::allowed()->first();
        if ($scanner) {
            return response([
                'status' => 'success',
                'message' => '',
                'status_code' => 200,
                'scanner' => new ScannerResource($scanner),
            ]);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'No scanner found.',
            'scanner' => null,
        ]);
    }

    public function submitData(Request $request)
    {
        $image = "";

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('scanners/images', 'public');
            $image = '/storage/' . $imagePath;
        }

        $scanner = Scanner::allowed()->first();

        if ($scanner) {
            $imagePath = $scanner->image;

            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            $updated = $scanner->update(['image' => $image]);
        } else {
            $updated = Scanner::create(['image' => $image]);
        }

        if ($updated) {
            $updatedScanner = Scanner::allowed()->first();
            return response([
                'status' => 'success',
                'scanner' => new ScannerResource($updatedScanner),
                'status_code' => 200,
                'message' => 'Successfully updated.',
            ], 200);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'Something went wrong.',
        ], 500);
    }
}
