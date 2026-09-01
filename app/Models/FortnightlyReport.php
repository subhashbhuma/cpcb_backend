<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class FortnightlyReport extends Model
{
    use softDeletes, LogsActivity;

    protected $fillable = [
        'title',
        'title_hi',
        'file_name',
        'file_name_hi',
        'is_approved',
        'is_published',
        'remarks',
        'publish_remark',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_approved' => 'integer',
        'is_published' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc', 'file_url', 'file_url_hi', 'file_path_en', 'file_path_hi'];

    // Activity log configuration
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getFileUrlAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['FORTNIGHTLY_REPORT_FILE_EN_PATH'] . '/' . $this->file_name);
    }

    public function getFileUrlHiAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['FORTNIGHTLY_REPORT_FILE_HI_PATH'] . '/' . $this->file_name_hi);
    }

    public function getFilePathEnAttribute()
    {
        return $this->file_name ? base64_encode(Config::get('file_paths')['FORTNIGHTLY_REPORT_FILE_EN_PATH'] . '/' . $this->file_name) : null;
    }
    public function getFilePathHiAttribute()
    {
        return $this->file_name_hi ? base64_encode(Config::get('file_paths')['FORTNIGHTLY_REPORT_FILE_HI_PATH'] . '/' . $this->file_name_hi) : null;
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

    // Get last updated or created timestamp
    public static function getLastUpdatedOrCreatedAt($tableName = 'fortnightly_reports', $type = null)
    {
        $columns = Schema::getColumnListing($tableName);
        if (in_array('updated_at', $columns)) {
            $query = DB::table($tableName);
            if ($type) {
                $query->where('type', $type);
            }
            $timestamps = $query->whereNull('deleted_at')->max('updated_at');
        } elseif (in_array('created_at', $columns)) {
            $query = DB::table($tableName);
            if ($type) {
                $query->where('type', $type);
            }
            $timestamps = $query->whereNull('deleted_at')->max('created_at');
        } else {
            // Fallback if needed, or throw exception like latest cpcb
             return null;
        }
        return Carbon::parse($timestamps)->format('d-m-Y H:i:s');
    }

    // Relationships
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }
}
