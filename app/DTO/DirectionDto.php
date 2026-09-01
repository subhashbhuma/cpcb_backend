<?php

namespace App\DTO;

class DirectionDto
{
    public ?int $direction_act_type_id;
    public ?int $direction_type_id;
    public ?int $direction_subject_id;
    public $title;
    public $title_hi;
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
    public $direction_category_id;
    public $direction_issued_to_id;

    public function __construct(
        ?int $direction_act_type_id = null,
        ?int $direction_type_id = null,
        ?int $direction_subject_id = null,
        $title,
        $title_hi,
        $publish_date = null,
        $file_name = null,
        $file_name_hi = null,
        $is_approved = 0,
        $is_published = 0,
        $remarks = null,
        $publish_remark = null,
        $created_by = null,
        $updated_by = null,
        $direction_state_id = [],
        $direction_category_id = [],
        $direction_issued_to_id = []
    ) {
        $this->direction_act_type_id = $direction_act_type_id;
        $this->direction_type_id = $direction_type_id;
        $this->direction_subject_id = $direction_subject_id;
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->publish_date = $publish_date;
        $this->file_name = $file_name;
        $this->file_name_hi = $file_name_hi;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->remarks = $remarks;
        $this->publish_remark = $publish_remark;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
        
        // Convert arrays to comma-separated strings
        $this->direction_state_id = is_array($direction_state_id) ? implode(',', $direction_state_id) : $direction_state_id;
        $this->direction_category_id = is_array($direction_category_id) ? implode(',', $direction_category_id) : $direction_category_id;
        $this->direction_issued_to_id = is_array($direction_issued_to_id) ? implode(',', $direction_issued_to_id) : $direction_issued_to_id;
    }

    public function toArray(): array
    {
        return [
            'direction_act_type_id' => $this->direction_act_type_id,
            'direction_type_id' => $this->direction_type_id,
            'direction_subject_id' => $this->direction_subject_id,
            'title' => $this->title,
            'title_hi' => $this->title_hi,
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
            'direction_category_id' => $this->direction_category_id,
            'direction_issued_to_id' => $this->direction_issued_to_id,
        ];
    }
}
