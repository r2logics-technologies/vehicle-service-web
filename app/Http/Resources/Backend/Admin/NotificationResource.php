<?php

namespace App\Http\Resources\Backend\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'notification_id' => $this->id,
            'notification_title' => $this->title,
            'notfication_description' => $this->description != null ? $this->description : "",
            'notification_read_by' => $this->read_by,
            'notification_status' => $this->status,
            'notification_created_at' => date_format(date_create($this->created_at), 'Y-m-d H:i'),
        ];
    }
}
