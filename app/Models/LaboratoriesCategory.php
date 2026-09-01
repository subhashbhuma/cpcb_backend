<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LaboratoriesCategory extends Model {

    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'laboratories_category';

    protected $fillable = [
        'title',
        'title_hi',
        'slogan',
        'slogan_hi',
        'description',
        'description_hi',
        'featured_image',
        'permission_group',
        'is_approved',
        'is_published',
        'remarks',
        'created_by',
        'updated_by',
    ];
    protected $appends = ['is_approved_desc', 'is_published_desc', 'featured_image_full_path'];
    public function getFeaturedImageFullPathAttribute()
    {
        return asset('storage/' . config('file_paths')['LABORATORIES_CATEGORY_FEATURED_IMAGE_PATH'] . '/' . $this->featured_image);
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

    public function laboratoriesPages()
    {
        return $this->hasMany(LaboratoriesPage::class, 'category_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('laboratoriesCategory')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Laboratories Category model has been {$eventName}");
    }

}