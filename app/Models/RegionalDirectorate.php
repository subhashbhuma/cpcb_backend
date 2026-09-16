<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class RegionalDirectorate extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'regional_directorate',
        'regional_directorate_hi',
        'title',
        'title_hi',
        'designation',
        'designation_hi',
        'email',
        'image',
        'description',
        'description_hi',
        'is_approved',
        'is_published',
        'remarks',
        'publish_remark',
        'order',
        'created_by',
        'updated_by',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->useLogName('regional_directorate');
    }

    public function getIsApprovedDescAttribute()
    {
        switch ($this->is_approved) {
            case 0:
                return 'Pending';
            case 1:
                return 'Approved';
            case 2:
                return 'Rejected';
            default:
                return 'Unknown';
        }
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



    public function getIsPublishedDescAttribute()
    {
        return $this->is_published ? 'Published' : 'Draft';
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updated_by_user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function personnels()
    {
        return $this->hasMany(OfficePersonnel::class, 'office_id')
            ->where('office_type', 'regional_directorates')
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc');
    }

    public function active_personnels()
    {
        return $this->personnels()->where('record_status', 1);
    }

    public function profileActivities()
    {
        return $this->hasMany(OfficeProfileActivity::class, 'office_id')
            ->where('office_type', 'regional_directorates')
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc');
    }

    public function active_profile_activities()
    {
        return $this->profileActivities()->where('record_status', 1);
    }

    public function states()
    {
        return $this->hasMany(RegionalDirectorateState::class, 'regional_directorate_id')
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc');
    }

    public function active_states()
    {
        return $this->states()->where('record_status', 1);
    }
}

