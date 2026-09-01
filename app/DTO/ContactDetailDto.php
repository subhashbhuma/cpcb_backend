<?php

namespace App\DTO;

class ContactDetailDto
{
    public $title;
    public $title_hi;
    public $department;
    public $department_hi;
    public $address;
    public $address_hi;
    public $phone_numbers;
    public $email_ids;
    public $profile_image;
    public $myorder;
    public $is_approved;
    public $is_published;
    public $remarks;
    public $publish_remark;
    public $created_by;
    public $updated_by;

    public function __construct(
        $title,
        $title_hi,
        $department,
        $department_hi,
        $address,
        $address_hi,
        $phone_numbers = null,
        $email_ids = null,
        $profile_image = null,
        $myorder = 0,
        $is_approved = 0,
        $is_published = 0,
        $remarks = null,
        $publish_remark = null,
        $created_by,
        $updated_by = null
    ) {
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->department = $department;
        $this->department_hi = $department_hi;
        $this->address = $address;
        $this->address_hi = $address_hi;
        $this->phone_numbers = $phone_numbers;
        $this->email_ids = $email_ids;
        $this->profile_image = $profile_image;
        $this->myorder = $myorder;
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
            'department' => $this->department,
            'department_hi' => $this->department_hi,
            'address' => $this->address,
            'address_hi' => $this->address_hi,
            'phone_numbers' => $this->phone_numbers,
            'email_ids' => $this->email_ids,
            'profile_image' => $this->profile_image,
            'myorder' => $this->myorder,
            'is_approved' => $this->is_approved,
            'is_published' => $this->is_published,
            'remarks' => $this->remarks,
            'publish_remark' => $this->publish_remark,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
