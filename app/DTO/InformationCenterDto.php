<?php

namespace App\DTO;

class InformationCenterDto
{
    public function __construct(
        public string $title,
        public string $title_hi,
        public int $is_approved = 0,
        public int $is_published = 0,
        public ?string $remarks = null,
        public ?string $publish_remark = null,
        public int $created_by = 0,
        public int $updated_by = 0,
    ) {}

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
