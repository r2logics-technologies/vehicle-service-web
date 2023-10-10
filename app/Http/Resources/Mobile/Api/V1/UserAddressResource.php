<?php

namespace App\Http\Resources\Mobile\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $fullAddress = $this->address_line1;
        if($this->title && $this->title != null){
            $fullAddress =  $fullAddress . ', ' . $this->title;
        }
        if($this->address_line2 && $this->address_line2 != null){
            $fullAddress =  $fullAddress . ', ' . $this->address_line2;
        }
        if($this->locality && $this->locality != null){
            $fullAddress =  $fullAddress . ', ' . $this->locality;
        }
        if($this->landmark && $this->landmark != null){
            $fullAddress =  $fullAddress . ', ' . $this->landmark;
        }
        if($this->city && $this->city != null){
            $fullAddress =  $fullAddress . ', ' . $this->city;
        }
        if($this->pincode && $this->pincode != null){
            $fullAddress =  $fullAddress . ', ' . $this->pincode;
        }
        if($this->state && $this->state != null){
            $fullAddress =  $fullAddress . ', ' . $this->state;
        }
        if($this->country && $this->country != null){
            $fullAddress =  $fullAddress . ', ' . $this->country;
        }
        return [
            'id' => $this->id,
            'title' => $this->title,
            'name' => $this->name,
            'mobile' => $this->mobile,
            'alternate_mobile' => $this->alternate_mobile,
            'address_line1' => $this->address_line1,
            'address_line2' => $this->address_line2,
            'locality' => $this->locality,
            'landmark' => $this->landmark,
            'pincode' => $this->pincode,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'details' => $this->details,
            'status' => $this->status,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'full_address' => $fullAddress,
        ];
    }
}
