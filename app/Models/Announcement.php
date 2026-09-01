<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Announcement extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'title',
        'title_hi',
        'file_or_link',
        'file_name',
        'file_name_hi',
        'page_link',
        'status',
        'is_approved',
        'is_published',
        'remarks',
        'publish_remark',
        'created_by',
        'updated_by',
        'published_date',
    ];

    protected $casts = [
        'published_date' => 'date',
    ];

    protected $appends = ['status_desc', 'is_approved_desc', 'is_published_desc', 'file_url', 'file_url_hi','file_path_en', 'file_path_hi'];

    public function getFileUrlAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['ANNOUNCEMENT_FILE_EN_PATH'] . '/' . $this->file_name);
    }

    public function getFileUrlHiAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['ANNOUNCEMENT_FILE_HI_PATH'] . '/' . $this->file_name_hi);
    }
     public function getFilePathEnAttribute()
    {
        return $this->file_name ? base64_encode(Config::get('file_paths')['ANNOUNCEMENT_FILE_EN_PATH'] . '/' . $this->file_name) : null;
    }
    public function getFilePathHiAttribute()
    {
        return $this->file_name_hi ? base64_encode(Config::get('file_paths')['ANNOUNCEMENT_FILE_HI_PATH'] . '/' . $this->file_name_hi) : null;
    }
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

    public function getStatusDescAttribute()
    {
        return $this->status ? 'Active' : 'Inactive';
    }





    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('announcement')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Announcement model has been {$eventName}");
    }

     public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updated_by_user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
