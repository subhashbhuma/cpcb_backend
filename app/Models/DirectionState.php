<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DirectionState extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'title',
        'title_hi',
        'is_approved',
        'is_published',
        'remarks',
        'publish_remark',
        'created_by',
        'updated_by'
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'title_hi', 'is_approved', 'is_published', 'remarks', 'publish_remark'])
            ->useLogName('Direction State');
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

    public function getIsPublishedDescAttribute()
    {
        return $this->is_published == 1 ? 'Published' : 'Draft';
    }
}
