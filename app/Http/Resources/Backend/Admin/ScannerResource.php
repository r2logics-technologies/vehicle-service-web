<?php

namespace App\Http\Resources\Backend\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ScannerResource extends JsonResource
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
            'scanner_id' => $this->id,
            'scanner_image' => $this->image,
            'scanner_detail' => $this->detail,
            'scanner_status' => $this->status,
        ];
    }
}
