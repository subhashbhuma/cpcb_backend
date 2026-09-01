<?php

namespace App\DTO;

class LatestCpcbDto
{
    public $title;
    public $title_hi;
    public $publish_date;
    public $division_id;
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
        $title_hi,
        $publish_date = null,
        $file_name = null,
        $file_name_hi = null,
        $is_approved = 0,
        $is_published = 0,
        $remarks = null,
        $publish_remark = null,
        $created_by,
        $updated_by = null,
        $division_id = null
    ) {
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->publish_date = $publish_date;
        $this->division_id = $division_id;
        $this->file_name = $file_name;
        $this->file_name_hi = $file_name_hi;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->remarks = $remarks;
        $this->publish_remark = $publish_remark;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }
}
