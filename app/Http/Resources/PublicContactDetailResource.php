<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicContactDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title'        => $this->title,
            'title_hi'     => $this->title_hi,
            'department'     => $this->department,
            'department_hi'     => $this->department_hi,
            'address'     => $this->address,
            'address_hi'     => $this->address_hi,
            'phone_numbers'     => $this->phone_numbers,
            'email_ids'=>$this->email_ids,
            'profile_image_url'=> $this->profile_image_url,
            'myorder'     => $this->myorder
        ];
    }
}
