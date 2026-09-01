<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LaboratoriesPage extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    protected $table = 'laboratories_page';

    protected $fillable = [
        'title',
        'title_hi',
        'category_id',
        'featured_image',
        'public_comments',
        'public_comments_hi',
        'public_comments_url',
        'content',
        'content_hi',
        'is_approved',
        'is_published',
        'remarks',
        'created_by',
        'updated_by'
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc', 'featured_image_url'];

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
    public function category()
    {
        return $this->belongsTo(LaboratoriesCategory::class, 'category_id');
    }
    public function files(){
        return $this->hasMany(LaboratoriesFile::class, 'page_id');
    }
    public function getFeaturedImageUrlAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['LABORATORIES_FEATURED_IMAGE_PATH'] . '/' . $this->featured_image);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('laboratoriesPage')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Laboratories Page model has been {$eventName}");
    }
}