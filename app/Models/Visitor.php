<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $table = 'visitors';

    protected $fillable = [
        'ip_address',
        'user_agent',
        'page_url',
        'session_id',
        'visit_date',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];
}
