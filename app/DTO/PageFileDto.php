<?php

namespace App\DTO;

class PageFileDto
{
    public $page_id;
    public $file_name;
    public $file_name_hi;
    public ?string $title;
    public ?string $title_hi;
    public $created_by;
    public $updated_by;
    public $upload_date;
    public ?int $order_number;

    public function __construct(
        $page_id,
        $file_name,
        $file_name_hi,
        ?string $title = null,
        ?string $title_hi = null,
        $created_by,
        $updated_by = null,
        $upload_date = null,
        ?int $order_number = 0
    ) {
        $this->page_id = $page_id;
        $this->file_name = $file_name;
        $this->file_name_hi = $file_name_hi;
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
        $this->upload_date = $upload_date;
        $this->order_number = $order_number ?? 0;
    }
}
