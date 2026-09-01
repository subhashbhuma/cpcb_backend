<?php

namespace App\DTO;

class LabsCategoryDto
{

    public $title;
    public $title_hi;
    public $slogan;
    public $slogan_hi;
    public $description;
    public $description_hi;
    public $featured_image;
    public $permission_group;
    public $is_approved;
    public $is_published;
    public $remarks;
    public $created_by;
    public $updated_by;

    public function __construct(
        $title,
        $title_hi,
        $slogan = null,
        $slogan_hi = null,
        $description = null,
        $description_hi = null,
        $featured_image = null,
        $permission_group = "",
        $is_approved = 0,
        $is_published = 0,
        $remarks = null,
        $created_by,
        $updated_by = null
    ) {
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->slogan = $slogan;
        $this->slogan_hi = $slogan_hi;
        $this->description = $description;
        $this->description_hi = $description_hi;
        $this->featured_image = $featured_image;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->permission_group = $permission_group;
        $this->remarks = $remarks;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }
}