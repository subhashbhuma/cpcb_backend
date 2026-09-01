<?php

namespace App\DTO;

class VideoGalleryDto
{
    public $gallery_event_id;
    public $thumbnail_image;
    public $type;
    public $file_name;
    public $youtube_embed_code;
    public $url;
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
        $gallery_event_id = null,
        $thumbnail_image = null,
        $type,
        $file_name = null,
        $youtube_embed_code = null,
        $url = null,
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
        $this->thumbnail_image = $thumbnail_image;
        $this->type = $type;
        $this->file_name = $file_name;
        $this->youtube_embed_code = $youtube_embed_code;
        $this->url = $url;
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
            'thumbnail_image' => $this->thumbnail_image,
            'type' => $this->type,
            'file_name' => $this->file_name,
            'youtube_embed_code' => $this->youtube_embed_code,
            'url' => $this->url,
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
