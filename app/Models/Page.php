<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Facades\Config;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Page extends Model
{
    use HasFactory, SoftDeletes, HasSlug, LogsActivity;

    protected $fillable = [
        'menu_id',
        'type',
        'slug',
        'title',
        'title_hi',
        'content',
        'content_hi',
        'featured_image',
        'is_approved',
        'is_published',
        'default_menu',
        'remarks',
        'publish_remark',
        'created_by',
        'updated_by',
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc', 'default_menu_desc', 'featured_image_full_path'];

    protected static function booted()
    {
        static::saved(function ($page) {
            \Illuminate\Support\Facades\Cache::flush();
        });

        static::deleted(function ($page) {
            \Illuminate\Support\Facades\Cache::flush();
        });
    }

    public function getFeaturedImageFullPathAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['PAGE_FEATURED_IMAGE_PATH'] . '/' . $this->featured_image);
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

    public function getDefaultMenuDescAttribute()
    {
        return $this->default_menu ? 'Enabled' : 'Disabled';
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function files()
    {
        return $this->hasMany(PageFile::class)->orderBy('order_number', 'desc');
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('page')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Page model has been {$eventName}");
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
