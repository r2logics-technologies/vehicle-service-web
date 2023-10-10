<?php

namespace App\Http\Resources\Mobile\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'booking_number' => $this->booking_number,
            'booking_user' => new UserResource($this->user),
            'booking_address' => $this->user_address == null ? null : new UserAddressResource($this->user_address),
            'booking_package' => $this->package == null ? null : new PackageResource($this->package),
            'booking_center' => $this->center == null ? null : new CenterResource($this->center),
            'booking_driver' => $this->driver  == null ? null : new UserResource($this->driver),
          	'booking_active_package' => $this->activePackage  == null ? null : new ActivePackageResource($this->activePackage),
            'booking_price' => $this->package->price,
            'booking_package_name' => $this->package->name,
            'booking_package_type' => $this->package->package_type,
            'booking_start_date' => $this->activePackage->start_date,
            'booking_end_date' => $this->activePackage->end_date,
            'booking_package_status' => $this->activePackage->status,
            'payment_status' => $this->payment_status,
            'payment_time' => $this->completed_at,
            'payment_method' => $this->payment_method,
        ];
    }
}
