<?php

namespace App\DTO;

class CommentReportDto
{
    public $title;
    public $title_hi;
    public $emails;
    public $published_date;
    public $file_name;
    public $file_name_hi;
    public $is_approved;
    public $is_published;
    public $remarks;
    public $publish_remark;
    public $created_by;
    public $updated_by;

    public function __construct(
        $title,
        $title_hi = null,
        $emails = null,
        $published_date = null,
        $file_name = null,
        $file_name_hi = null,
        $is_approved = 0,
        $is_published = 0,
        $remarks = null,
        $publish_remark = null,
        $created_by = null,
        $updated_by = null
    ) {
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->emails = $emails;
        $this->published_date = $published_date;
        $this->file_name = $file_name;
        $this->file_name_hi = $file_name_hi;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->remarks = $remarks;
        $this->publish_remark = $publish_remark;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'title_hi' => $this->title_hi,
            'emails' => $this->emails,
            'published_date' => $this->published_date,
            'file_name' => $this->file_name,
            'file_name_hi' => $this->file_name_hi,
            'is_approved' => $this->is_approved,
            'is_published' => $this->is_published,
            'remarks' => $this->remarks,
            'publish_remark' => $this->publish_remark,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
