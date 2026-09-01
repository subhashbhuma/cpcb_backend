<?php

namespace App\DTO;

class SocialMediaDto
{
    public $type;
    public $name;
    public $url;
    public $embed_code;
    public $icon_class;
    public $is_approved;
    public $is_published;
    public $remarks;
    public $publish_remark;
    public $created_by;
    public $updated_by;

    public function __construct(
        $type,
        $name,
        $url,
        $embed_code,
        $icon_class = null,
        $is_approved = 0,
        $is_published = 0,
        $remarks = null,
        $publish_remark = null,
        $created_by,
        $updated_by = null
    ) {
        $this->type = $type;
        $this->name = $name;
        $this->url = $url;
        $this->embed_code = $embed_code;
        $this->icon_class = $icon_class;
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
            'type' => $this->type,
            'name' => $this->name,
            'url' => $this->url,
            'embed_code' => $this->embed_code,
            'icon_class' => $this->icon_class,
            'is_approved' => $this->is_approved,
            'is_published' => $this->is_published,
            'remarks' => $this->remarks,
            'publish_remark' => $this->publish_remark,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ];
    }
}
