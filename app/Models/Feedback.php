<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Config;

class Feedback extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'feedbacks';

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'message',
        'file_name',
        'status',
    ];

    protected $appends = ['status_badge','file_full_path'];

    public function getFileFullPathAttribute()
    {
        return asset('storage/' . Config::get('file_paths')['FEEDBACK_FILE_PATH'] . '/' . $this->file_name);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'in_progress' => '<span class="badge bg-info">In Progress</span>',
            'resolved' => '<span class="badge bg-success">Resolved</span>',
            'closed' => '<span class="badge bg-secondary">Closed</span>',
        ];
        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }



    public function histories()
    {
        return $this->hasMany(FeedbackHistory::class)->orderBy('created_at', 'desc');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('feedbacks')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Feedback has been {$eventName}");
    }
}
