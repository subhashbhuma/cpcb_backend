<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LaboratoriesFile extends Model

{
    use HasFactory, SoftDeletes, LogsActivity;
    protected $table = 'laboratories_file';

    protected $fillable = [
        'page_id',
        'file_name',
        'file_name_hi',
        'title',
        'title_hi',
        'date',
        'type',
        'description',
        'description_hi',
        'created_by',
        'updated_by'
    ];

    protected $appends = ['file_path', 'file_path_hi'];

    public function getFilePathAttribute()
    {
        return $this->file_name ? asset('storage/' . Config::get('file_paths')['LABORATORIES_FILES_EN_PATH'] . '/' . $this->file_name)  : null;
    }

    public function getFilePathHiAttribute()
    {
        return $this->file_name_hi ? asset('storage/' . Config::get('file_paths')['LABORATORIES_FILES_HI_PATH'] . '/' . $this->file_name_hi) : null;
    }

    public function page()
    {
        return $this->belongsTo(LaboratoriesPage::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('labsPageFile')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Labs Page File model has been {$eventName}");
    }
}