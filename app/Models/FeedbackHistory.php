<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FeedbackHistory extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'feedback_id',
        'revert_message',
        'responded_by',
        'email_sent',
        'email_sent_at',
    ];

    protected $casts = [
        'email_sent' => 'boolean',
        'email_sent_at' => 'datetime',
    ];

    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }

    public function respondedBy()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('feedback_histories')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Feedback history has been {$eventName}");
    }
}
