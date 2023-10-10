<?php

namespace App\Http\Resources\Backend\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class HelpDeskResource extends JsonResource
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
            'help_desk_id' => $this->id,
            'help_desk_type' => $this->contect_type,
            'help_desk_contect' => $this->contect_type == 'mobile' ? $this->mobile : $this->email,
            'help_desk_status' => $this->status,
        ];
    }
}
