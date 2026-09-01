<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicStudiesReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'title'                => $this->title,
            'title_hi'             => $this->title_hi,
            'division'                => $this->division ? $this->division->title : null,
            'division_hi'             => $this->division ? $this->division->title_hi : null,
            'report_year'                => $this->report_year,
            'file_path_en'                => $this->file_path_en,
            'file_path_hi'             => $this->file_path_hi ?? $this->file_path_en,
        ];
    }
}
