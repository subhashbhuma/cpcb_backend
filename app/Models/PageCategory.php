<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Config;

class PageCategory extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'title',
        'title_hi',
        'file_name',
        'file_name_hi',
        'is_approved',
        'is_published',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc','file_url', 'file_url_hi', 'file_path_en', 'file_path_hi'];

    public function getFileUrlAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['PAGE_CATEGORY_FILE_EN_PATH'] . '/' . $this->file_name);
    }

    public function getFileUrlHiAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['PAGE_CATEGORY_FILE_HI_PATH'] . '/' . $this->file_name_hi);
    }
    public function getIsPublishedDescAttribute()
    {
        return $this->is_published ? 'Published' : 'Draft';
    }

    public function getFilePathEnAttribute()
    {
        return $this->file_name ? base64_encode(Config::get('file_paths')['PAGE_CATEGORY_FILE_EN_PATH'] . '/' . $this->file_name) : null;
    }
    public function getFilePathHiAttribute()
    {
        return $this->file_name_hi ? base64_encode(Config::get('file_paths')['PAGE_CATEGORY_FILE_HI_PATH'] . '/' . $this->file_name_hi) : null;
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('page_categories')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "PageCategory model has been {$eventName}");
    }
}
