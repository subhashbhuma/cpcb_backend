<?php

namespace App\DTO;

class EprPortalDto
{
    public $title;
    public $title_hi;
    public $link;
    public $is_approved;
    public $is_published;
    public $is_live;
    public $remarks;
    public $publish_remark;
    public $created_by;
    public $updated_by;

    public function __construct(
        $title,
        $title_hi,
        $link,
        $is_approved = 0,
        $is_published = 0,
        $is_live = 0,
        $remarks = null,
        $publish_remark = null,
        $created_by,
        $updated_by = null
    ) {
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->link = $link;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->is_live = $is_live;
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
            'link' => $this->link,
            'is_approved' => $this->is_approved,
            'is_published' => $this->is_published,
            'is_live' => $this->is_live,
            'remarks' => $this->remarks,
            'publish_remark' => $this->publish_remark,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
