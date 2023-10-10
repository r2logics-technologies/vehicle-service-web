<?php

namespace App\Http\Resources\Mobile\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'category_id' => $this->id,
            'category_name' => $this->name,
            'category_icon' => $this->icon,
            'category_detail' => json_decode($this->detail) != null ? json_decode($this->detail) : null,
            'category_status' => $this->status,
            'services' => ServiceResource::collection($this->services),
        ];
    }
}
