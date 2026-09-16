<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class OfficeProfileActivity extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'office_profile_activities';

    protected $fillable = [
        'office_type',
        'office_id',
        'title',
        'title_hi',
        'order',
        'record_status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'order' => 'integer',
        'record_status' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('record_status', 1);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('id', 'asc');
    }

    public function scopeForHeadOffice($query, $headOfficeId = null)
    {
        $q = $query->where('office_type', 'head_offices');
        if ($headOfficeId) {
            $q->where('office_id', $headOfficeId);
        }
        return $q;
    }

    public function scopeForRegionalDirectorate($query, $regionalDirectorateId = null)
    {
        $q = $query->where('office_type', 'regional_directorates');
        if ($regionalDirectorateId) {
            $q->where('office_id', $regionalDirectorateId);
        }
        return $q;
    }

    public function headOffice()
    {
        return $this->belongsTo(HeadOffice::class, 'office_id')->where('office_type', 'head_offices');
    }

    public function regionalDirectorate()
    {
        return $this->belongsTo(RegionalDirectorate::class, 'office_id')->where('office_type', 'regional_directorates');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('office_profile_activity')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Office Profile Activity has been {$eventName}");
    }
}
