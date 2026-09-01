<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class QualityZone extends Model
{
    use softDeletes, LogsActivity;

    protected $fillable = [
        'title',
        'title_hi',
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

    public static function getLastUpdatedOrCreatedAt($tableName, $type = null)
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
            throw new \Exception("Table '$tableName' does not have 'updated_at' or 'created_at' columns.");
        }
        return Carbon::parse($timestamps)->format('d-m-Y H:i:s');
    }

    // 👇 Spatie Activitylog Settings
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // logs all fillable attributes
            ->useLogName('quality_zones') // optional: label for this model
            ->logOnlyDirty() // only logs changed fields
            ->setDescriptionForEvent(fn(string $eventName) => "Quality Zone model has been {$eventName}");
    }
}
