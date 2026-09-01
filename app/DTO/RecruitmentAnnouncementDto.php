<?php

namespace App\DTO;

class RecruitmentAnnouncementDto
{
    public $job_post_id;
    public $type;
    public $title;
    public $title_hi;
    public $start_date;
    public $end_date;
    public $file_name;
    public $file_name_hi;
    public $remarks;
    public $is_approved;
    public $is_published;
    public $publish_remark;
    public $created_by;
    public $created_at;
    public $updated_by;
    public $updated_at;

    public function __construct(
        $job_post_id,
        $type,
        $title,
        $title_hi = null,
        $start_date = null,
        $end_date = null,
        $file_name = null,
        $file_name_hi = null,
        $remarks = null,
        $is_approved = 0,
        $is_published = 0,
        $created_by = null,
        $created_at = null,
        $updated_by = null,
        $updated_at = null,
        $publish_remark = null
    ) {
        $this->job_post_id = $job_post_id;
        $this->type = $type;
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->file_name = $file_name;
        $this->file_name_hi = $file_name_hi;
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
            'job_post_id' => $this->job_post_id,
            'type' => $this->type,
            'title' => $this->title,
            'title_hi' => $this->title_hi,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'file_name' => $this->file_name,
            'file_name_hi' => $this->file_name_hi,
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
