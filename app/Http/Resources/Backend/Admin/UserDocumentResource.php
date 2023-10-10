<?php

namespace App\Http\Resources\Backend\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDocumentResource extends JsonResource
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
            'doc_user_id' => $this->user_id,
            'doc_name' => $this->name != null ? $this->name : '',
            'doc_file' => $this->file != null ? $this->file : '',
        ];
    }
}
