<?php

namespace App\DTO;

class GovernmentPortalDto
{
    public $file_name;
    public $title;
    public $title_hi;
    public $link;
    public $is_approved;
    public $is_published;
    public $remarks;
    public $publish_remark;
    public $created_by;
    public $updated_by;

    public function __construct(
        $file_name = null,
        $title,
        $title_hi,
        $link,
        $is_approved = 0,
        $is_published = 0,
        $remarks = null,
        $publish_remark = null,
        $created_by,
        $updated_by = null
    ) {
        $this->file_name = $file_name;
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->link = $link;
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
            'file_name' => $this->file_name,
            'title' => $this->title,
            'title_hi' => $this->title_hi,
            'link' => $this->link,
            'is_approved' => $this->is_approved,
            'is_published' => $this->is_published,
            'remarks' => $this->remarks,
            'publish_remark' => $this->publish_remark,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
