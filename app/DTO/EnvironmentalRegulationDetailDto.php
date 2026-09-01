<?php

namespace App\DTO;

class EnvironmentalRegulationDetailDto
{
    public $environmental_regulation_id;
    public $parent_id;
    public $order;
    public $type;
    public $url;
    public $file_name;
    public $file_name_hi;
    public $title;
    public $title_hi;
    public $is_approved;
    public $is_published;
    public $remarks;
    public $publish_remark;
    public $created_by;
    public $updated_by;

    public function __construct(
        $title,
        $title_hi,
        $created_by,
        $environmental_regulation_id = null,
        $parent_id = null,
        $order = 0,
        $type = 'URL',
        $url = null,
        $file_name = null,
        $file_name_hi = null,
        $is_approved = 0,
        $is_published = 0,
        $remarks = null,
        $publish_remark = null,
        $updated_by = null
    ) {
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->created_by = $created_by;
        $this->environmental_regulation_id = $environmental_regulation_id;
        $this->parent_id = $parent_id;
        $this->order = $order;
        $this->type = $type;
        $this->url = $url;
        $this->file_name = $file_name;
        $this->file_name_hi = $file_name_hi;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->remarks = $remarks;
        $this->publish_remark = $publish_remark;
        $this->updated_by = $updated_by;
    }
}
