<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class TenderCorrigendum extends Model
{
    use SoftDeletes, LogsActivity;
    protected $guarded = [];

    protected $appends = ['file_path', 'file_path_hi', 'public_file_path_en', 'public_file_path_hi'];

    public function getFilePathAttribute()
    {
        return $this->file_name ? asset('storage/' . Config::get('file_paths')['TENDER_CORRIGENDUM_FILE_EN_PATH'] . '/' . $this->file_name)  : null;
    }

    public function getFilePathHiAttribute()
    {
        return $this->file_name_hi ? asset('storage/' . Config::get('file_paths')['TENDER_CORRIGENDUM_FILE_HI_PATH'] . '/' . $this->file_name_hi) : null;
    }


    public function getPublicFilePathEnAttribute()
    {
        return $this->file_name ? base64_encode(Config::get('file_paths')['TENDER_CORRIGENDUM_FILE_EN_PATH'] . '/' . $this->file_name) : null;
    }
    public function getPublicFilePathHiAttribute()
    {
        return $this->file_name_hi ? base64_encode(Config::get('file_paths')['TENDER_CORRIGENDUM_FILE_HI_PATH'] . '/' . $this->file_name_hi) : null;
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('tender_corrigendums')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "TenderCorrigendum model has been {$eventName}");
    }
}
