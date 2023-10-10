<?php

namespace App\Http\Resources\Mobile\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
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
            'vehicle_id' => $this->id,
            'category_id' => $this->category_id,
            'vehicle_name' => $this->name,
            'vehicle_ragistration_nummber' => $this->ragistration_nummber,
            'vehicle_menufacturer' => $this->menufacturer,
            'vehicle_model' => $this->model,
            'vehicle_chasis_number' => $this->chasis_number,
            'vehicle_owner_name' => $this->owner_name,
            'vehicle_is_insured' => $this->is_insured,
            'vehicle_status' => $this->status,
        ];
    }
}
