<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
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
}
