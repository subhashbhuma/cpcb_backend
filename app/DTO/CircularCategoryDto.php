<?php

namespace App\DTO;

class CircularCategoryDto
{
    public $name;
    public $name_hi;
    public $created_by;
    public $updated_by;

    public function __construct(
        $name,
        $name_hi,
        $created_by,
        $updated_by = null
    ) {
        $this->name = $name;
        $this->name_hi = $name_hi;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }
}
