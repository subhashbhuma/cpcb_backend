<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class RegionalDirectorateState extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'regional_directorate_states';

    protected $fillable = [
        'regional_directorate_id',
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

    public function regionalDirectorate()
    {
        return $this->belongsTo(RegionalDirectorate::class, 'regional_directorate_id');
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
            ->useLogName('regional_directorate_state')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Regional Directorate State has been {$eventName}");
    }
}
