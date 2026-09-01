<?php

namespace App\DTO;

class WhoIsWhoDto
{
    public $order;
    public $name;
    public $name_hi;
    public $designation;
    public $designation_hi;
    public $mobile_number;
    public $email_id;
    public $division_id;
    public $image;
    public $address;
    public $address_hi;
    public $show_on_homepage;
    public $hide_on_who_is_who;
    public $is_approved;
    public $is_published;
    public $remarks;
    public $publish_remark;
    public $created_by;
    public $updated_by;

    public function __construct(
        $order = 0,
        $name,
        $name_hi,
        $designation = null,
        $designation_hi = null,
        $mobile_number = null,
        $email_id = null,
        $division_id = null,
        $image = null,
        $address = null,
        $address_hi = null,
        $show_on_homepage = 0,
        $hide_on_who_is_who = 0,
        $is_approved = 0,
        $is_published = 0,
        $remarks = null,
        $publish_remark = null,
        $created_by,
        $updated_by = null
    ) {
        $this->order = $order;
        $this->name = $name;
        $this->name_hi = $name_hi;
        $this->designation = $designation;
        $this->designation_hi = $designation_hi;
        $this->mobile_number = $mobile_number;
        $this->email_id = $email_id;
        $this->division_id = $division_id;
        $this->image = $image;
        $this->address = $address;
        $this->address_hi = $address_hi;
        $this->show_on_homepage = $show_on_homepage;
        $this->hide_on_who_is_who = $hide_on_who_is_who;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->remarks = $remarks;
        $this->publish_remark = $publish_remark;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }
}
