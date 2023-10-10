<?php

namespace App\Http\Controllers\Mobile\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\Api\V1\ActivePackageResource;
use App\Http\Resources\Mobile\Api\V1\InstructionResource;
use App\Http\Resources\Mobile\Api\V1\FreeAccessoriesResource;
use App\Http\Resources\Mobile\Api\V1\TransactionResource;
use App\Http\Resources\Mobile\Api\V1\UserResource;
use App\Models\ActivePackage;
use App\Models\Booking;
use App\Models\Instruction;
use App\Models\FreeAccessories;
use App\Models\TransactionPoints;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function generateReferralCode()
    {
        $length = 10;
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!@#$%^&*()';
        $prefix = 'REF-';
        $suffix = '';
        $refCode = $prefix . Str::random($length, $characters) . $suffix;
        $count = User::where('referral_id', $refCode)->count();
        if ($count == 0) {
            $refCode = $prefix . Str::random($length, $characters) . $suffix;
        }
        return Str::upper($refCode);
    }
    public function profileUpdate(Request $request)
    {
        Log::info('Registration  Log:::' . json_encode($request->all()));
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);


        $user = User::where('id', $auth->id)->first();
        $avatar = $user->avatar;

        // if($request->has('reference_id') && $request->reference_id != null && $request->reference_id != ""){
        //     $isValidRefernce = User::where('reference_id','!=' ,$request->reference_id)->where(function($query){
        //         $query->where('id','!=',$user->id)->where('reference_id','!=', $user->reference_id);
        //     })->first();
        //     return response(['status' => 'warning', 'message' => 'this refernce code is not valid']);

        // }


        //Reference Code Update
        if ($request->has('reference_id') && $request->reference_id != null && $request->reference_id != "") {
            $isReferValid = User::where('referral_id', $request->reference_id)->first();
            if ($isReferValid) {
                $referUpdate = $user->update([
                    'reference_id' => $request->reference_id,
                ]);
            } else {
                return response(['status' => 'warning', 'message' => 'referal id invalid!']);
            }
        }

        //Referral code update
        $refCode = $this->generateReferralCode();
        $isRefValid = $user->referral_id == null;
        if ($isRefValid) {
            $refUpdate = $user->update([
                'referral_id' => $refCode,
            ]);
        }

        // Avatar Update
        if ($request->has('avatar') && $request->avatar != null && $request->avatar != "") {
            if ($user->avatar != null && $user->avatar != "") {
                Storage::delete($user->avatar);
            }
            $avatar = '/storage/' . $request->avatar->store('users/avatar');
        }

        $updated = $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'avatar' => $avatar,
            'mobile' => $request->mobile,
        ]);


        $authUser = User::find($auth->id);
        if ($updated) {

            //Add Points
            $rewardPoints = 50;
            if ($request->filled('reference_id')) {
                //add for referral person
                $refPerson = User::where('referral_id', $request->reference_id)->first();

                if ($refPerson) {
                    $refRewardPoints = $refPerson->reward_points + $rewardPoints;
                    $refPersonUpdate = $refPerson->update([
                        'reward_points' => $refRewardPoints,
                    ]);

                    if ($refPersonUpdate) {
                        $refPersonTransCredit = TransactionPoints::create([
                            'user_id' => $refPerson->id,
                            'reward_points' => $rewardPoints,
                            'transaction_type' => 'cr'
                        ]);
                        //add for new user 
                        $referUpdate = $authUser->update([
                            'reward_points' => $rewardPoints
                        ]);
                        if ($referUpdate) {
                            $refTransCredit = TransactionPoints::create([
                                'user_id' => $authUser->id,
                                'reward_points' => $rewardPoints,
                                'transaction_type' => 'cr'
                            ]);
                        }
                    }
                }
            }
            return response([
                'status' => 'success',
                'message' => 'success',
                'user' => new UserResource($authUser),
            ]);
        }

        return response([
            'status' => 'error',
            'message' => 'error',
            'user' => null,
        ]);
    }
    public function getProfileData()
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);
        $user = User::where('id', $auth->id)->first();
        if ($user) return response([
            'status' => 'success',
            'message' => '',
            'user' => new UserResource($user),
        ]);
    }
    public function activePackages(Request $request)
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $activePackages  = ActivePackage::where('user_id', Auth::id())->where('vehicle_type', $request->type)->where('status', 'activated')->where('max_benifit', '!=', '0')->first();
        if ($activePackages) return response([
            'status' => 'success',
            'active_package' => new ActivePackageResource($activePackages),
        ]);
        return response([
            'status' => 'warning',
            'message' => 'No packages available',
        ]);
    }
    public function transactionHistory()
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $activePackageId = Booking::where('user_id',$auth->id)->selectRaw('MIN(id) AS id')->groupBy('active_package_id')->pluck('id');
        $userTransactions = Booking::where('user_id',$auth->id)->whereIn('id', $activePackageId)->get();

        if ($userTransactions) return response([
            'status' => 'success',
            'transactions' => TransactionResource::collection($userTransactions),
        ]);
        return response([
            'status' => 'warning',
            'message' => 'transations not available',
        ]);
    }
    public function bookingInstruction()
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $instructions = Instruction::allowed()->orderBy('id','DESC')->get();

        if ($instructions && count($instructions) > 0) return response([
            'status' => 'success',
         	'message' => '',
            'instructions' => InstructionResource::collection($instructions),
        ]);
        return response([
            'status' => 'warning',
            'message' => 'instructions not available',
         	'instructions' => null
        ]);
    }
  
public function bookingFreeAccessories()
    {
        $auth = Auth::user();
        if (!$auth) return response([
            'status' => 'unauthorized',
            'message' => 'user not available',
        ]);

        $free_accessories = FreeAccessories::allowed()->orderBy('id','DESC')->get();

        if ($free_accessories && count($free_accessories) > 0) return response([
            'status' => 'success',
         	'message' => '',
            'free_accessories' => FreeAccessoriesResource::collection($free_accessories),
        ]);
        return response([
            'status' => 'warning',
            'message' => 'Free Accessories not available',
         	'free_accessories' => null
        ]);
    }

  
}
