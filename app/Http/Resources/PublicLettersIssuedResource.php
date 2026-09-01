<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicLettersIssuedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'title'               => $this->title,
            'title_hi'            => $this->title_hi,
            'type'                => $this->type,
            'publish_date'        => $this->publish_date,
            'file_name'           => $this->file_name,
            'file_name_hi'        => $this->file_name_hi,
            'file_url'            => $this->file_url,
            'file_url_hi'         => $this->file_url_hi,
            'file_path_en'        => $this->file_path_en,
            'file_path_hi'        => $this->file_path_hi,
            // Format states as string if needed, or array. 
            // Direction resource flattened it.
            'states'              => $this->states->pluck('title')->implode(', '),
            'states_hi'           => $this->states->pluck('title_hi')->implode(', '),
        ];
    }
}
