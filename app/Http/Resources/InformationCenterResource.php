<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InformationCenterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'title_hi' => $this->title_hi,
            'is_approved' => $this->is_approved,
            'is_approved_desc' => $this->is_approved_desc,
            'is_published' => $this->is_published,
            'is_published_desc' => $this->is_published_desc,
            'remarks' => $this->remarks,
            'created_by' => $this->created_by,
            'created_by_name' => $this->createdBy?->name,
            'updated_by' => $this->updated_by,
            'updated_by_name' => $this->updatedBy?->name,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'deleted_at' => $this->deleted_at?->toIso8601String(),
            'details' => InformationCenterDetailResource::collection($this->whenLoaded('details')),
        ];
    }
}

