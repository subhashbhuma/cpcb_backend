<?php

namespace App\DTO;

class TenderDto
{

    public ?int $division_id;
    public $title;
    public $title_hi;
    public ?string $issuing_authority;
    public ?string $issuing_authority_hi;
    public $publish_date;
    public $start_date;
    public $end_date;
    public $file_name;
    public $file_name_hi;
    public $is_approved;
    public $is_published;
    public $remarks;
    public $publish_remark;
    public $created_by;
    public $updated_by;

    public function __construct(
        ?int $division_id = null,
        $title = null,
        $title_hi = null,
        ?string $issuing_authority = null,
        ?string $issuing_authority_hi = null,
        $publish_date = null,
        $start_date = null,
        $end_date = null,
        $file_name = null,
        $file_name_hi = null,
        $is_approved = 0,
        $is_published = 0,
        $remarks = null,
        $publish_remark = null,
        $created_by = null,
        $updated_by = null
    ) {
        $this->division_id = $division_id;
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->issuing_authority = $issuing_authority;
        $this->issuing_authority_hi = $issuing_authority_hi;
        $this->publish_date = $publish_date;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->file_name = $file_name;
        $this->file_name_hi = $file_name_hi;
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
            'division_id' => $this->division_id,
            'title' => $this->title,
            'title_hi' => $this->title_hi,
            'issuing_authority' => $this->issuing_authority,
            'issuing_authority_hi' => $this->issuing_authority_hi,
            'publish_date' => $this->publish_date,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'file_name' => $this->file_name,
            'file_name_hi' => $this->file_name_hi,
            'is_approved' => $this->is_approved,
            'is_published' => $this->is_published,
            'remarks' => $this->remarks,
            'publish_remark' => $this->publish_remark,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
