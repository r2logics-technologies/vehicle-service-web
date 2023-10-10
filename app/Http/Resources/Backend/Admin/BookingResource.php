<?php

namespace App\Http\Resources\Backend\Admin;

use App\Models\UserAddress;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
        $proccessed_at = Carbon::parse($this->proccessed_at);
        $completed_at = Carbon::parse($this->completed_at);
        $serviceDurationTime = $proccessed_at->diffInHours($completed_at);

        return [
            'booking_id' => $this->id != null ? $this->id : '',
            'booking_user' => new UserResource($this->user) == null ? '' : new UserResource($this->user),
            'booking_address' => $this->user_address == null ? '' : $this->user_address,
            'booking_package' => new PackageResource($this->package) == null ? '' : new PackageResource($this->package),
            'booking_center' => new CenterResource($this->center) == null ? '' : new CenterResource($this->center),
            'booking_number' => $this->booking_number != null ? $this->booking_number : '',
            'booking_date' => $this->booking_date != null ? $this->booking_date : '',
            'booked_date' => $this->booked_date != null ? $this->booked_date : '',
            'booked_time' => $this->booked_time != null ? $this->booked_time : '',
            'booking_veh_name' => $this->vehicle_name != null ? $this->vehicle_name : '',
            'booking_veh_num' => $this->vehicle_number != null ? $this->vehicle_number : '',
            'booking_veh_type' => $this->vehicle_type != null ? $this->vehicle_type : '',
            'booking_instruct' => $this->instructions != null ? $this->instructions : '',
            'booking_accepted_at' => $this->accepted_at != null ? $this->accepted_at : '',
            'booking_proccessed_at' => $this->proccessed_at != null ? $this->proccessed_at : '',
            'booking_picked_at' => $this->picked_at != null ? $this->picked_at : '',
            'booking_driver' => new DriverResource($this->driver) == null ? '' : new DriverResource($this->driver),
            'booking_completed_at' => $this->completed_at != null ? $this->completed_at : '',
            'booking_dropped' => $this->dropped_at_vendor_at != null ? $this->dropped_at_vendor_at : '',
            'booking_rejected_at' => $this->rejected_at != null ? $this->rejected_at : '',
            'booking_payment_method' => $this->payment_method != null ? $this->payment_method : '',
            'booking_payment_detail' => $this->payment_detail != null ? $this->payment_detail : '',
            'booking_payment_status' => $this->payment_status != null ? $this->payment_status : '',
            'service_duration_time' => $serviceDurationTime != null ? $serviceDurationTime : '',
            'booking_url' => $this->url != null ? $this->url : '',
            'booking_review' => $this->review != null ? $this->review : '',
            'booking_rating' => $this->rating != null ? $this->rating : '',
            'booking_status' => $this->status != null ? $this->status : '',
        ];
    }
}