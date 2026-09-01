<?php

namespace App\DTO;

class LettersIssuedDto
{
    public $title;
    public $title_hi;
    public $type;
    public $publish_date;
    public $file_name;
    public $file_name_hi;
    public $is_approved;
    public $is_published;
    public $remarks;
    public $publish_remark;
    public $created_by;
    public $updated_by;
    public $direction_state_id;

    public function __construct(
        $title,
        $title_hi,
        $type,
        $publish_date,
        $file_name,
        $file_name_hi,
        $is_approved = 0,
        $is_published = 0,
        $remarks = null,
        $publish_remark = null,
        $created_by = null,
        $updated_by = null,
        $direction_state_id = null
    ) {
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->type = $type;
        $this->publish_date = $publish_date;
        $this->file_name = $file_name;
        $this->file_name_hi = $file_name_hi;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->remarks = $remarks;
        $this->publish_remark = $publish_remark;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
        $this->direction_state_id = is_array($direction_state_id) ? implode(',', $direction_state_id) : $direction_state_id;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'title_hi' => $this->title_hi,
            'type' => $this->type,
            'publish_date' => $this->publish_date,
            'file_name' => $this->file_name,
            'file_name_hi' => $this->file_name_hi,
            'is_approved' => $this->is_approved,
            'is_published' => $this->is_published,
            'remarks' => $this->remarks,
            'publish_remark' => $this->publish_remark,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'direction_state_id' => $this->direction_state_id,
        ];
    }
}
