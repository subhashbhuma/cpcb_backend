<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ZonalOffice extends Model
{
    use SoftDeletes, LogsActivity;
    protected $fillable = [
        'title',
        'title_hi',
        'remarks',
        'publish_remark',
        'is_approved',
        'is_published',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc'];

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
            ->useLogName('zonal_offices')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Zonal Office model has been {$eventName}");
    }
}
