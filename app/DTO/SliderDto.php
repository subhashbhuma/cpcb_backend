<?php

namespace App\DTO;

class SliderDto
{
    public $title;
    public $title_hi;
    public $description;
    public $description_hi;
    public $file_name;
    public $is_approved;
    public $is_published;
    public $remarks;
    public $publish_remark;
    public $link;
    public $created_by;
    public $updated_by;

    public function __construct(
        $title = null,
        $title_hi = null,
        $description = null,
        $description_hi = null,
        $file_name = null,
        $is_approved = 0,
        $is_published = 0,
        $remarks = null,
        $publish_remark = null,
        $link = null,
        $created_by,
        $updated_by = null
    ) {
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->description = $description;
        $this->description_hi = $description_hi;
        $this->file_name = $file_name;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->remarks = $remarks;
        $this->publish_remark = $publish_remark;
        $this->link = $link;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }
}
