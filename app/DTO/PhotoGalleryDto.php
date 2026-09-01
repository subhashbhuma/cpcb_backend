<?php

namespace App\DTO;

class PhotoGalleryDto
{
    public $gallery_event_id;
    public $featured_image;
    public $title;
    public $title_hi;
    public $description;
    public $description_hi;
    public $date;
    public $is_approved;
    public $is_published;
    public $remarks;
    public $publish_remark;
    public $created_by;
    public $updated_by;

    public function __construct(
        $gallery_event_id,
        $featured_image = null,
        $title,
        $title_hi,
        $description = null,
        $description_hi = null,
        $date = null,
        $is_approved = 0,
        $is_published = 0,
        $remarks = null,
        $publish_remark = null,
        $created_by,
        $updated_by = null
    ) {
        $this->gallery_event_id = $gallery_event_id;
        $this->featured_image = $featured_image;
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->description = $description;
        $this->description_hi = $description_hi;
        $this->date = $date;
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
            'gallery_event_id' => $this->gallery_event_id,
            'featured_image' => $this->featured_image,
            'title' => $this->title,
            'title_hi' => $this->title_hi,
            'description' => $this->description,
            'description_hi' => $this->description_hi,
            'date' => $this->date,
            'is_approved' => $this->is_approved,
            'is_published' => $this->is_published,
            'remarks' => $this->remarks,
            'publish_remark' => $this->publish_remark,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
