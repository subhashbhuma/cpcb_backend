<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class StudiesReport extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'title',
        'title_hi',
        'division_id',
        'report_year',
        'file_name',
        'file_name_hi',
        'is_approved',
        'is_published',
        'remarks',
        'publish_remark',
        'created_by',
        'updated_by',
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc', 'file_url', 'file_url_hi', 'file_path_en', 'file_path_hi'];

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }

    public function getFileUrlAttribute()
    {
        $path = Config::get('file_paths')['STUDIES_REPORT_FILE_EN_PATH'] ?? 'studies_reports/en';
        return asset('storage/' . $path . '/' . $this->file_name);
    }

    public function getFileUrlHiAttribute()
    {
        $path = Config::get('file_paths')['STUDIES_REPORT_FILE_HI_PATH'] ?? 'studies_reports/hi';
        return asset('storage/' . $path . '/' . $this->file_name_hi);
    }

    public function getFilePathEnAttribute()
    {
        return $this->file_name ? base64_encode(Config::get('file_paths')['STUDIES_REPORT_FILE_EN_PATH'] . '/' . $this->file_name) : null;
    }

    public function getFilePathHiAttribute()
    {
        return $this->file_name_hi ? base64_encode(Config::get('file_paths')['STUDIES_REPORT_FILE_HI_PATH'] . '/' . $this->file_name_hi) : null;
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

    public static function getLastUpdatedOrCreatedAt()
    {
        $model = new static();
        $tableName = $model->getTable();
        $columns = Schema::getColumnListing($tableName);
        $query = DB::table($tableName)->whereNull('deleted_at');

        if (in_array('updated_at', $columns)) {
            $timestamp = $query->max('updated_at');
        } elseif (in_array('created_at', $columns)) {
            $timestamp = $query->max('created_at');
        } else {
            return null;
        }

        return $timestamp ? Carbon::parse($timestamp)->format('d-m-Y H:i:s') : null;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('studies_report')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Studies Report has been {$eventName}");
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
