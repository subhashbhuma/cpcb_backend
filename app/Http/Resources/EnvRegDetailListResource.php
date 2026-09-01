<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnvRegDetailListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title'        => $this->title,
            'title_hi'     => $this->title_hi,
            'type'     => $this->type,
            'url'     => $this->url,
            'file_path_en' => $this->file_path_en,
            'file_path_hi' => $this->file_path_hi,
            'parent_id'     => $this->parent_id,
            'children'      => EnvRegDetailListResource::collection($this->whenLoaded('children')),
        ];
    }
}
