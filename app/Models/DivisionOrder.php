<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DivisionOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_no',
        'title',
        'title_hi',
        'created_by',
        'updated_by',
    ];
}
