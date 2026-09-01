<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Facades\Config;
class SiteSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'site_name' => $this->site_name,
            'site_name_hi' => $this->site_name_hi,
            'seo_keywords' => $this->seo_keywords,
            'seo_description' => $this->seo_description,

            'header_logo_path' => $this->header_logo_path,
            'header_img_1_path' => $this->header_img_1_path,
            'header_img_2_path' => $this->header_img_2_path,
            'header_img_3_path' => $this->header_img_3_path,
            'footer_logo_path' => $this->footer_logo_path,
            'favicon_path' => $this->favicon_path,

            'header_name_1' => $this->header_name_1,
            'header_name_1_hi' => $this->header_name_1_hi,
            'header_name_2' => $this->header_name_2,
            'header_name_2_hi' => $this->header_name_2_hi,
            'header_name_3' => $this->header_name_3,
            'header_name_3_hi' => $this->header_name_3_hi,
            'site_address' => $this->site_address,
            'site_address_hi' => $this->site_address_hi,
            'disclaimer' => $this->disclaimer,
            'disclaimer_hi' => $this->disclaimer_hi,
            'copyright_text' => $this->copyright_text,
            'maintained_by_text' => $this->maintained_by_text,
            'maintained_by_text_hi' => $this->maintained_by_text_hi,
            'copyright_text_hi' => $this->copyright_text_hi,
        ];
    }
}
