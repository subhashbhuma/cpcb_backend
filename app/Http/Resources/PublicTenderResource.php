<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicTenderResource extends JsonResource
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
            'division'=>$this->division?->title,
            'division_hi'=>$this->division?->title_hi,
            'publish_date' => $this->publish_date,
            'updated_at'   => $this->updated_at,
            'issuing_authority' => $this->issuing_authority,
            'issuing_authority_hi' => $this->issuing_authority_hi,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'file_path_en' => $this->file_path_en,
            'file_path_hi' => $this->file_path_hi,
            'corrigendumns' => PublicCorrigendumResource::collection($this->whenLoaded('corrigendumns')),
        ];
    }
}
