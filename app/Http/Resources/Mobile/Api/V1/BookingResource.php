<?php

namespace App\Http\Resources\Mobile\Api\V1;

use Carbon\Carbon;
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
        $bookingStatus = str_replace("_", " ", $this->status);
        $isAdvanceBooking = false;
        $now = Carbon::now();
        $bookedDate = $this->booked_date;
        if (!$now->greaterThanOrEqualTo($bookedDate)) {
            $isAdvanceBooking = true;
        }


        return [
            'booking_id' => $this->id,
            'booking_user' => new UserResource($this->user),
            'booking_address' => $this->user_address == null ? null : new UserAddressResource($this->user_address),
            'booking_package' => $this->package == null ? null : new PackageResource($this->package),
            'booking_center' => $this->center == null ? null : new CenterResource($this->center),
            'booking_driver' => $this->driver  == null ? null : new UserResource($this->driver),
            'booking_active_package' => $this->activePackage  == null ? null : new ActivePackageResource($this->activePackage),
            'booking_number' => $this->booking_number,
            'booking_date' => Carbon::parse($this->booking_date)->format('d-m-Y'),
            'booked_date' => Carbon::parse($this->booked_date)->format('d-m-Y'),
            'booked_time' => $this->booked_time,
            'booking_veh_name' => $this->vehicle_name,
            'booking_veh_num' => $this->vehicle_number,
            'booking_veh_type' => $this->vehicle_type,
            'booking_instructions' => $this->instructions,
            'booking_accepted_at' => $this->accepted_at == null ? null : Carbon::parse($this->accepted_at)->format('d-m-Y, h:i A'),
            'booking_rejected_at' => $this->rejected_at == null ? null : Carbon::parse($this->rejected_at)->format('d-m-Y, h:i A'),
            'booking_cancelled_at' => $this->cancelled_at == null ? null : Carbon::parse($this->cancelled_at)->format('d-m-Y, h:i A'),
            'booking_picked_at' => $this->picked_at == null ? null : Carbon::parse($this->picked_at)->format('d-m-Y, h:i A'),
            'booking_dropped_at_vendor_at' => $this->dropped_at_vendor_at == null ? null : Carbon::parse($this->dropped_at_vendor_at)->format('d-m-Y, h:i A'),
            'booking_proccessed_at' => $this->proccessed_at == null ? null :  Carbon::parse($this->proccessed_at)->format('d-m-Y, h:i A'),
            'booking_service_completed_at' => $this->service_completed_at == null ? null : Carbon::parse($this->service_completed_at)->format('d-m-Y, h:i A'),
            'booking_picked_at_vendor_at' => $this->picked_at_vendor_at == null ? null : Carbon::parse($this->picked_at_vendor_at)->format('d-m-Y, h:i A'),
            'booking_completed_at' => $this->completed_at == null ? null : Carbon::parse($this->completed_at)->format('d-m-Y, h:i A'),
            'booking_payment_method' => $this->payment_method,
            'booking_payment_detail' => $this->payment_detail,
            'booking_payment_status' => $this->payment_status,
            'booking_payable_amount' => $this->payable_amount,
            'booking_reward_amount' => $this->reward_amount,
            'booking_review' => $this->review,
            'booking_rating' => $this->rating,
            'booking_url' => $this->url,
            'booking_is_request_vehicle' => $this->is_request_vehicle != 0 ? true : false,
            'booking_status' => $this->status,
            'booking_detail' => json_decode($this->detail),
            'status' => ucwords($bookingStatus),
            'is_advance_booking' => $isAdvanceBooking,
        ];
    }
}
