<?php

namespace App\DTO;

class AgraAirQualityDto
{
    public function __construct(
        public int $quality_zone_id,
        public ?string $title,
        public ?string $title_hi,
        public mixed $file_name,
        public mixed $file_name_hi,
        public string $for_date,
        public int $is_approved,
        public int $is_published,
        public ?string $remarks,
        public ?string $publish_remark,
        public ?int $created_by,
        public int $updated_by
    ) {
    }

    public function toArray(): array
    {
        return [
            'quality_zone_id' => $this->quality_zone_id,
            'title' => $this->title,
            'title_hi' => $this->title_hi,
            'file_name' => $this->file_name,
            'file_name_hi' => $this->file_name_hi,
            'for_date' => $this->for_date,
            'is_approved' => $this->is_approved,
            'is_published' => $this->is_published,
            'remarks' => $this->remarks,
            'publish_remark' => $this->publish_remark,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
