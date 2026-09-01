<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InformationCenterDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'information_center_id' => $this->information_center_id,
            'type' => $this->type,
            'url' => $this->url,
            'title' => $this->title,
            'title_hi' => $this->title_hi,
            'content' => $this->content,
            'content_hi' => $this->content_hi,
            'file_name' => $this->file_name,
            'file_name_hi' => $this->file_name_hi,
            'featured_image' => $this->featured_image,
            'file_path' => $this->file_path,
            'file_path_hi' => $this->file_path_hi,
            'featured_image_path' => $this->featured_image_path,
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
        ];
    }
}
