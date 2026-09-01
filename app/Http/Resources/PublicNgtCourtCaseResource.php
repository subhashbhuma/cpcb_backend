<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicNgtCourtCaseResource extends JsonResource
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
            'type'        => $this->type,
            'type_hi'     => $this->type_hi,
            'qa_number'        => $this->qa_number,
            'qa_number_hi'     => $this->qa_number_hi,
            'publish_date' => $this->publish_date,
            'updated_at'   => $this->updated_at,
            'file_path_en' => $this->file_name ? $this->file_path_en : null,
            'file_path_hi' => $this->file_name_hi ? $this->file_path_hi : $this->file_path_en,
        ];
    }
}
