<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TechnicalReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'subject' => $this->subjectArea?->title,
            'subject_hi' => $this->subjectArea?->title_hi,
            'division' => $this->division?->title,
            'division_hi' => $this->division?->title_hi,
            'title'        => $this->title,
            'title_hi'     => $this->title_hi,
            'release_date' => $this->release_date,
            'updated_at'   => $this->updated_at,
            'file_path_en' => $this->file_path_en,
            'file_path_hi' => $this->file_path_hi
        ];
    }
}
