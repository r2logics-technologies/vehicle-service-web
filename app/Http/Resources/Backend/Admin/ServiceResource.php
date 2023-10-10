<?php

namespace App\Http\Resources\Backend\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'service_id' => $this->id,
            'service_icon' => $this->icon != null ? $this->icon : '',
            'service_category_id' => $this->category->id != null ? $this->category->id : '',
            'service_category_name' => $this->category->name != null ? $this->category->name : '',
            'service_name' => $this->name != null ? $this->name : '',
            'service_price' => $this->price != null ? $this->price : '',
            'service_status' => $this->status != null ? $this->status : '',
        ];
    }
}
