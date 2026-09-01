<?php

namespace App\DTO;

class QualityZoneDto
{
    public function __construct(
        public string $title,
        public ?string $title_hi,
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
            'title' => $this->title,
            'title_hi' => $this->title_hi,
            'is_approved' => $this->is_approved,
            'is_published' => $this->is_published,
            'remarks' => $this->remarks,
            'publish_remark' => $this->publish_remark,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
