<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicCommentReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'title_hi' => $this->title_hi,
            'published_date' => $this->published_date,
            'file_path_en' => $this->file_name ? $this->file_path_en : null,
            'file_path_hi' => $this->file_name_hi ? $this->file_path_hi : $this->file_path_en,
            'description' => $this->description,
            'description_hi' => $this->description_hi,
            'emails' => $this->emails,
        ];
    }
}
