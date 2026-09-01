<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AdditionalLogo extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'link',
        'title',
        'title_hi',
        'file_name',
        'is_approved',
        'is_published',
        'remarks',
        'created_by',
        'updated_by'
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc', 'file_url'];

    public function getFileUrlAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['ADDITIONAL_LOGO_IMAGE_PATH'] . '/' . $this->file_name);
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

    // 📝 Spatie activity log configuration
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // logs all fillable attributes
            ->useLogName('slider') // label for this model in logs
            ->logOnlyDirty() // logs only changed fields
            ->setDescriptionForEvent(fn(string $eventName) => "Additional Logo model has been {$eventName}");
    }

    public static function getPublishedLogos()
    {
        return self::where('is_published', 1)->get();
    }
}
