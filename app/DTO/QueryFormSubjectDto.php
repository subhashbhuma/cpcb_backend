<?php

namespace App\DTO;

class QueryFormSubjectDto
{
    public function __construct(
        public string $title,
        public ?string $title_hi,
        public ?int $division_id = null,
        public ?string $name = null,
        public ?string $name_hi = null,
        public ?string $email_id = null,
        public int $is_approved = 0,
        public int $is_published = 0,
        public ?string $remarks = null,
        public ?string $publish_remark = null,
        public ?int $created_by = null,
        public ?int $updated_by = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'title_hi' => $this->title_hi,
            'division_id' => $this->division_id,
            'name' => $this->name,
            'name_hi' => $this->name_hi,
            'email_id' => $this->email_id,
            'is_approved' => $this->is_approved,
            'is_published' => $this->is_published,
            'remarks' => $this->remarks,
            'publish_remark' => $this->publish_remark,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
