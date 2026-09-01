<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class GalleryEvent extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'featured_image',
        'title',
        'title_hi',
        'is_approved',
        'is_published',
        'remarks',
        'publish_remark',
        'created_by',
        'updated_by',
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc', 'featured_image_full_path'];

    public function getFeaturedImageFullPathAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['GALLERY_EVENT_FEATURED_IMAGE_PATH'] . '/' . $this->featured_image);
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
            ->useLogName('gallery_events')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Gallery Event model has been {$eventName}");
    }

    public function photoGalleries()
    {
        return $this->hasMany(PhotoGallery::class, 'gallery_event_id');
    }

    public function videoGalleries()
    {
        return $this->hasMany(VideoGallery::class, 'gallery_event_id');
    }

    public function images()
    {
        return $this->hasManyThrough(
            PhotoGalleryFile::class,
            PhotoGallery::class,
            'gallery_event_id',
            'photo_gallery_id',
            'id',
            'id'
        );
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
