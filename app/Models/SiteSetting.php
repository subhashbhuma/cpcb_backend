<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SiteSetting extends Model
{
    use SoftDeletes, LogsActivity;
    
    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('site_settings');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('site_settings');
        });
    }

    protected $fillable = [
        'site_name',
        'site_name_hi',
        'seo_keywords',
        'seo_description',
        'header_logo',
        'header_img_1',
        'header_name_1',
        'header_name_1_hi',
        'header_img_2',
        'header_name_2',
        'header_name_2_hi',
        'header_img_3',
        'header_name_3',
        'header_name_3_hi',
        'footer_logo',
        'favicon',
        'admin_panel_logo',
        'site_address',
        'site_address_hi',
        'disclaimer',
        'disclaimer_hi',
        'copyright_text',
        'copyright_text_hi',
        'maintained_by_text',
        'maintained_by_text_hi',
        'created_by',
        'updated_by'
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc', 'header_logo_url','footer_logo_url','favicon_logo_url','admin_panel_logo_url'];

    public function getIsPublishedDescAttribute()
    {
        return $this->is_published ? 'Published' : 'Draft';
    }

    public function getIsApprovedDescAttribute()
    {
        if ($this->is_approved == 1) {
            return 'Approved';
        } elseif ($this->is_approved == 2) {
            return 'Rejected';
        } else {
            return 'Pending';
        }
    }

    public function getHeaderLogoUrlAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['SITE_HEADER_LOGO_PATH'] . '/' . $this->file_name);
    }

    public function getFooterLogoUrlAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['SITE_FOOTER_LOGO_PATH'] . '/' . $this->file_name);
    }

    public function getFaviconLogoUrlAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['SITE_FAVICON_PATH'] . '/' . $this->file_name);
    }

    public function getAdminPanelLogoUrlAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['SITE_ADMIN_PANEL_LOGO_PATH'] . '/' . $this->file_name);
    }

    public function getHeaderLogoPathAttribute()
    {
        return $this->header_logo ? base64_encode(Config::get('file_paths')['SITE_HEADER_LOGO_PATH'] . '/' . $this->header_logo) : null;
    }

    public function getHeaderImg1PathAttribute()
    {
        return $this->header_img_1 ? base64_encode(Config::get('file_paths')['SITE_HEADER_IMAGES_PATH'] . '/' . $this->header_img_1) : null;
    }

    public function getHeaderImg2PathAttribute()
    {
        return $this->header_img_2 ? base64_encode(Config::get('file_paths')['SITE_HEADER_IMAGES_PATH'] . '/' . $this->header_img_2) : null;
    }

    public function getHeaderImg3PathAttribute()
    {
        return $this->header_img_3 ? base64_encode(Config::get('file_paths')['SITE_HEADER_IMAGES_PATH'] . '/' . $this->header_img_3) : null;
    }

    public function getFooterLogoPathAttribute()
    {
        return $this->footer_logo ? base64_encode(Config::get('file_paths')['SITE_FOOTER_LOGO_PATH'] . '/' . $this->footer_logo) : null;
    }

    public function getFaviconPathAttribute()
    {
        return $this->favicon ? base64_encode(Config::get('file_paths')['SITE_FAVICON_PATH'] . '/' . $this->favicon) : null;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('site_setting')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "SiteSetting model has been {$eventName}");
    }
}
