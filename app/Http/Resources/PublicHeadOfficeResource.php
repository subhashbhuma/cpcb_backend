<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicHeadOfficeResource extends JsonResource
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
            'title'          => $this->title,
            'title_hi'       => $this->title_hi,
            'email' => $this->email,
            'ext_number' => $this->ext_number,
            'description' => $this->description,
            'description_hi' => $this->description_hi,
            'division'=>$this?->division?->title,
            'division_hi'=>$this?->division?->title_hi,
        ];
    }
}
