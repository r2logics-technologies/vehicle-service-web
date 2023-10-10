<?php

namespace App\Http\Resources\Backend\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

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
                    $benefitNames .= ($key + 1) . ". " . $value->name .'/'.$value->time. "<br>";
                    if ($key < count($benefits) - 1) {
                        $benefitNames .= "\n";
                    }
                }
            }
        }

        return [
            'package_id' => $this->id != null ? $this->id : '',
            'package_name' => $this->name != null ? $this->name : '',
            'package_type' => $this->package_type != null ? $this->package_type : '',
            'package_price' => $this->price != null ? $this->price : '',
            'package_duration' => $this->duration != null ? $this->duration : '',
            'package_benefits' => json_decode($this->benefits) != null ? json_decode($this->benefits) : "",
            'package_features_name' => $benefitNames,
            'package_status' => $this->status != null ? $this->status : '',
        ];
    }
}
