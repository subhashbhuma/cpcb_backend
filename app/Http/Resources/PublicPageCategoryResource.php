<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicPageCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'title'                => $this->title,
            'title_hi'             => $this->title_hi,
            'file_path_en'         => $this->file_path_en,
            'file_path_hi'         => $this->file_path_hi,
        ];
    }
}
