<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class GovernmentPortal extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'file_name',
        'title',
        'title_hi',
        'link',
        'is_approved',
        'is_published',
        'remarks',
        'publish_remark',
        'created_by',
        'updated_by',
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc', 'file_name_full_path'];

    public function getFileNameFullPathAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['GOVERNMENT_PORTAL_IMAGE_PATH'] . '/' . $this->file_name);
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
            ->useLogName('governmentportal')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Government portal model has been {$eventName}");
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
