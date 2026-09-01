<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicCpcbResource extends JsonResource
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
            'publish_date' => $this->publish_date,
            'division_id'  => $this->division_id,
            'division'     => $this->division ? [
                'id' => $this->division->id,
                'title' => $this->division->title,
                'title_hi' => $this->division->title_hi,
            ] : null,
            'updated_at'   => $this->updated_at,
            'publish_remark' => $this->publish_remark,
            'file_detail_en' => getFileMeta($this->file_url),
            'file_detail_hi' => getFileMeta($this->file_url_hi),
            'file_path_en' => $this->file_path_en,
            'file_path_hi' => $this->file_path_hi,
        ];
    }
}
