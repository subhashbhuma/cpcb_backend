<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicCircularResource extends JsonResource
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
            'published_date' => $this->published_date ? $this->published_date->format('Y-m-d') : null,
            'division'       => $this->division ? $this->division->title : null,
            'division_hi'    => $this->division ? $this->division->title_hi : null,
            'file_path_en' => $this->file_name ? $this->file_path_en : null,
            'file_path_hi' => $this->file_name_hi ? $this->file_path_hi : $this->file_path_en,
            'category_name'  => $this->circularCategory ? $this->circularCategory->name : null,
            'category_name_hi' => $this->circularCategory ? $this->circularCategory->name_hi : null,
        ];
    }
}
