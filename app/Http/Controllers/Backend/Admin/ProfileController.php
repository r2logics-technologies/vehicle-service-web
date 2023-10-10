<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Admin\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function getData()
    {
        $auth = Auth::user();
        if ($auth) return response([
            'status' => 'success',
            'message' => '',
            'user' => new UserResource($auth),
            'status_code' => 200,
        ]);

        return response([
            'status' => 'error',
            'message' => 'You are not logged in.',
            'status_code' => 401
        ]);
    }

    public function chanegProfile(Request $request)
    {
        $profile = User::find($request->id);
        $avatar = "";
        if ($request->hasFile('user_avatar') && $request->user_avatar != null && $request->user_avatar != "") {
            $avatar = '/storage/' . $request->user_avatar->store('user/profile');
        }

        if ($avatar != "") {
            if ($profile->avatar != null && $profile->avatar != "") {
                if (File::exists((public_path($profile->avatar)))) {
                    File::delete(public_path($profile->avatar));
                }
            }
        } else {
            $avatar = $profile->avatar;
        }

        if ($request->pass_change == 0) {
            $change = $profile->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'avatar' => $avatar,
            ]);
            if ($change) {
                $change = User::find($profile->id);
                return response(
                    [
                        'status' => 'success',
                        'userUpdate' => new UserResource($change),
                        'status_code' => 200,
                        'message' => 'Successfully Saved...'
                    ],
                    200
                );
            }
        } else {
            if (Hash::check($request->current_password,  $profile->password)) {
                $passChange = $profile->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'avatar' => $avatar,
                    'password' => Hash::make($request->new_password),
                ]);

                if ($passChange) {
                    $passChange = User::find($profile->id);
                    return response(
                        [
                            'status' => 'success',
                            'userUpdate' => new UserResource($passChange),
                            'status_code' => 200,
                            'message' => 'Successfully Saved...'
                        ],
                        200
                    );
                }
            } else {
                return response([
                    'status' => 'warning',
                    'status_code' => 200,
                    'message' => 'Password not correct...',
                    'userUpdate' => null,
                ], 200);
            }
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'something went wrong...',
            'userUpdate' => null,
        ], 500);
    }
}
