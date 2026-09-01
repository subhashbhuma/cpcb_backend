<?php

namespace App\DTO;

class MediaDto
{
    public $file_name;
    public $original_name;
    public $mime_type;
    public $size;
    public ?string $alt_text;
    public ?string $alt_text_hi;
    public $created_by;
    public $updated_by;

    public function __construct(
        $file_name,
        $original_name = null,
        $mime_type = null,
        $size = null,
        ?string $alt_text = null,
        ?string $alt_text_hi = null,
        $created_by,
        $updated_by = null
    ) {
        $this->file_name = $file_name;
        $this->original_name = $original_name;
        $this->mime_type = $mime_type;
        $this->size = $size;
        $this->alt_text = $alt_text;
        $this->alt_text_hi = $alt_text_hi;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }
}
