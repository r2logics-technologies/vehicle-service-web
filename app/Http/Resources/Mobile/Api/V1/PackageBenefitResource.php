<?php

namespace App\Http\Resources\Mobile\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageBenefitResource extends JsonResource
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
            'name' => $this->name,
            'limit' => (int)$this->time,
        ];
    }
}
