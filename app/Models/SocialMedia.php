<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SocialMedia extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'type',
        'name',
        'url',
        'embed_code',
        'icon_class',
        'is_approved',
        'is_published',
        'remarks',
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('announcement')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Social media model has been {$eventName}");
    }

    public function socialMediaPlatform()
    {
        return $this->belongsTo(SocialMediaPlatform::class, 'type', 'id');
    }

    public static function getSocialMediaLinks()
    {
        return self::where([
            'is_approved' => 1,
            'is_published' => 1
        ])->get();
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
