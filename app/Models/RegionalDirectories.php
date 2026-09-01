<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class RegionalDirectories extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'zone',
        'state',
        'address',
        'phone_numbers',
        'email_ids',
        'jurdiction',
        'location_link',
        'is_approved',
        'is_published',
        'remarks',
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('regionalDirectories')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Regional Directories model has been {$eventName}");
    }
}
