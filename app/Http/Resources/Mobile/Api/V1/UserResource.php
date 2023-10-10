<?php

namespace App\Http\Resources\Mobile\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country_code' => $this->country_code,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'avatar' => $this->avatar != null ? $this->avatar : "/assets/img/avatar.png",
            'user_type' => $this->user_type ,
            'reward_points' =>(int) $this->reward_points,
            'referral_id' => $this->referral_id,
            'reference_id' => $this->reference_id,
            'fcm_topics' => json_decode($this->fcm_topics) != null ? json_decode($this->fcm_topics) : null ,
        ];
    }
}
