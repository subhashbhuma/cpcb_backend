<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class InformationCenterDetail extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'information_center_id',
        'type',
        'url',
        'file_name',
        'file_name_hi',
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
        'file_path',
        'file_path_hi',
        'file_url',
        'file_url_hi',
        'is_published_desc',
        'is_approved_desc',
    ];

    // ===== RELATIONSHIPS =====

    public function informationCenter(): BelongsTo
    {
        return $this->belongsTo(InformationCenter::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ===== FILE PATH ACCESSORS =====



    public function getFilePathAttribute(): ?string
    {
        return $this->file_name ? base64_encode(Config::get('file_paths')['INFORMATION_CENTER_FILE_EN_PATH'] . '/' . $this->file_name) : null;
    }

    public function getFilePathHiAttribute(): ?string
    {
        return $this->file_name_hi ? base64_encode(Config::get('file_paths')['INFORMATION_CENTER_FILE_HI_PATH'] . '/' . $this->file_name_hi) : null;
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_name ? asset('storage/' . config('file_paths.INFORMATION_CENTER_FILE_EN_PATH') . '/' . $this->file_name) : null;
    }

    public function getFileUrlHiAttribute(): ?string
    {
        return $this->file_name_hi ? asset('storage/' . config('file_paths.INFORMATION_CENTER_FILE_HI_PATH') . '/' . $this->file_name_hi) : null;
    }

    // ===== WORKFLOW DESCRIPTIONS =====

    public function getIsPublishedDescAttribute(): string
    {
        return $this->is_published == 1 ? 'Published' : 'Draft';
    }

    public function getIsApprovedDescAttribute(): string
    {
        return match ($this->is_approved) {
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
            ->logOnly([
                'title',
                'title_hi',
                'type',
                'url',
                'file_name',
                'file_name_hi',
                'is_approved',
                'is_published',
                'remarks'
            ])
            ->setDescriptionForEvent(fn(string $eventName) => "InformationCenterDetail has been {$eventName}")
            ->useLogName('information_center_detail');
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
