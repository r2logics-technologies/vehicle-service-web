<?php

namespace App\Http\Resources\Backend\ServiceCenter;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'booking_id'=>$this->booking_number,
            'booking_date' => $this->booking_date,
            'booking_time' => $this->booking_time,
            'package_type' => $this->booking_time,
        ];
    }
}
