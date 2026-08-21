<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allsuspend extends Model
{
    protected $fillable = [
        'suspend_date'
    ];

    protected $casts = [
        'suspend_date' => 'datetime'
    ];
}
