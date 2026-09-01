<?php

namespace App\DTO;

class SiteSettingDto
{
    public string $site_name;
    public ?string $site_name_hi;
    public ?string $seo_keywords;
    public ?string $seo_description;
    public $header_logo;
    public $header_img_1;
    public ?string $header_name_1;
    public ?string $header_name_1_hi;
    public $header_img_2;
    public ?string $header_name_2;
    public ?string $header_name_2_hi;
    public $header_img_3;
    public ?string $header_name_3;
    public ?string $header_name_3_hi;
    public $footer_logo;
    public $favicon;
    public $admin_panel_logo;
    public ?string $site_address;
    public ?string $site_address_hi;
    public ?string $disclaimer;
    public ?string $disclaimer_hi;
    public ?string $copyright_text;
    public ?string $maintained_by_text;
    public ?string $maintained_by_text_hi;
    public ?string $copyright_text_hi;
    public $created_by;
    public $updated_by;

    public function __construct(
        $site_name,
        $site_name_hi = '',
        $seo_keywords = '',
        $seo_description = '',
        $header_logo = null,
        $header_img_1 = null,
        $header_name_1 = '',
        $header_name_1_hi = '',
        $header_img_2 = null,
        $header_name_2 = '',
        $header_name_2_hi = '',
        $header_img_3 = null,
        $header_name_3 = '',
        $header_name_3_hi = '',
        $footer_logo = null,
        $favicon = null,
        $admin_panel_logo = null,
        $site_address = '',
        $site_address_hi = '',
        $disclaimer = '',
        $disclaimer_hi = '',
        $copyright_text = '',
        $maintained_by_text = '',
        $maintained_by_text_hi = '',
        $copyright_text_hi = '',
        $created_by = null,
        $updated_by = null
    ) {
        $this->site_name = $site_name;
        $this->site_name_hi = $site_name_hi;
        $this->seo_keywords = $seo_keywords;
        $this->seo_description = $seo_description;
        $this->header_logo = $header_logo;
        $this->header_img_1 = $header_img_1;
        $this->header_name_1 = $header_name_1;
        $this->header_name_1_hi = $header_name_1_hi;
        $this->header_img_2 = $header_img_2;
        $this->header_name_2 = $header_name_2;
        $this->header_name_2_hi = $header_name_2_hi;
        $this->header_img_3 = $header_img_3;
        $this->header_name_3 = $header_name_3;
        $this->header_name_3_hi = $header_name_3_hi;
        $this->footer_logo = $footer_logo;
        $this->favicon = $favicon;
        $this->admin_panel_logo = $admin_panel_logo;
        $this->site_address = $site_address;
        $this->site_address_hi = $site_address_hi;
        $this->disclaimer = $disclaimer;
        $this->disclaimer_hi = $disclaimer_hi;
        $this->copyright_text = $copyright_text;
        $this->maintained_by_text = $maintained_by_text;
        $this->maintained_by_text_hi = $maintained_by_text_hi;
        $this->copyright_text_hi = $copyright_text_hi;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
    }
}
