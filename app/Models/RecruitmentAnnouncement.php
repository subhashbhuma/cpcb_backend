<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Config;

class RecruitmentAnnouncement extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'job_post_id',
        'type',
        'title',
        'title_hi',
        'start_date',
        'end_date',
        'file_name',
        'file_name_hi',
        'remarks',
        'is_approved',
        'is_published',
        'publish_remark',
        'created_by',
        'updated_by',
    ];

    protected $appends = ['file_url', 'file_url_hi', 'is_approved_desc', 'is_published_desc'];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getFileUrlAttribute()
    {
        if (!$this->file_name)
            return null;
        return asset('storage/' . Config::get('file_paths')['RECRUITMENT_ANNOUNCEMENT_FILE_EN_PATH'] . '/' . $this->file_name);
    }

    public function getFileUrlHiAttribute()
    {
        if (!$this->file_name_hi)
            return null;
        return asset('storage/' . Config::get('file_paths')['RECRUITMENT_ANNOUNCEMENT_FILE_HI_PATH'] . '/' . $this->file_name_hi);
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('recruitment_announcement')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Recruitment Announcement model has been {$eventName}");
    }
}
