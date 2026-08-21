<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedlimit extends Model
{
    protected $fillable = [
        'late_time',
        'absent_time',
        'schedule_id'
    ];

    protected $casts = [
        'late_time' => 'datetime:H:i:s',
        'absent_time' => 'datetime:H:i:s',
    ];

    public function schedule(){
        $this->belongsTo(Schedule::class);
    }
}
