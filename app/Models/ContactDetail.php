<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
class ContactDetail extends Model
{
    use SoftDeletes, LogsActivity;


    protected $fillable = [
        'title',
        'title_hi',
        'department',
        'department_hi',
        'address',
        'address_hi',
        'phone_numbers',
        'email_ids',
        'profile_image',
        'myorder',
        'is_approved',
        'is_published',
        'remarks',
        'publish_remark',
        'created_by',
        'updated_by',
    ];

    protected $appends = ['is_approved_desc', 'is_published_desc', 'profile_image_url'];

    public function getProfileImageUrlAttribute()
    {

        return $this->profile_image
            ? getFileMeta('storage/' . Config::get('file_paths')['CONTACT_DETAIL_PROFILE_IMAGE_PATH'] . '/' . $this->profile_image)['exists']
            ? base64_encode(Config::get('file_paths')['CONTACT_DETAIL_PROFILE_IMAGE_PATH'] . '/' . $this->profile_image)
            : null
            : null;


        // return getFileMeta(asset('storage/' . Config::get('file_paths')['CONTACT_DETAIL_PROFILE_IMAGE_PATH'] . '/' . $this->profile_image))['exists'] ? base64_encode(Config::get('file_paths')['CONTACT_DETAIL_PROFILE_IMAGE_PATH'] . '/' . $this->profile_image) : null;
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


    public static function getLastUpdatedOrCreatedAt()
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
            return null;
        }

        return $timestamp ? Carbon::parse($timestamp)->format('d-m-Y H:i:s') : null;
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('announcement')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Announcement model has been {$eventName}");
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
