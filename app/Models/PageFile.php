<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PageFile extends Model
{
    use SoftDeletes, LogsActivity;
    protected $guarded = [];

    protected $appends = ['file_path', 'file_path_hi','file_url_en', 'file_url_hi'];

    protected static function boot()
    {
        parent::boot();
        
        // Auto-set order_number on creation if not provided
        static::creating(function ($pageFile) {
            if (is_null($pageFile->order_number)) {
                $pageFile->order_number = 0;
            }
        });
    }

    // Scope for ordering files
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_number', 'asc')->orderBy('created_at', 'asc');
    }

    public function getFilePathAttribute()
    {
        return $this->file_name ? asset('storage/' . Config::get('file_paths')['PAGE_FILE_EN_PATH'] . '/' . $this->file_name)  : null;
    }

    public function getFilePathHiAttribute()
    {
        return $this->file_name_hi ? asset('storage/' . Config::get('file_paths')['PAGE_FILE_HI_PATH'] . '/' . $this->file_name_hi) : null;
    }

    public function getFileUrlEnAttribute()
    {
        return $this->file_name ? base64_encode(Config::get('file_paths')['PAGE_FILE_EN_PATH'] . '/' . $this->file_name) : null;
    }
    public function getFileUrlHiAttribute()
    {
        return $this->file_name_hi ? base64_encode(Config::get('file_paths')['PAGE_FILE_HI_PATH'] . '/' . $this->file_name_hi) : null;
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('page_file')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "PageFile model has been {$eventName}");
    }
}
