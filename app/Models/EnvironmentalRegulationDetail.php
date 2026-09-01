<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EnvironmentalRegulationDetail extends Model
{

    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'environmental_regulation_id',
        'parent_id',
        'order',
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




    protected $appends = ['is_approved_desc', 'is_published_desc', 'file_url', 'file_url_hi', 'file_path_en', 'file_path_hi'];


    public function getFileUrlAttribute()
    {
        return asset('storage/' . config('file_paths')['ENV_REGULATION_DETAIL_FILE_EN_PATH'] . '/' . $this->file_name);
    }
    public function getFileUrlHiAttribute()
    {
        return asset('storage/' . config('file_paths')['ENV_REGULATION_DETAIL_FILE_HI_PATH'] . '/' . $this->file_name_hi);
    }

    public function getFilePathEnAttribute()
    {
        return $this->file_name ? base64_encode(Config::get('file_paths')['ENV_REGULATION_DETAIL_FILE_EN_PATH'] . '/' . $this->file_name) : null;
    }
    public function getFilePathHiAttribute()
    {
        return $this->file_name_hi ? base64_encode(Config::get('file_paths')['ENV_REGULATION_DETAIL_FILE_HI_PATH'] . '/' . $this->file_name_hi) : null;
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

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(EnvironmentalRegulationDetail::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(EnvironmentalRegulationDetail::class, 'parent_id')->orderBy('order');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('environmental_regulation_details')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "EnvironmentalRegulationDetail model has been {$eventName}");
    }

    public function environmentalRegulation()
    {
        return $this->belongsTo(EnvironmentalRegulation::class);
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
