<?php

namespace App\DTO;

class LabsPageFilesDto
{
    public $page_id;
    public $file_name;
    public $file_name_hi;
    public $title;
    public $title_hi;
    public $date;
    public $type;
    public $description;
    public $description_hi;
    public $created_by;
    public $updated_by;

    public function __construct(
        $page_id,
        $file_name,
        $file_name_hi,
        ?string $title = null,
        ?string $title_hi = null,
        $date = null,
        $type = null,
        ?string $description = null,
        ?string $description_hi = null,
        $created_by,
        $updated_by = null
    ) {
        $this->page_id = $page_id;
        $this->file_name = $file_name;
        $this->file_name_hi = $file_name_hi;
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->date = $date;
        $this->type = $type;
        $this->description = $description;
        $this->description_hi = $description_hi;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }
}