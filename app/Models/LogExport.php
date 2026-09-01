<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogExport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'export_type',
        'status',
        'file_path',
        'file_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
