<?php

namespace App\Http\Resources\Backend\ServiceCenter;

use App\Models\ServiceCenter;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $center = ServiceCenter::where('user_id', Auth::id())->first();
        $bookings = $this->bookings->where('service_center_id', $center->id);
        return [
            'id' => $this->id != null ? $this->id : '',
            'user_avatar' => $this->avatar != null ? $this->avatar : '',
            'user_name' => $this->name != null ? $this->name : '',
            'user_email' => $this->email != null ? $this->email : '',
            'user_mobile' => $this->mobile != null ? $this->mobile : '',
            'user_status' => $this->status != null ? $this->status : '',
            "user_created_at" => $this->created_at != null ? $this->created_at : '',
            'booking_count' => $this->bookings != null ? count($this->bookings) : 0,
            'center_booking_count'=>$bookings != null ? count($bookings) : 0, 
        ];
    }
}
