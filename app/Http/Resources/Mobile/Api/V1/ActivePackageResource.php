<?php

namespace App\Http\Resources\Mobile\Api\V1;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivePackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $benifits = array();
        if (json_decode($this->available_benefits) != null) {
            $activeBenifits = json_decode($this->available_benefits);
            foreach ($activeBenifits as $benefit) {
                array_push($benifits, json_decode($benefit));
            }
        }
        return [
            'active_pack_id' => (int) $this->package_id,
            'active_vehicle_type' => $this->vehicle_type,
            'active_available_benefits' => count($benifits) > 0 ? PackageBenefitResource::collection($benifits) : null,
            'active_pack_description' => $this->description,
            'active_start_date' => date('d-m-Y', strtotime($this->start_date)),
            'active_end_date' => date('d-m-Y', strtotime($this->end_date)),
            'active_status' => $this->status,
          	'package_limit' => (int) $this->max_benifit,
        ];
    }
}
