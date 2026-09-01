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

class Directory extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'name_hi',
        'cpcb_no',
        'assigned_work',
        'assigned_work_hi',
        'designation',
        'designation_hi',
        'division_id',
        'office_ph_no',
        'mobile_no',
        'email',
        'image',
        'ext_number',
        'order_no',
        'show_order',
        'remarks',
        'publish_remark',
        'is_approved',
        'is_published',
        'created_by',
        'updated_by',
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc', 'image_url'];

    protected $casts = [
        'order_no' => 'integer',
        'show_order' => 'integer',
    ];

    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/' . Config::get('file_paths')['DIRECTORY_IMAGE_PATH'] . '/' . $this->image)
            : null;
    }


    /**
     * Human-readable description for publication status.
     */
    public function getIsPublishedDescAttribute()
    {
        return $this->is_published ? 'Published' : 'Draft';
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }

    public function division_order()
    {
        return $this->belongsTo(DivisionOrder::class, 'order_no', 'order_no');
    }

    /**
     * Human-readable description for approval status.
     */
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

    /**
     * Get the latest update or creation timestamp for the table.
     * Useful for "Last updated on" metadata in your DataTable API.
     */
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

    /**
     * Configure Activity Log settings.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('directory')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Directory entry has been {$eventName}");
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
