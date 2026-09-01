<?php

namespace App\DTO;

class DirectionSubjectDto
{
    public function __construct(
        public readonly string $title,
        public readonly string $title_hi,
        public readonly ?int $direction_act_type_id,
        public readonly int $is_approved,
        public readonly int $is_published,
        public readonly ?string $remarks,
        public readonly ?string $publish_remark = null,
        public readonly ?int $created_by = null,
        public readonly ?string $created_at = null,
        public readonly ?int $updated_by = null,
        public readonly ?string $updated_at = null,
    ) {}

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'title_hi' => $this->title_hi,
            'direction_act_type_id' => $this->direction_act_type_id,
            'is_approved' => $this->is_approved,
            'is_published' => $this->is_published,
            'remarks' => $this->remarks,
            'publish_remark' => $this->publish_remark,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ];
    }
}
