<?php

namespace App\Http\Controllers\Mobile\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\Api\V1\UserAddressResource;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{ 
    public function getAddresses()
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $addresses = UserAddress::where('user_id', $auth->id)->active()->get();
        if ($addresses && count($addresses) > 0) {
            return response([
                'status' => 'success',
                'message' => '',
                'addresses' => UserAddressResource::collection($addresses),
            ]);
        }

        return response([
            'status' => 'error',
            'message' => '',
            'addresses' => null,
        ]);
    }

    public function saveAddressData(Request $request)
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);
        // $request->validate([
        //     'title' =>  'required|string',
        //     'name' => 'required|string',
        //     'mobile' => 'required|regex:/^[0-9]{10}$/',
        //     'alternate_mobile' =>  'regex:/^[0-9]{10}$/',
        //     'address_line1' => 'required|string',
        //     'address_line2' => 'nullable|string',
        //     'locality' => 'nullable|string',
        //     'landmark' => 'nullable|string',
        //     'pincode' => 'required|string',
        //     'city' => 'required|string',
        //     'state' => 'required|string',
        //     'country' => 'required|string',
        // ]);

        // $latitude = $request->latitude;
        // $longitude = $request->longitude;

        // $address = $request->address_line1 . " " . $request->address_line2 . " " . $request->locality . " " . $request->landmark . " " . $request->pincode . " " . $request->city . " " . $request->state . " " . $request->country; // Google HQ
        // $prepAddr = str_replace(' ', '+', $address);
        // $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&sensor=false&key=AIzaSyCEqg5ekhi5J0LZXIAFW3Xn65MoAVl4Hc8');
        // $output = $geocode == null ? null : json_decode($geocode);
        // if ($output != null) {
        //     $latitude = $output->results[0]->geometry->location->lat;
        //     $longitude = $output->results[0]->geometry->location->lng;
        // }

        $created = UserAddress::create([
            'user_id' => $auth->id,
            'title' => $request->title,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'alternate_mobile' => $request->alternate_mobile,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'locality' => $request->locality,
            'landmark' => $request->landmark,
            'pincode' => $request->pincode,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            // 'latitude' => $latitude,
            // 'longitude' => $longitude,
        ]);

        if ($created) {
            return response([
                'status' => 'success',
                'message' => 'address created successfully',
                'user_address' => new UserAddressResource($created),
            ]);
        }

        return response([
            'status' => 'error',
            'message' => 'Something went wrong',
        ]);
    }

    public function updateAddressData(Request $request, UserAddress $userAddress)
    {
        $authUser = Auth::user();
        if (!$authUser) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);
        
        $user = User::find($authUser->id);

        $userAddress = UserAddress::where('id', $userAddress->id)->where('user_id', $user->id)->first();

        if (!$userAddress) return response([
            'status' => 'error',
            'message' => 'No address found'
        ]);
        // $request->validate([
        //     'title' =>  'required|string',
        //     'name' => 'required|string',
        //     'mobile' => 'required|regex:/^[0-9]{10}$/',
        //     'alternate_mobile' =>  'regex:/^[0-9]{10}$/',
        //     'address_line1' => 'required|string',
        //     'address_line2' => 'nullable|string',
        //     'locality' => 'nullable|string',
        //     'landmark' => 'nullable|string',
        //     'pincode' => 'required|string',
        //     'city' => 'required|string',
        //     'state' => 'required|string',
        //     'country' => 'required|string',
        // ]);

        // if ($request->latitude == null && $request->latitude == "") {
        //     $address = $request->address_line1 . " " . $request->address_line2 . " " . $request->locality . " " . $request->landmark . " " . $request->pincode . " " . $request->city . " " . $request->state . " " . $request->country; // Google HQ
        //     $prepAddr = str_replace(' ', '+', $address);
        //     $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&sensor=false&key=AIzaSyCEqg5ekhi5J0LZXIAFW3Xn65MoAVl4Hc8');
        //     $output = json_decode($geocode);
        //     $latitude = $output->results[0]->geometry->location->lat;
        //     $longitude = $output->results[0]->geometry->location->lng;
        // } else {
        //     $latitude = $request->latitude;
        //     $longitude = $request->longitude;
        // }

        $updated = $userAddress->update([
            'title' => $request->title,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'alternate_mobile' => $request->alternate_mobile,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'locality' => $request->locality,
            'landmark' => $request->landmark,
            'pincode' => $request->pincode,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            // 'latitude' => $latitude,
            // 'longitude' => $longitude,
        ]);

        if (!$updated) return response([
            'status' => 'error',
            'message' => 'Oops!! Something went wrong. Unable to update address'
        ]);

        return response([
            'status' => 'success',
            'message' => 'Address updated successfully',
            'user_address' => new UserAddressResource($userAddress),
        ]);
    }

    public function deleteAddressData(UserAddress $userAddress)
    {
        $authUser = Auth::user();
        if (!$authUser) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        if (!$userAddress) return response([
            'status' => 'error',
            'message' => 'No address found'
        ]);

        if ($userAddress->status == 'deleted') return response([
            'status' => 'warning',
            'message' => 'This address is already deleted'
        ]);

        $updated = $userAddress->update(['status' => 'deleted']);

        if (!$updated) return response([
            'status' => 'error',
            'message' => 'Oops!! Something went wrong. Unable to delete this address'
        ]);

        return response([
            'status' => 'success',
            'message' => 'Address deleted successfully',
        ]);
    }
}
