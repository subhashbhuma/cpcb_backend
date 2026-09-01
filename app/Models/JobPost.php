<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class JobPost extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'job_id',
        'title',
        'title_hi',
        'remarks',
        'is_approved',
        'is_published',
        'publish_remark',
        'created_by',
        'updated_by',
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc'];

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

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function recruitmentAnnouncements()
    {
        return $this->hasMany(RecruitmentAnnouncement::class, 'job_post_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('job_post')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Job Post model has been {$eventName}");
    }
}
