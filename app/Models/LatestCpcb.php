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

class LatestCpcb extends Model
{
    use softDeletes, LogsActivity;

    protected $fillable = [
        'title',
        'title_hi',
        'publish_date',
        'division_id',
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

    public function getFileUrlAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['LATEST_CPCB_FILE_EN_PATH'] . '/' . $this->file_name);
    }

    public function getFileUrlHiAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['LATEST_CPCB_FILE_HI_PATH'] . '/' . $this->file_name_hi);
    }

    public function getFilePathEnAttribute()
    {
        return $this->file_name ? base64_encode(Config::get('file_paths')['LATEST_CPCB_FILE_EN_PATH'] . '/' . $this->file_name) : null;
    }
    public function getFilePathHiAttribute()
    {
        return $this->file_name_hi ? base64_encode(Config::get('file_paths')['LATEST_CPCB_FILE_HI_PATH'] . '/' . $this->file_name_hi) : null;
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
            ->useLogName('latest_cpcbs') // optional: label for this model
            ->logOnlyDirty() // only logs changed fields
            ->setDescriptionForEvent(fn(string $eventName) => "Latest CPCB model has been {$eventName}");
    }

     public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updated_by_user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function division()
    {
         return $this->belongsTo(Division::class, 'division_id');
    }
}
