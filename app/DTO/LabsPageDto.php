<?php

namespace App\DTO;

class LabsPageDto
{
    public $title;
    public $title_hi;
    public $category_id;
    public $featured_image;
    public $public_comments;
    public $public_comments_hi;
    public $public_comments_url;
    public $content;
    public $content_hi;
    public $is_approved;
    public $is_published;
    public $remarks;
    public $created_by;
    public $updated_by;

    public function __construct(
        $title,
        $title_hi,
        $category_id = null,
        $featured_image = null,
        $public_comments = null,
        $public_comments_hi = null,
        $public_comments_url = null,
        $content = null,
        $content_hi = null,
        $is_approved = 0,
        $is_published = 0,
        $remarks = null,
        $created_by,
        $updated_by = null
    ) {
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->category_id = $category_id;
        $this->featured_image = $featured_image;
        $this->public_comments = $public_comments;
        $this->public_comments_hi = $public_comments_hi;
        $this->public_comments_url = $public_comments_url;
        $this->content = $content;
        $this->content_hi = $content_hi;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->remarks = $remarks;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }
}