<?php

namespace App\Http\Resources\Backend\Admin;

use App\Models\BankDetail;
use App\Models\UserDocument;
use Illuminate\Http\Resources\Json\JsonResource;

class CenterResource extends JsonResource
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
        $documents = UserDocument::where('user_id', $this->user_id)->get();
        return [
            'shop_id' => $this->id,
            'image' => $this->logo != null ? $this->logo : '',
            'shop_name' => $this->name != null ? $this->name : '',
            'shop_mobile' => $this->mobile != null ? $this->mobile : '',
            'shop_email' => $this->email != null ? $this->email : '',
            'shop_owner' => $this->owner_name != null ? $this->owner_name : '',
            'shop_alter_email' => $this->alter_email != null ? $this->alter_email : '',
            'shop_alter_mobile' => $this->alter_mobile != null ? $this->alter_mobile : '',
            'shop_delivery_type' => $this->delivery_type != null ? $this->delivery_type : '',
            'shop_recommend' => $this->recommend != null ? $this->recommend : '',
            'shop_spotlight' => $this->sportlight != null ? $this->sportlight : '',
            'shop_popular' => $this->popular != null ? $this->popular : '',
            'shop_featured' => $this->featured != null ? $this->featured : '',
            'shop_description' => $this->description != null ? $this->description : '',
            'shop_address_line1' => $this->address_line_1 != null ? $this->address_line_1 : '',
            'shop_address_line2' => $this->address_line_2 != null ? $this->address_line_2 : '',
            'shop_location' => $this->location != null ? $this->location : '',
            'shop_landmark' => $this->landmark != null ? $this->landmark : '',
            'shop_city' => $this->city != null ? $this->city : '',
            'shop_state' => $this->state != null ? $this->state : '',
            'shop_postcode' => $this->postcode != null ? $this->postcode : '',
            'shop_country' => $this->country != null ? $this->country : '',
            'shop_lat' => $this->latitude != null ? $this->latitude : '',
            'shop_long' => $this->longitude != null ? $this->longitude : '',
            'shop_activity_status' => $this->activity_status != null ? $this->activity_status : '',
            'shop_change_status_time' => $this->change_activity_time != null ? $this->change_activity_time : '',
            'shop_status' => $this->status != null ? $this->status : '',
            'shop_create_at' => $this->created_at != null ? $this->created_at : '',
            'shop_details' => json_decode($this->details),
            'shop_categories' => $this->categories && $this->categories != null ? CategoryResource::collection($this->categories) : null,
            'shop_account_holder' => $bank && $bank != null ? ($bank->holder_name != null ? $bank->holder_name : '') : "",
            'shop_account_number' => $bank && $bank != null ? ($bank->account_number != null ? $bank->account_number : '') : '',
            'shop_confirm_account_number' => $bank && $bank != null ? ($bank->confirm_account_number != null ? $bank->confirm_account_number : '') : '',
            'shop_bank_name' => $bank && $bank != null ? ($bank->name != null ? $bank->name : '') : '',
            'shop_bank_branch_name' => $bank && $bank != null ? ($bank->branch != null ? $bank->branch : '') : '',
            'shop_upi' => $bank && $bank != null ? ($bank->upi != null ? $bank->upi : '') : '',
            'shop_ifsc' => $bank && $bank != null ? ($bank->ifsc != null ? $bank->ifsc : '') : '',
            'shop_documents' => $documents,
            
            // for notification
            'user_id' => $this->user_id,
            'name' => $this->name != null ? $this->name : '',
        ];
    }
}
