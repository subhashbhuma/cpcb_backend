<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Slider extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'title',
        'title_hi',
        'description',
        'description_hi',
        'file_name',
        'link',
        'is_approved',
        'is_published',
        'remarks',
        'publish_remark',
        'created_by',
        'updated_by'
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc', 'file_url','final_file_url'];

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

    public function getFileUrlAttribute()
    {
        return $this->file_name ? asset('storage/' . Config::get('file_paths')['SLIDER_IMAGE_PATH'] . '/' . $this->file_name) : null;
    }

    public function getFinalFileUrlAttribute()
    {
         return $this->file_name ? base64_encode(Config::get('file_paths')['SLIDER_IMAGE_PATH'] . '/' . $this->file_name) : null;
    }



    // 📝 Spatie activity log configuration
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // logs all fillable attributes
            ->useLogName('slider') // label for this model in logs
            ->logOnlyDirty() // logs only changed fields
            ->setDescriptionForEvent(fn(string $eventName) => "Slider model has been {$eventName}");
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
