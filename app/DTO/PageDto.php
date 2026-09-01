<?php

namespace App\DTO;

class PageDto
{
    public ?int $menu_id;
    public string $type;
    public string $title;
    public string $title_hi;
    public ?string $content;
    public ?string $content_hi;
    public $featured_image;
    public $is_approved;
    public $is_published;
    public $default_menu;
    public $remarks;
    public $publish_remark;
    public $created_by;
    public $updated_by;

    public function __construct(
        ?int $menu_id,
        string $type,
        string $title,
        string $title_hi,
        ?string $content = null,
        ?string $content_hi = null,
        $featured_image = null,
        $is_approved = 0,
        $is_published = 0,
        $default_menu = 0,
        $remarks = null,
        $publish_remark = null,
        $created_by,
        $updated_by = null
    ) {
        $this->menu_id = $menu_id;
        $this->type = $type;
        $this->title = $title;
        $this->title_hi = $title_hi;
        $this->content = $content;
        $this->content_hi = $content_hi;
        $this->featured_image = $featured_image;
        $this->is_approved = $is_approved;
        $this->is_published = $is_published;
        $this->default_menu = $default_menu;
        $this->remarks = $remarks;
        $this->publish_remark = $publish_remark;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }
}
