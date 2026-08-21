<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity_log extends Model
{
    use HasFactory;

    protected $fillable = [

        'id_no',
        'log_msg',
        'log_date'
    ];
}
