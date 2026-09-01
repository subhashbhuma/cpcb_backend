<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PhotoGallery extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'gallery_event_id',
        'featured_image',
        'title',
        'title_hi',
        'description',
        'description_hi',
        'date',
        'is_approved',
        'is_published',
        'remarks',
        'publish_remark',
        'created_by',
        'updated_by',
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc', 'featured_image_full_path', 'file_path_featured_image'];

    public function getFeaturedImageFullPathAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['PHOTO_GALLERY_FEATURED_IMAGE_PATH'] . '/' . $this->featured_image);
    }
    public function getFilePathFeaturedImageAttribute()
    {
        return $this->featured_image ? base64_encode(Config::get('file_paths')['PHOTO_GALLERY_FEATURED_IMAGE_PATH'] . '/' . $this->featured_image) : null;
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
            ->useLogName('photo_gallery')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Photo Gallery model has been {$eventName}");
    }

    public static function getLastUpdatedOrCreatedAt($type = null)
    {
        $model = new static();
        $tableName = $model->getTable();

        $columns = Schema::getColumnListing($tableName);

        $query = DB::table($tableName)->whereNull('deleted_at');

        if ($type && Schema::hasColumn($tableName, 'type')) {
            $query->where('type', $type);
        }

        if (in_array('updated_at', $columns)) {
            $timestamp = $query->max('updated_at');
        } elseif (in_array('created_at', $columns)) {
            $timestamp = $query->max('created_at');
        } else {
            throw new \Exception("Table '{$tableName}' has no timestamps.");
        }

        return $timestamp
            ? Carbon::parse($timestamp)->format('d-m-Y H:i:s')
            : null;
    }

    public function images()
    {
        return $this->hasMany(PhotoGalleryFile::class);
    }


    public function event()
    {
        return $this->belongsTo(GalleryEvent::class, 'gallery_event_id');
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
