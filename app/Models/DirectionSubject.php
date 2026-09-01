<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DirectionSubject extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'direction_subjects';

    protected $fillable = [
        'title',
        'title_hi',
        'direction_act_type_id',
        'is_approved',
        'is_published',
        'remarks',
        'publish_remark',
        'created_by',
        'updated_by',
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'title_hi', 'direction_act_type_id', 'is_approved', 'is_published', 'remarks'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function directionActType()
    {
        return $this->belongsTo(DirectionActType::class, 'direction_act_type_id');
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
        return $this->is_published ? 'Published' : 'Draft';
    }
}
