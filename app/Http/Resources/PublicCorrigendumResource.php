<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicCorrigendumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title'       => $this->title,
            'title_hi'    => $this->title_hi,
            'file_path'   => $this->public_file_path_en,      // accessor or attribute
            'file_path_hi' => $this->public_file_path_hi,   // accessor or attribute
        ];
    }
}
