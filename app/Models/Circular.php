<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Circular extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'title',
        'title_hi',
        'division_id',
        'category',
        'published_date',
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
        'published_date' => 'datetime',
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc', 'file_url', 'file_url_hi', 'file_path_en', 'file_path_hi'];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', DB::raw($this->booleanLiteral(true)));
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', DB::raw($this->booleanLiteral(true)));
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('is_approved', DB::raw($this->booleanLiteral(false)));
    }

    private function booleanLiteral(bool $value): string
    {
        if (DB::connection()->getDriverName() === 'sqlsrv') {
            return $value ? '1' : '0';
        }

        return $value ? 'TRUE' : 'FALSE';
    }

    public function getFileUrlAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['CIRCULAR_FILE_EN_PATH'] . '/' . $this->file_name);
    }

    public function getFileUrlHiAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['CIRCULAR_FILE_HI_PATH'] . '/' . $this->file_name_hi);
    }
    public function getFilePathEnAttribute()
    {
        return $this->file_name ? base64_encode(Config::get('file_paths')['CIRCULAR_FILE_EN_PATH'] . '/' . $this->file_name) : null;
    }
    public function getFilePathHiAttribute()
    {
        return $this->file_name_hi ? base64_encode(Config::get('file_paths')['CIRCULAR_FILE_HI_PATH'] . '/' . $this->file_name_hi) : null;
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('announcement')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Announcement model has been {$eventName}");
    }

    public function circularCategory()
    {
        return $this->belongsTo(CircularCategory::class, 'category', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }

    public static function getLastUpdatedOrCreatedAt($type = null)
    {
        return \Illuminate\Support\Facades\Cache::remember("last_updated_circulars_" . ($type ?? 'all'), 120, function () use ($type) {
            $query = DB::table('circulars')->whereNull('deleted_at');

            if ($type) {
                $query->where('type', $type);
            }

            $timestamp = $query->max('updated_at') ?? $query->max('created_at');

            return $timestamp
                ? Carbon::parse($timestamp)->format('d-m-Y H:i:s')
                : null;
        });
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
