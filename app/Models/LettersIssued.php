<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class LettersIssued extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'letters_issued';

    protected $fillable = [
        'title',
        'title_hi',
        'type',
        'publish_date',
        'file_name',
        'file_name_hi',
        'is_approved',
        'is_published',
        'remarks',
        'publish_remark',
        'created_by',
        'updated_by',
        'direction_state_id',
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc', 'file_url', 'file_url_hi', 'file_path_en', 'file_path_hi'];


    public function getFileUrlAttribute()
    {
        return $this->file_name ? asset('storage/' . Config::get('file_paths')['LETTERS_ISSUED_FILE_EN_PATH'] . '/' . $this->file_name) : null;
    }

    public function getFileUrlHiAttribute()
    {
        return $this->file_name_hi ? asset('storage/' . Config::get('file_paths')['LETTERS_ISSUED_FILE_HI_PATH'] . '/' . $this->file_name_hi) : null;
    }

    public function getFilePathEnAttribute()
    {
        return $this->file_name ? base64_encode(Config::get('file_paths')['LETTERS_ISSUED_FILE_EN_PATH'] . '/' . $this->file_name) : null;
    }

    public function getFilePathHiAttribute()
    {
        return $this->file_name_hi ? base64_encode(Config::get('file_paths')['LETTERS_ISSUED_FILE_HI_PATH'] . '/' . $this->file_name_hi) : null;
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

    public function getStatesAttribute()
    {
        if (empty($this->direction_state_id)) {
            return collect([]);
        }
        $ids = explode(',', $this->direction_state_id);
        return DirectionState::whereIn('id', $ids)->get();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('letters_issued')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "LettersIssued model has been {$eventName}");
    }

    public static function getLastUpdatedOrCreatedAt($type = null)
    {
        $model = new static();
        $tableName = $model->getTable();

        $columns = Schema::getColumnListing($tableName);

        $query = DB::table($tableName)->whereNull('deleted_at');
        if (in_array('updated_at', $columns)) {
            $timestamp = $query->max('updated_at');
        } elseif (in_array('created_at', $columns)) {
            $timestamp = $query->max('created_at');
        } else {
            throw new \Exception("Table '{$tableName}' has no timestamps.");
        }
        return $timestamp
            ? Carbon::parse($timestamp)->format('d-m-Y H:i:s')
            : null;
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
