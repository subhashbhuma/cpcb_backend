<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AgraAirQuality extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'quality_zone_id',
        'title',
        'title_hi',
        'file_name',
        'file_name_hi',
        'for_date',
        'is_approved',
        'is_published',
        'remarks',
        'publish_remark',
        'created_by',
        'updated_by',
    ];

    protected $appends = [
        'file_en_url',
        'file_hi_url',
        'approval_status_description',
        'publish_status_description',
        'is_approved_desc',
        'is_published_desc',
        'file_path_en', 'file_path_hi'
    ];

    public function getFilePathEnAttribute()
    {
        return $this->file_name ? base64_encode(config('file_paths.AGRA_AIR_QUALITY_FILE_EN_PATH') . '/' . $this->file_name) : null;
    }
    public function getFilePathHiAttribute()
    {
        return $this->file_name_hi ? base64_encode(config('file_paths.AGRA_AIR_QUALITY_FILE_HI_PATH') . '/' . $this->file_name_hi) : null;
    }

    


    public static function getAgraAirQualityFile()
    {
        $fileName = 'AAQM_data-Project_Office_Agra-2002-2023.pdf';
        return base64_encode(config('file_paths.AGRA_AIR_QUALITY_ANNUAL_AVERAGE_FILE_EN_PATH') . '/' . $fileName);
    }



    /**
     * Get the activity log options
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('agra_air_quality');
    }

    /**
     * Relationship with QualityZone
     */
    public function qualityZone()
    {
        return $this->belongsTo(QualityZone::class);
    }

    /**
     * Get file URL for English file
     */
    public function getFileEnUrlAttribute()
    {
        if ($this->file_name) {
            return asset('storage/' . config('file_paths.AGRA_AIR_QUALITY_FILE_EN_PATH') . '/' . $this->file_name);
        }
        return null;
    }


    /**
     * Get file URL for Hindi file
     */
    public function getFileHiUrlAttribute()
    {
        if ($this->file_name_hi) {
            return asset('storage/' . config('file_paths.AGRA_AIR_QUALITY_FILE_HI_PATH') . '/' . $this->file_name_hi);
        }
        return null;
    }

    /**
     * Get approval status description
     */
    public function getApprovalStatusDescriptionAttribute()
    {
        return match ($this->is_approved) {
            0 => 'Pending',
            1 => 'Approved',
            2 => 'Rejected',
            default => 'Unknown',
        };
    }

    /**
     * Get publish status description
     */
    public function getPublishStatusDescriptionAttribute()
    {
        return match ($this->is_published) {
            0 => 'Draft',
            1 => 'Published',
            default => 'Unknown',
        };
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

    public function getIsPublishedDescAttribute()
    {
        return $this->is_published ? 'Published' : 'Draft';
    }

    /**
     * Get the last updated or created timestamp from the table
     */
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
