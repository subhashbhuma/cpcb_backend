<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Carbon\Carbon;

class CommentReport extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'title',
        'title_hi',
        'emails',
        'published_date',
        'file_name',
        'file_name_hi',
        'description',
        'description_hi',
        'is_approved',
        'is_published',
        'remarks',
        'publish_remark',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'published_date' => 'datetime',
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc', 'file_url', 'file_url_hi','file_path_en', 'file_path_hi'];

    public function getFileUrlAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['COMMENT_REPORT_FILE_EN_PATH'] . '/' . $this->file_name);
    }

    public function getFileUrlHiAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['COMMENT_REPORT_FILE_HI_PATH'] . '/' . $this->file_name_hi);
    }
     public function getFilePathEnAttribute()
    {
        return $this->file_name ? base64_encode(Config::get('file_paths')['COMMENT_REPORT_FILE_EN_PATH'] . '/' . $this->file_name) : null;
    }
    public function getFilePathHiAttribute()
    {
        return $this->file_name_hi ? base64_encode(Config::get('file_paths')['COMMENT_REPORT_FILE_HI_PATH'] . '/' . $this->file_name_hi) : null;
    }

    public function getIsPublishedDescAttribute()
    {
        return $this->is_published ? 'Published' : 'Draft';
    }

    public function getIsApprovedDescAttribute()
    {
        if ($this->is_approved == 1) {
            return 'Approved';
        } elseif ($this->is_approved == 2) { // Logic handling integer/boolean mix if any
             return 'Rejected';
        } else {
            return 'Pending';
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('comment_report')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Comment Report model has been {$eventName}");
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
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
}
