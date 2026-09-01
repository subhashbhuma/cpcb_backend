<?php

namespace App\DTO;

class AdditionalLogoDto
{
    public $link;
    public $title;
    public $title_hi;
    public $file_name;
    public $is_approved;
    public $is_published;
    public $remarks;
    public $created_by;
    public $updated_by;

    public function __construct(
        $link = null,
        $title = null,
        $title_hi = null,
        $file_name = null,
        $is_approved = 0,
        $is_published = 0,
        $remarks = null,
        $created_by,
        $updated_by = null
    ) {
        $this->link = $link;
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->file_name = $file_name;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->remarks = $remarks;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }
}
