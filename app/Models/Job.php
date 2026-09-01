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

class Job extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'title',
        'title_hi',
        'start_date',
        'end_date',
        'advertisement_file_name',
        'advertisement_file_hi_name',
        'direct_application_form_name',
        'direct_application_form_hi_name',
        'deputation_application_form_name',
        'deputation_application_form_hi_name',
        'job_type',
        'direct_application',
        'deputation_application',
        'direct_application_url',
        'deputation_application_url',
        'online_form_url',
        'walk_in_interview_date',
        'is_approved',
        'is_published',
        'remarks',
        'publish_remark',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc', 'advertisement_file_url', 'advertisement_file_url_hi', 'direct_application_form_url', 'direct_application_form_url_hi', 'deputation_application_form_url', 'deputation_application_form_url_hi'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function jobPosts()
    {
        return $this->hasMany(JobPost::class, 'job_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
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

    public function getAdvertisementFileUrlAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['JOB_ADVERTIESMENT_FILE_EN_PATH'] . '/' . $this->advertisement_file_name);
    }

    public function getAdvertisementFileUrlHiAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['JOB_ADVERTIESMENT_FILE_HI_PATH'] . '/' . $this->advertisement_file_hi_name);
    }

    public function getDirectApplicationFormUrlAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['DIRECT_APPLICATION_FILE_EN_PATH'] . '/' . $this->direct_application_form_name);
    }

    public function getDirectApplicationFormUrlHiAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['DIRECT_APPLICATION_FILE_HI_PATH'] . '/' . $this->direct_application_form_hi_name);
    }

    public function getDeputationApplicationFormUrlAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['DEPUTATION_APPLICATION_FILE_EN_PATH'] . '/' . $this->deputation_application_form_name);
    }

    public function getDeputationApplicationFormUrlHiAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['DEPUTATION_APPLICATION_FILE_HI_PATH'] . '/' . $this->deputation_application_form_hi_name);
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
            ->useLogName('job')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Job model has been {$eventName}");
    }
}
