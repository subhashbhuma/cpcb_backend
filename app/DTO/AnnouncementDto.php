<?php

namespace App\DTO;

class AnnouncementDto
{
    public $title;
    public $title_hi;
    public $file_or_link;
    public $file_name;
    public $file_name_hi;
    public $page_link;
    public $status;
    public $is_approved;
    public $is_published;
    public $remarks;
    public $publish_remark;
    public $created_by;
    public $updated_by;
    public $published_date;

    public function __construct(
        $title,
        $title_hi,
        $file_or_link = null,
        $file_name = null,
        $file_name_hi = null,
        $page_link,
        $status = 0,
        $is_approved = 0,
        $is_published = 0,
        $remarks = null,
        $publish_remark = null,
        $created_by,
        $updated_by = null,
        $published_date = null
    ) {
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->file_or_link = $file_or_link;
        $this->file_name = $file_name;
        $this->file_name_hi = $file_name_hi;
        $this->page_link = $page_link;
        $this->status = $status;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->remarks = $remarks;
        $this->publish_remark = $publish_remark;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
        $this->published_date = $published_date;
    }
}
