<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicWhoIsWhoHomePageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'          => $this->name,
            'name_hi'       => $this->name_hi,
            'order' => $this->order,
            'division'       => $this->division ? $this->division->title : null,
            'division_hi'    => $this->division ? $this->division->title_hi : null,
            'designation'       => $this->designation,
            'designation_hi'    => $this->designation_hi,
            'mobile_number'       => $this->mobile_number,
            'email_id'    => $this->email_id,
            'address'       => $this->address,
            'address_hi'    => $this->address_hi,
            'image_full_path' =>$this->file_path,
        ];
    }
}
