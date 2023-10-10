<?php

namespace App\Http\Resources\Mobile\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'user_avatar' => $this->avatar != null ? $this->avatar : "",
            'user_name' => $this->name != null ? $this->name : "",
            'user_email' => $this->email != null ? $this->email : "",
            'user_mobile' => $this->mobile != null ? $this->mobile : "",
            'user_status' => $this->status != null ? $this->status : "",
            "user_created_at" => $this->created_at != null ? $this->created_at : "",
        ];
    }
}
