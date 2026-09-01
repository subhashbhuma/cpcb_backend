<?php

namespace App\DTO;

class PublicationDto {
    public $category_id;
    public $title;
    public $title_hi;
    public $price;
    public $file_name;
    public $file_name_hi;
    public $published_date;
    public $is_approved;
    public $is_published;
    public $remarks;
    public $publish_remark;
    public $created_by;
    public $created_at;
    public $updated_by;
    public $updated_at;

    public function __construct(
        $category_id,
        $title,
        $title_hi,
        $price,
        $file_name = null,
        $file_name_hi = null,
        $published_date = null,
        $is_approved = 0,
        $is_published = 0,
        $remarks = null,
        $publish_remark = null,
        $created_by,
        $created_at,
        $updated_by = null,
        $updated_at
    ) {
        $this->category_id = $category_id;
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->price = $price;
        $this->file_name = $file_name;
        $this->file_name_hi = $file_name_hi;
        $this->published_date = $published_date;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->remarks = $remarks;
        $this->publish_remark = $publish_remark;
        $this->created_by = $created_by;
        $this->created_at = $created_at;
        $this->updated_by = $updated_by;
        $this->updated_at = $updated_at;
    }

    public function toArray(): array
    {
        return [
            'category_id' => $this->category_id,
            'title' => $this->title,
            'title_hi' => $this->title_hi,
            'price' => $this->price,
            'file_name' => $this->file_name,
            'file_name_hi' => $this->file_name_hi,
            'published_date' => $this->published_date,
            'is_approved' => $this->is_approved,
            'is_published' => $this->is_published,
            'remarks' => $this->remarks,
            'publish_remark' => $this->publish_remark,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ];
    }
}