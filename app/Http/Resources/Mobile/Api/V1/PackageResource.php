<?php

namespace App\Http\Resources\Mobile\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        
        $benefitNames = "";
        if ($this->benefits != null) {
            $benefits =  json_decode($this->benefits);
            if (count($benefits) > 0) {
                foreach ($benefits as $key => $value) {
                    $benefitNames .= ($key + 1) . ". " . $value->name . '/' . $value->time . "<br>";
                    if ($key < count($benefits) - 1) {
                        $benefitNames .= "\n";
                    }
                }
            }
        }
        $now = Carbon::now();
        $endDate = $now->addMonth($this->duration);
      	$allBenefits = json_decode($this->benefits);
		$packageLimit = collect($allBenefits)->max('time');
        return [
            'package_id' => $this->id,
            'package_name' => $this->name,
            'package_type' => $this->package_type,
            'package_price' => $this->price,
            'package_duration' => $this->duration,
            'package_status' => $this->status,
            'package_features_name' => $benefitNames,
            'package_benefits' => json_decode($this->benefits) != null ? PackageBenefitResource::collection(json_decode($this->benefits)) : null,
            'package_end_date' => date('d-m-Y', strtotime($endDate)),
          	'package_limit' => (int) $packageLimit,
        ];
    }
}
