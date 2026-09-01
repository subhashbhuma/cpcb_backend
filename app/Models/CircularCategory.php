<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CircularCategory extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'name_hi',
        'created_by',
        'updated_by',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('circular_category')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Circular Category model has been {$eventName}");
    }
}
