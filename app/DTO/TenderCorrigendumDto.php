<?php

namespace App\DTO;

class TenderCorrigendumDto
{
    public $tender_id;
    public $file_name;
    public $file_name_hi;
    public ?string $title;
    public ?string $title_hi;
    public ?string $description;
    public ?string $description_hi;
    public $created_by;
    public $updated_by;

    public function __construct(
        $tender_id,
        $file_name,
        $file_name_hi,
        ?string $title = null,
        ?string $title_hi = null,
        ?string $description = null,
        ?string $description_hi = null,
        $created_by,
        $updated_by = null
    ) {
        $this->tender_id = $tender_id;
        $this->file_name = $file_name;
        $this->file_name_hi = $file_name_hi;
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->description = $description;
        $this->description_hi = $description_hi;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }
}
