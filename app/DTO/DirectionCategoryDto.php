<?php

namespace App\DTO;

class DirectionCategoryDto
{
    public function __construct(
        public ?string $title,
        public ?string $title_hi,
        public ?int $direction_act_type_id,
        public ?int $is_approved,
        public ?int $is_published,
        public ?string $remarks,
        public ?string $publish_remark = null,
        public ?int $created_by,
        public $created_at,
        public ?int $updated_by,
        public $updated_at
    ) {
    }

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
