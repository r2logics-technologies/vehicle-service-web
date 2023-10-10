<?php

namespace App\Http\Resources\Mobile\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
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
            'logo' => $this->logo != null ? $this->logo : '/assets/img/avatar.png',
            'app_name' => $this->app_name,
            'owner_name' => $this->name,
            'owner_email' => $this->email,
            'owner_mobile' => $this->mobile,
            'current_version' => $this->current_version,
        ];
    }
}
