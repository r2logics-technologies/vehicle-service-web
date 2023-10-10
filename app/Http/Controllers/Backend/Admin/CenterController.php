<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Admin\BookingResource;
use App\Http\Resources\Backend\Admin\CenterResource;
use App\Models\BankDetail;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\Package;
use App\Models\ServiceCenter;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class CenterController extends Controller
{
    public function getData()
    {
        $actCenters = count(ServiceCenter::active()->get());
        $inActCenters = count(ServiceCenter::Inactive()->get());
        $allCenters = count(ServiceCenter::get());

        $states = [
            'allCenters' => $allCenters,
            'actCenters' => $actCenters,
            'inActCenters' => $inActCenters,
        ];
        $centers = ServiceCenter::allowed()->latest()->get();
        if ($centers && count($centers) > 0) {
            return response([
                'status' => 'success',
                'message' => '',
                'status_code' => 200,
                'states' =>  $states,
                'centers' => CenterResource::collection($centers),
            ]);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'No service centers found.',
            'centers' => null,
        ]);
    }

    public function submitData(Request $request)
    {
        $image = "";
        if ($request->hasFile('logo') && $request->logo != null && $request->logo != "") {
            $image = '/storage/' . $request->logo->store('shops/images');
        }
        if ($request->edited == -1) {
            $password = $request->password == null && $request->password == '' ? Hash::make('Center@12345') : Hash::make($request->password);
            $exist = ServiceCenter::where('mobile', $request->mobile)->orWhere('email', $request->email)->first();
            if ($exist) return response([
                'status' => 'warning',
                'status_code' => 500,
                'message' => 'Service center already exists.',
            ], 500);
            $createUser = User::create([
                'name' => $request->owner,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'password' => $password,
                'user_type' => 'service_center',
            ]);
            $user = User::where('id', $createUser->id)->first();
            $created = ServiceCenter::create([
                'user_id' => $user->id,
                'logo' => $image,
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'owner_name' => $request->owner,
                'password' => $password,
                "alter_email" => $request->alter_email,
                "alter_mobile" => $request->alter_mobile,
                "delivery_type" => $request->delivery_type,
                'description' => $request->description,
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

            if ($request->hasFile('document_file') && count($request->document_file) > 0) {
                foreach ($request->document_file as $key => $value) {
                    if ($value != null && $value != '') {
                        $file = '/storage/' . $value->store('user/documents');
                        $createdDocumet = UserDocument::create([
                            'user_id' => $user->id,
                            'name' => $request->document_name[$key],
                            'file' => $file,
                        ]);
                    }
                }
            }
            if ($created) {
                if ($request->cat_ids) {
                    $created->categories()->sync(explode(",", $request->cat_ids));
                }
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


                return response(['status' => 'success', 'center' => new CenterResource($created), 'status_code' => 200, 'message' => 'Successfully added..'], 200);
            }
        } else {
            $shop = ServiceCenter::find($request->edited);
            if ($image != "") {
                if ($shop->logo != null && $shop->logo != "") {
                    if (File::exists((public_path($shop->logo)))) {
                        File::delete(public_path($shop->logo));
                    }
                }
            } else {
                $image = $shop->logo;
            }
            $user = User::find($shop->user_id);
            $user->update([
                'name' => $request->owner,
                'mobile' => $request->mobile,
                'email' => $request->email,
            ]);
            $updated = $shop->update([
                'logo' => $image,
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'owner_name' => $request->owner,
                "alter_email" => $request->alter_email,
                "alter_mobile" => $request->alter_mobile,
                "delivery_type" => $request->delivery_type,
                'password' => Hash::make($request->password),
                'description' => $request->description,
                'address_line_1' => $request->address_line1,
                'address_line_2' => $request->address_line2,
                'location' => $request->location,
                'latitude' => $request->has('latitude')  ? $request->latitude : '',
                'longitude' => $request->has('longitude') ? $request->longitude : '',
                'landmark' => $request->landmark,
                'postcode' => $request->postcode,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
            ]);

            if ($request->hasFile('document_file') && count($request->document_file) > 0) {
                foreach ($request->document_file as $key => $value) {
                    if ($value != null && $value != '') {
                        $file = '/storage/' . $value->store('users/documents');
                        $createdDocumet = UserDocument::create([
                            'user_id' => $user->id,
                            'name' => $request->document_name[$key],
                            'file' => $file,
                        ]);
                    }
                }
            }

            $shop = ServiceCenter::find($request->edited);
            if ($updated) {
                if ($request->cat_ids) {
                    $shop->categories()->sync(explode(",", $request->cat_ids));
                }
                $bank = BankDetail::where('user_id', $shop->user_id)->first();
                if (!$bank) {
                    $bankDetails = BankDetail::create([
                        'user_id' => $shop->user_id,
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
                return response(['status' => 'success', 'center' => new CenterResource($shop), 'status_code' => 200, 'message' => 'Successfully updated..'], 200);
            }
        }

        return response(['status' => 'warning', 'status_code' => 500, 'message' => 'something went wrong'], 500);
    }

    public function getEditData(ServiceCenter $serviceCenter)
    {
        return response(['status' => 'success', 'status_code' => 200, 'message' => "", 'center' => new CenterResource($serviceCenter)]);
    }

    public function changeStatusData(Request $request, ServiceCenter $serviceCenter)
    {
        $message = "";
        if ($request->status == 'deleted') {
            $message = "Deleted";
        } else if ($request->status == 'activated') {
            $message = "Activated";
        } else {
            $message = "Deactivated";
        }
        $updated =  $serviceCenter->update(['status' => $request->status]);

        if ($updated) {
            $serviceCenter =  ServiceCenter::find($serviceCenter->id);
            return response(['status' => 'success', 'message' => $message, 'center' => new CenterResource($serviceCenter)], 200);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'something want wrong. unable to ' . $message,

        ]);
    }

    public function changeActivityStatusData(ServiceCenter $serviceCenter)
    {
        $message = $serviceCenter->activity_status == 'online' ? 'offline' : 'online';
        $updated =  $serviceCenter->update(['activity_status' => $serviceCenter->activity_status == 'online' ? 'offline' : 'online']);

        if ($updated) {
            $serviceCenter =  ServiceCenter::find($serviceCenter->id);
            return response(['status' => 'success', 'message' => $message, 'center' => new CenterResource($serviceCenter)], 200);
        }

        return response([
            'status' => 'warning',
            'status_code' => 500,
            'message' => 'something want wrong. unable to ' . $message,

        ]);
    }


    public function changeStatusRecommend(Request $request, ServiceCenter $serviceCenter)
    {
        $update = $serviceCenter->update(['recommend' => $request->recommend]);
        $newShop = ServiceCenter::find($serviceCenter->id);

        if ($update) return response([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Data updated successfully',
            'center' => new CenterResource($newShop)
        ]);

        return response([
            'status' => 'error',
            'status_code' => 400,
            'message' => 'Oops!! Something went wrong. Unable to Recommend',
        ]);
    }

    public function changeStatusSpotlight(Request $request, ServiceCenter $serviceCenter)
    {
        $update = $serviceCenter->update(['sportlight' => $request->spotlight]);
        $newShop = ServiceCenter::find($serviceCenter->id);

        if ($update) return response([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Data updated successfully',
            'center' => new CenterResource($newShop)
        ]);



        return response([
            'status' => 'error',
            'status_code' => 400,
            'message' => 'Oops!! Something went wrong. Unable to Spotlight',
        ]);
    }

    public function changeStatusPopular(Request $request, ServiceCenter $serviceCenter)
    {
        $update = $serviceCenter->update(['popular' => $request->popular]);
        $newShop = ServiceCenter::find($serviceCenter->id);

        if ($update) return response([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Data updated successfully',
            'center' => new CenterResource($newShop)
        ]);



        return response([
            'status' => 'error',
            'status_code' => 400,
            'message' => 'Oops!! Something went wrong. Unable to Popular',
        ]);
    }

    public function changeStatusFeatured(Request $request, ServiceCenter $serviceCenter)
    {
        $update = $serviceCenter->update(['featured' => $request->featured]);
        $newShop = ServiceCenter::find($serviceCenter->id);

        if ($update) return response([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Data updated successfully',
            'center' => new CenterResource($newShop)
        ]);



        return response([
            'status' => 'error',
            'status_code' => 400,
            'message' => 'Oops!! Something went wrong. Unable to Featured',
        ]);
    }

    public function deleteDocument(Request $request, $user_document)
    {
        $user_doc = UserDocument::find($user_document)->first();
        if ($user_doc != null && $user_doc != '') {
            if (File::exists((public_path($user_doc->file)))) {
                File::delete(public_path($user_doc->file));
            }
        }
        $delete = $user_doc->delete();

        if ($delete) return response([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Document Delete Successfully',
        ]);

        return response([
            'status' => 'error',
            'status_code' => 400,
            'message' => 'Oops!! Something went wrong.',
        ]);
    }

    public function uploadDocument(Request $request)
    {
        $upload = UserDocument::find($request->id)->first();
        $document_file = "";
        if ($request->hasFile('document_file') && $request->document_file != null && $request->document_file != "") {
            $document_file = '/storage/' . $request->document_file->store('user/documents');
        }

        if ($document_file != "") {
            if ($upload->file != null && $upload->file != "") {
                if (File::exists((public_path($upload->file)))) {
                    File::delete(public_path($upload->file));
                }
            }
        } else {
            $document_file = $upload->file;
        }

        $upload->update([
            'name' => $request->document_name,
            'file' => $document_file,
        ]);

        if ($upload) return response(['status' => 'success', 'center' => $upload, 'status_code' => 200, 'message' => 'Successfully Uploaded...'], 200);
    }
    public function getServiceDetails(ServiceCenter $serviceCenter)
    {
        $center = ServiceCenter::where('id', $serviceCenter->id)->first();
        $topCustomers = User::activeCustomer()->with(['bookings' => function ($query) {
            return $query->where('status', 'completed');
        }])->whereHas('bookings', function ($query) {
            return $query->where('status', 'completed');
        })->withCount('bookings')->orderBy('bookings_count', 'desc')->take(10)->get();
        $allBookings = Booking::where('service_center_id', $center->id)->get();
        $pendBookings = Booking::pending()->where('service_center_id', $center->id)->get();
        $todaybookingCount = booking::where('service_center_id', $center->id)->whereDate("created_at", "=", Carbon::now()->toDateString())->count();
        $prosBookings = Booking::where('service_center_id', $center->id)->proccessed()->get();
        $compBookings = Booking::where('service_center_id', $center->id)->completed()->get();
        $drivers = Driver::allowed()->get();
        $customers = User::role('service_center')->get();
        $packages = Package::allowed()->get();
        $state = [
            'all_bookings' => count($allBookings),
            'pend_bookings' => count($pendBookings),
            'pros_bookings' => count($prosBookings),
            'comp_bookings' => count($compBookings),
            'drivers' => count($drivers),
            'customers' => count($customers),
            'packages' => count($packages),
            'today_booking' => $todaybookingCount,
        ];

        return response([
            'status' => 'success',
            'state' => $state,
            'this_center' => new CenterResource($center),
            'topCustomers' => $topCustomers,
            'all_bookings' => BookingResource::collection($allBookings),
            'message' => "",
        ], 200);
    }
    public function getbookingServiceDetails(Booking $booking){
        $service_detail = Booking::find($booking->id);
        if($service_detail){
            return response([
                'status_code'=>200,
                'status'=>'success',
                'service'=>new BookingResource($service_detail)
            ]);
        }
        return response([
            'status_code'=>300,
            'status'=>'warning',
            'service'=>null
        ]);
    }

    public function getProfileData()
    {
        $center = ServiceCenter::where('user_id',Auth::id())->first();
        if($center){
            return response([
                'status_code'=>200,
                'status'=>'success',
                'centers'=> new CenterResource($center)
            ]);
        }
        else{
            return dd($center);
        }
    }
}
