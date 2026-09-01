<?php

namespace App\DTO;

class PhotoGalleryFileDto
{
    public $photo_gallery_id;
    public $image_name;
    public $title;
    public $title_hi;
    public $created_by;
    public $updated_by;

    public function __construct(
        $photo_gallery_id,
        $image_name,
        $title = null,
        $title_hi = null,
        $created_by,
        $updated_by = null
    ) {
        $this->photo_gallery_id = $photo_gallery_id;
        $this->image_name = $image_name;
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }
}
