<?php

namespace App\DTO;

class HeadOfficeDto
{
    public $division_id;
    public $title;
    public $title_hi;
    public $email;
    public $ext_number;
    public $description;
    public $description_hi;
    public $order;
    public $is_approved;
    public $is_published;
    public $remarks;
    public $publish_remark;
    public $created_by;
    public $updated_by;

    public function __construct(
        $division_id,
        $title,
        $title_hi,
        $email,
        $ext_number,
        $description,
        $description_hi,
        $order,
        $is_approved,
        $is_published,
        $remarks,
        $publish_remark,
        $created_by,
        $updated_by
    ) {
        $this->division_id = $division_id;
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->email = $email;
        $this->ext_number = $ext_number;
        $this->description = $description;
        $this->description_hi = $description_hi;
        $this->order = $order;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->remarks = $remarks;
        $this->publish_remark = $publish_remark;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }
}
