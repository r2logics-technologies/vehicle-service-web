<?php

namespace App\Http\Resources\Mobile\Api\V1;

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
            'image' => $this->logo,
            'shop_name' => $this->name,
            'shop_mobile' => $this->mobile,
            'shop_email' => $this->email,
            'shop_owner' => $this->owner_name,
            'shop_alter_email' => $this->alter_email,
            'shop_alter_mobile' => $this->shop_alter_mobile,
            'shop_delivery_type' => $this->delivery_type,
            'shop_recommend' => $this->recommend,
            'shop_sportlight' => $this->sportlight,
            'shop_popular' => $this->popular,
            'shop_featured' => $this->featured,
            'shop_description' => $this->description,
            'shop_address_line1' => $this->address_line_1,
            'shop_address_line2' => $this->address_line_2,
            'shop_location' => $this->shop_location,
            'shop_landmark' => $this->landmark,
            'shop_city' => $this->shop_city,
            'shop_state' => $this->state,
            'shop_postcode' => $this->postcode,
            'shop_country' => $this->country,
            'shop_lat' => $this->latitude,
            'shop_long' => $this->longitude,
            'shop_activity_status' => $this->activity_status,
            'shop_change_status_time' => $this->change_activity_time,
            'shop_status' => $this->status,
            'shop_create_at' => $this->created_at,
            'shop_details' => json_decode($this->details),
            'shop_categories' => $this->categories && $this->categories != null ? CategoryResource::collection($this->categories) : null,
            'shop_account_holder' => $bank && $bank != null ? $bank->holder_name : null,
            'shop_account_number' => $bank && $bank != null ? $bank->account_number : null,
            'shop_confirm_account_number' => $bank && $bank != null ? $bank->confirm_account_number : null,
            'shop_bank_name' => $bank && $bank != null ? $bank->name : null,
            'shop_bank_branch_name' => $bank && $bank != null ? $bank->branch : null,
            'shop_upi' => $bank && $bank != null ? $bank->upi : null,
            'shop_ifsc' => $bank && $bank != null ? $bank->ifsc : null,
            'shop_documents' => $documents ? $documents : [],
        ];
    }
}
