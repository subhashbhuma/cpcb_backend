<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class InformationCenter extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

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

    protected $appends = [
        'is_published_desc',
        'is_approved_desc',
    ];

    // ===== RELATIONSHIPS =====

    public function details(): HasMany
    {
        return $this->hasMany(InformationCenterDetail::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ===== APPENDED ATTRIBUTES =====

    public function getIsPublishedDescAttribute(): string
    {
        return $this->is_published == 1 ? 'Published' : 'Draft';
    }

    public function getIsApprovedDescAttribute(): string
    {
        return match($this->is_approved) {
            0 => 'Pending',
            1 => 'Approved',
            2 => 'Rejected',
            default => 'Unknown'
        };
    }

    // ===== ACTIVITY LOGGING =====

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'title_hi', 'is_approved', 'is_published', 'remarks'])
            ->setDescriptionForEvent(fn(string $eventName) => "InformationCenter has been {$eventName}")
            ->useLogName('information_center');
    }
}
