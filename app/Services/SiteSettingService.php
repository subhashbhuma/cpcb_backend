<?php

namespace App\Services;

use App\Repositories\SiteSettingRepository;
use App\DTO\SiteSettingDto;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class SiteSettingService
{
    use FileUploadTrait;
    private $siteSettingRepository;

    public function __construct()
    {
        $this->siteSettingRepository = new SiteSettingRepository();
    }

    public function findFirst()
    {
        return $this->siteSettingRepository->findFirst();
    }

    public function findById($id)
    {
        return $this->siteSettingRepository->findById($id);
    }

    public function update(SiteSettingDto $siteSettingDto, $id)
    {
        // Upload header logo
        if ($siteSettingDto->header_logo) {
            $headerFile = $this->uploadFile($siteSettingDto->header_logo, Config::get('file_paths')['SITE_HEADER_LOGO_PATH']);
            $siteSettingDto->header_logo = $headerFile['file_name'];
        }

        if ($siteSettingDto->header_img_1) {
            $headerFile = $this->uploadFile($siteSettingDto->header_img_1, Config::get('file_paths')['SITE_HEADER_IMAGES_PATH']);
            $siteSettingDto->header_img_1 = $headerFile['file_name'];
        }
        if ($siteSettingDto->header_img_2) {
            $headerFile = $this->uploadFile($siteSettingDto->header_img_2, Config::get('file_paths')['SITE_HEADER_IMAGES_PATH']);
            $siteSettingDto->header_img_2 = $headerFile['file_name'];
        }
        if ($siteSettingDto->header_img_3) {
            $headerFile = $this->uploadFile($siteSettingDto->header_img_3, Config::get('file_paths')['SITE_HEADER_IMAGES_PATH']);
            $siteSettingDto->header_img_3 = $headerFile['file_name'];
        }

        // Upload footer logo
        if ($siteSettingDto->footer_logo) {
            $footerFile = $this->uploadFile($siteSettingDto->footer_logo, Config::get('file_paths')['SITE_FOOTER_LOGO_PATH']);
            $siteSettingDto->footer_logo = $footerFile['file_name'];
        }

        // Upload favicon logo
        if ($siteSettingDto->favicon) {
            $faviconFile = $this->uploadFile($siteSettingDto->favicon, Config::get('file_paths')['SITE_FAVICON_PATH']);
            $siteSettingDto->favicon = $faviconFile['file_name'];
        }

        // Upload admin panel logo
        if ($siteSettingDto->admin_panel_logo) {
            $adminLogoFile = $this->uploadFile($siteSettingDto->admin_panel_logo, Config::get('file_paths')['SITE_ADMIN_PANEL_LOGO_PATH']);
            $siteSettingDto->admin_panel_logo = $adminLogoFile['file_name'];
        }

        $updateData = [
            'site_name' => $siteSettingDto->site_name,
            'site_name_hi' => $siteSettingDto->site_name_hi,
            'seo_keywords' => $siteSettingDto->seo_keywords,
            'seo_description' => $siteSettingDto->seo_description,
            'header_name_1' => $siteSettingDto->header_name_1,
            'header_name_1_hi' => $siteSettingDto->header_name_1_hi,
            'header_name_2' => $siteSettingDto->header_name_2,
            'header_name_2_hi' => $siteSettingDto->header_name_2_hi,
            'header_name_3' => $siteSettingDto->header_name_3,
            'header_name_3_hi' => $siteSettingDto->header_name_3_hi,
            'site_address' => $siteSettingDto->site_address,
            'site_address_hi' => $siteSettingDto->site_address_hi,
            'disclaimer' => $siteSettingDto->disclaimer,
            'disclaimer_hi' => $siteSettingDto->disclaimer_hi,
            'copyright_text' => $siteSettingDto->copyright_text,
            'maintained_by_text' => $siteSettingDto->maintained_by_text,
            'maintained_by_text_hi' => $siteSettingDto->maintained_by_text_hi,
            'copyright_text_hi' => $siteSettingDto->copyright_text_hi,
            'created_by' => $siteSettingDto->created_by,
            'updated_by' => $siteSettingDto->updated_by,
        ];

        if ($siteSettingDto->header_logo) {
            $updateData['header_logo'] = $siteSettingDto->header_logo;
        }
        if ($siteSettingDto->header_img_1) {
            $updateData['header_img_1'] = $siteSettingDto->header_img_1;
        }
        if ($siteSettingDto->header_img_2) {
            $updateData['header_img_2'] = $siteSettingDto->header_img_2;
        }
        if ($siteSettingDto->header_img_3) {
            $updateData['header_img_3'] = $siteSettingDto->header_img_3;
        }
        if ($siteSettingDto->footer_logo) {
            $updateData['footer_logo'] = $siteSettingDto->footer_logo;
        }
        if ($siteSettingDto->favicon) {
            $updateData['favicon'] = $siteSettingDto->favicon;
        }
        if ($siteSettingDto->admin_panel_logo) {
            $updateData['admin_panel_logo'] = $siteSettingDto->admin_panel_logo;
        }

        $siteSetting = $this->siteSettingRepository->update($updateData, $id);

        if (!$siteSetting) {
            return false;
        }

        return $siteSetting;
    }


}
