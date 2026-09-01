<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Publication extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'category_id',
        'title',
        'title_hi',
        'price',
        'file_name',
        'file_name_hi',
        'remarks',
        'publish_remark',
        'is_approved',
        'is_published',
        'published_date',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc', 'file_url', 'file_url_hi', 'file_path_en', 'file_path_hi'];

    public function getFileUrlAttribute()
    {
        return asset('storage/' . config('file_paths')['PUBLICATION_FILE_EN_PATH'] . '/' . $this->file_name);
    }
    public function getFileUrlHiAttribute()
    {
        return asset('storage/' . config('file_paths')['PUBLICATION_FILE_HI_PATH'] . '/' . $this->file_name_hi);
    }

    public function getFilePathEnAttribute()
    {
        return $this->file_name ? base64_encode(Config::get('file_paths')['PUBLICATION_FILE_EN_PATH'] . '/' . $this->file_name) : null;
    }
    public function getFilePathHiAttribute()
    {
        return $this->file_name_hi ? base64_encode(Config::get('file_paths')['PUBLICATION_FILE_HI_PATH'] . '/' . $this->file_name_hi) : null;
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

    public function category()
    {
        return $this->belongsTo(PublicationCategory::class, 'category_id');
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
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('publication')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Publication has been {$eventName}");
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
