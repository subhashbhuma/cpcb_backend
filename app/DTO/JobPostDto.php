<?php

namespace App\DTO;

class JobPostDto
{
    public $job_id;
    public $title;
    public $title_hi;
    public $remarks;
    public $is_approved;
    public $is_published;
    public $publish_remark;
    public $created_by;
    public $created_at;
    public $updated_by;
    public $updated_at;

    public function __construct(
        $job_id,
        $title,
        $title_hi = null,
        $remarks = null,
        $is_approved = 0,
        $is_published = 0,
        $created_by = null,
        $created_at = null,
        $updated_by = null,
        $updated_at = null,
        $publish_remark = null
    ) {
        $this->job_id = $job_id;
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->remarks = $remarks;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->created_by = $created_by;
        $this->created_at = $created_at;
        $this->updated_by = $updated_by;
        $this->updated_at = $updated_at;
        $this->publish_remark = $publish_remark;
    }

    public function toArray(): array
    {
        return [
            'job_id' => $this->job_id,
            'title' => $this->title,
            'title_hi' => $this->title_hi,
            'remarks' => $this->remarks,
            'is_approved' => $this->is_approved,
            'is_published' => $this->is_published,
            'publish_remark' => $this->publish_remark,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ];
    }
}
