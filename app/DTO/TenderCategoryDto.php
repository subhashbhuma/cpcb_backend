<?php

namespace App\DTO;

use Illuminate\Database\Eloquent\Model;

class TenderCategoryDto
{
    public $title;
    public $title_hi;
    public $is_approved;
    public $is_published;
    public $remarks;
    public $publish_remark;
    public $created_by;
    public $created_at;
    public $updated_by;
    public $updated_at;

    public function __construct(
        $title,
        $title_hi,
        $is_approved = 0,
        $is_published = 0,
        $remarks = null,
        $publish_remark = null,
        $created_by,
        $created_at,
        $updated_by = null,
        $updated_at = null
    ) {
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->remarks = $remarks;
        $this->publish_remark = $publish_remark;
        $this->created_by = $created_by;
        $this->created_at = $created_at;
        $this->updated_by = $updated_by;
        $this->updated_at = $updated_at;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'title_hi' => $this->title_hi,
            'is_approved' => $this->is_approved,
            'is_published' => $this->is_published,
            'remarks' => $this->remarks,
            'publish_remark' => $this->publish_remark,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ];
    }
}
