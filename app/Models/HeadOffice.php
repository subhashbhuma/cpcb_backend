<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class HeadOffice extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'division_id',
        'title',
        'title_hi',
        'email',
        'image',
        'ext_number',
        'description',
        'description_hi',
        'order',
        'is_approved',
        'is_published',
        'remarks',
        'publish_remark',
        'created_by',
        'updated_by',
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc'];

    public function getIsPublishedDescAttribute()
    {
        return $this->is_published ? 'Published' : 'Draft';
    }


    public function getFinalImageUrlAttribute()
    {
         return $this->image ? base64_encode(Config::get('file_paths')['HEAD_OFFICE_IMAGE_PATH'] . '/' . $this->image) : null;
    }


    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
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
            ->useLogName('head_office')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Head Office has been {$eventName}");
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
            ->where('office_type', 'head_offices')
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
            ->where('office_type', 'head_offices')
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc');
    }

    public function active_profile_activities()
    {
        return $this->profileActivities()->where('record_status', 1);
    }
}


