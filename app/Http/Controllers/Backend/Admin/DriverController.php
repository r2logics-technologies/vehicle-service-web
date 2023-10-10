<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Admin\DriverResource;
use App\Models\BankDetail;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function getData()
    {
        $drivers = Driver::allowed()->get();
        if ($drivers && count($drivers) > 0) {
            return response([
                'status' => 'success',
                'message' => '',
                'status_code' => 200,
                'drivers' => DriverResource::collection($drivers),
            ]);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'No drivers found.',
            'drivers' => null,
        ]);
    }

    public function submitData(Request $request)
    {
        // return dd($request->all());
        $avatar = "";
        $license_front = "";
        $license_back = "";
        $aadhar_front = "";
        $aadhar_back = "";
        // return dd($request->all());
        if ($request->hasFile('avatar') && $request->avatar != null && $request->avatar != "") {
            $avatar = '/storage/' . $request->avatar->store('drivers/images');
        }
        if ($request->hasFile('license_front') && $request->license_front != null && $request->license_front != "") {
            $license_front = '/storage/' . $request->license_front->store('drivers/licenses');
        }
        if ($request->hasFile('license_back') && $request->license_back != null && $request->license_back != "") {
            $license_back = '/storage/' . $request->license_back->store('drivers/licenses');
        }
        if ($request->hasFile('aadhar_front') && $request->aadhar_front != null && $request->aadhar_front != "") {
            $aadhar_front = '/storage/' . $request->aadhar_front->store('drivers/aadhar_card');
        }
        if ($request->hasFile('aadhar_back') && $request->aadhar_back != null && $request->aadhar_back != "") {
            $aadhar_back = '/storage/' . $request->aadhar_back->store('drivers/aadhar_card');
        }

        if ($request->edited == -1) {
            $exist = Driver::where('mobile', $request->mobile)->orWhere('email', $request->email)->first();
            if ($exist) return response([
                'status' => 'warning',
                'status_code' => 500,
                'message' => 'Driver already exists.',
            ]);

            $createUser = User::create([
                'avatar' => $avatar,
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => 'driver',
            ]);
            $user = User::where('id', $createUser->id)->first();
            $created = Driver::create([
                'user_id' => $user->id,
                'avatar' => $avatar,
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'license_front' => $license_front,
                'license_back' => $license_back,
                'aadhar_front' => $aadhar_front,
                'aadhar_back' => $aadhar_back,
                'address_line_1' => $request->address_line1,
                'address_line_2' => $request->address_line2,
                'location' => $request->location,
                'latitude' => $request->has('latitude')  ? $request->latitude : '',
                'longitude' => $request->has('longitude') ? $request->longitude : '',
                'landmark' => $request->landmark,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'postcode' => $request->postcode,
            ]);
            if ($created) {
                $bankDetails = BankDetail::create([
                    'user_id' => $createUser->id,
                    'holder_name' => $request->holder_name,
                    'account_number' => $request->account_number,
                    'confirm_account_number' => $request->confirm_account_number,
                    'name' => $request->bank_name,
                    'branch' => $request->bank_branch_name,
                    'ifsc' => $request->upi,
                    'upi' => $request->ifsc,
                ]);
                return response(['status' => 'success', 'driver' => new DriverResource($created), 'status_code' => 200, 'message' => 'successfully added..'], 200);
            }
        } else {
            $driver = Driver::find($request->edited);

            $user = User::find($driver->user_id);
            $user->update([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
            ]);

            if ($avatar != "" && $avatar != null) {
                if ($driver->avatar != null && $driver->avatar != "") {
                    if (File::exists((public_path($driver->avatar)))) {
                        File::delete(public_path($driver->avatar));
                    }
                }
            } else {
                $avatar = $driver->avatar;
            }

            if ($license_front != "" && $license_front != null) {
                if ($driver->license_front != null && $driver->license_front != "") {
                    if (File::exists((public_path($driver->license_front)))) {
                        File::delete(public_path($driver->license_front));
                    }
                }
            } else {
                $license_front = $driver->license_front;
            }

            if ($license_back != "" && $license_back != null) {
                if ($driver->license_back != null && $driver->license_back != "") {
                    if (File::exists((public_path($driver->license_back)))) {
                        File::delete(public_path($driver->license_back));
                    }
                }
            } else {
                $license_back = $driver->license_back;
            }

            if ($aadhar_front != "" && $aadhar_front != null) {
                if ($driver->aadhar_front != null && $driver->aadhar_front != "") {
                    if (File::exists((public_path($driver->aadhar_front)))) {
                        File::delete(public_path($driver->aadhar_front));
                    }
                }
            } else {
                $aadhar_front = $driver->aadhar_front;
            }

            if ($aadhar_back != "" && $aadhar_back != null) {
                if ($driver->aadhar_back != null && $driver->aadhar_back != "") {
                    if (File::exists((public_path($driver->aadhar_back)))) {
                        File::delete(public_path($driver->aadhar_back));
                    }
                }
            } else {
                $aadhar_back = $driver->aadhar_back;
            }

            $updated = $driver->update([
                'avatar' => $avatar,
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'license_front' => $license_front,
                'license_back' => $license_back,
                'aadhar_front' => $aadhar_front,
                'aadhar_back' => $aadhar_back,
                'address_line_1' => $request->address_line1,
                'address_line_2' => $request->address_line2,
                'location' => $request->location,
                'latitude' => $request->has('latitude')  ? $request->latitude : '',
                'longitude' => $request->has('longitude') ? $request->longitude : '',
                'landmark' => $request->landmark,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'postcode' => $request->postcode,
            ]);
            $driver = Driver::find($request->edited);
            if ($updated) {
                $bank = BankDetail::where('user_id', $driver->user_id)->first();
                if (!$bank) {
                    $bankDetails = BankDetail::create([
                        'user_id' => $driver->user_id,
                        'holder_name' => $request->holder_name,
                        'account_number' => $request->account_number,
                        'confirm_account_number' => $request->confirm_account_number,
                        'name' => $request->bank_name,
                        'branch' => $request->bank_branch_name,
                        'ifsc' => $request->upi,
                        'upi' => $request->ifsc,
                    ]);
                } else {
                    $bankDetails = $bank->update([
                        'holder_name' => $request->holder_name,
                        'account_number' => $request->account_number,
                        'confirm_account_number' => $request->confirm_account_number,
                        'name' => $request->bank_name,
                        'branch' => $request->bank_branch_name,
                        'ifsc' => $request->upi,
                        'upi' => $request->ifsc,
                    ]);
                }
                return response(['status' => 'success', 'driver' => new DriverResource($driver), 'status_code' => 200, 'message' => 'successfully updated..'], 200);
            }
        }

        return response(['status' => 'warning', 'status_code' => 500, 'message' => 'something went wrong'], 500);
    }

    public function getEditData(Driver $driver)
    {
        return response(['status' => 'success', 'status_code' => 200, 'message' => "", 'driver' => new DriverResource($driver)]);
    }

    public function changeStatusData(Request $request, Driver $driver)
    {
        $message = "";
        if ($request->status == 'deleted') {
            $message = "Deleted";
        } else if ($request->status == 'activated') {
            $message = "Activated";
        } else {
            $message = "Deactivated";
        }
        $updated =  $driver->update(['status' => $request->status]);

        if ($updated) {
            $driver =  Driver::find($driver->id);
            return response(['status' => 'success', 'message' => $message, 'driver' => new DriverResource($driver)], 200);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'something want wrong. unable to ' . $message,

        ]);
    }
}
