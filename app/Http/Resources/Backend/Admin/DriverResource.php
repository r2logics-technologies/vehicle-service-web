<?php

namespace App\Http\Resources\Backend\Admin;

use App\Models\BankDetail;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $bank = BankDetail::where('user_id', $this->user_id)->first();
        return [
            'driver_id' => $this->id,
            'driver_avatar' => $this->avatar != null ? $this->avatar : '',
            'driver_name' => $this->name != null ? $this->name : '',
            'driver_email' => $this->email != null ? $this->email : '',
            'driver_status' => $this->status != null ? $this->status : '',
            'driver_mobile' => $this->mobile != null ? $this->mobile : '',
            'driver_password' => $this->password != null ? $this->password : '',
            'driver_confirm_password' => $this->confirm_password != null ? $this->confirm_password : '',
            'driver_license_front' => $this->license_front != null ? $this->license_front : '',
            'driver_license_back' => $this->license_back != null ? $this->license_back : '',
            'driver_aadhar_front' => $this->aadhar_front != null ? $this->aadhar_front : '',
            'driver_aadhar_back' => $this->aadhar_back != null ? $this->aadhar_back : '',
            'driver_address_line1' => $this->address_line_1 != null ? $this->address_line_1 : '',
            'driver_address_line2' => $this->address_line_2 != null ? $this->address_line_2 : '',
            'driver_location' => $this->location != null ? $this->location : '',
            'driver_landmark' => $this->landmark != null ? $this->landmark : '',
            'driver_city' => $this->city != null ? $this->city : '',
            'driver_state' => $this->state != null ? $this->state : '',
            'driver_postcode' => $this->postcode != null ? $this->postcode : '',
            'driver_country' => $this->country != null ? $this->country : '',
            'driver_lat' => $this->latitude != null ? $this->latitude : '',
            'driver_long' => $this->longitude != null ? $this->longitude : '',
            'driver_account_holder' =>  $bank && $bank != null ? ($bank->holder_name != null ? $bank->holder_name : '') : "",
            'driver_account_number' =>  $bank && $bank != null ? ($bank->account_number != null ? $bank->account_number : '') : '',
            'driver_confirm_account_number' =>  $bank && $bank != null ? ($bank->confirm_account_number != null ? $bank->confirm_account_number : '') : '',
            'driver_bank_name' =>  $bank && $bank != null ? ($bank->name != null ? $bank->name : '') : '',
            'driver_bank_branch_name' =>  $bank && $bank != null ? ($bank->branch != null ? $bank->branch : '') : '',
            'driver_upi' =>  $bank && $bank != null ? ($bank->upi != null ? $bank->upi : '') : '',
            'driver_ifsc' =>  $bank && $bank != null ? ($bank->ifsc != null ? $bank->ifsc : '') : '',
            // for notification
            'user_id' => $this->user_id,
            'name' => $this->name != null ? $this->name : '',
        ];
    }
}
