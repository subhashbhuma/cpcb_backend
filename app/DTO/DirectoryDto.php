<?php

namespace App\DTO;

class DirectoryDto
{
    public $name;
    public $name_hi;
    public $designation;
    public $designation_hi;
    public $division_id;
    public $office_ph_no;
    public $mobile_no;
    public $email;
    public $image;
    public $ext_number;
    public $order_no;
    public $show_order;
    public $remarks;
    public $is_approved;
    public $is_published;
    public $publish_remark;
    public $cpcb_no;
    public $assigned_work;
    public $assigned_work_hi;
    public $created_by;
    public $updated_by;

    public function __construct(
        $name,
        $name_hi,
        $designation,
        $designation_hi,
        $division_id,
        $office_ph_no,
        $mobile_no,
        $email,
        $image = null,
        $ext_number = null,
        $order_no = 1,
        $show_order = 1,
        $remarks = null,
        $cpcb_no = null,
        $assigned_work = null,
        $assigned_work_hi = null,
        $is_approved = 0,
        $is_published = 0,
        $publish_remark = null,
        $created_by = null,
        $updated_by = null
    ) {
        $this->name = $name;
        $this->name_hi = $name_hi;
        $this->designation = $designation;
        $this->designation_hi = $designation_hi;
        $this->division_id = $division_id;
        $this->office_ph_no = $office_ph_no;
        $this->mobile_no = $mobile_no;
        $this->email = $email;
        $this->image = $image;
        $this->ext_number = $ext_number;
        $this->order_no = $order_no;
        $this->show_order = $show_order;
        $this->remarks = $remarks;
        $this->cpcb_no = $cpcb_no;
        $this->assigned_work = $assigned_work;
        $this->assigned_work_hi = $assigned_work_hi;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->publish_remark = $publish_remark;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'name_hi' => $this->name_hi,
            'designation' => $this->designation,
            'designation_hi' => $this->designation_hi,
            'division_id' => $this->division_id,
            'office_ph_no' => $this->office_ph_no,
            'mobile_no' => $this->mobile_no,
            'email' => $this->email,
            'image' => $this->image,
            'ext_number' => $this->ext_number,
            'order_no' => $this->order_no,
            'show_order' => $this->show_order,
            'remarks' => $this->remarks,
            'publish_remark' => $this->publish_remark,
            'cpcb_no' => $this->cpcb_no,
            'assigned_work' => $this->assigned_work,
            'assigned_work_hi' => $this->assigned_work_hi,
            'is_approved' => $this->is_approved,
            'is_published' => $this->is_published,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
