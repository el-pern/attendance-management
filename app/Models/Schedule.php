<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'start_time',
        'end_time',
        'subject_id',
        'section_id'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
    ];

    public function subject(){
        return $this->belongsTo(Subject::class);
    }

    public function section(){
        return $this->belongsTo(Section::class);
    }

    public function schedlimit(){
        return $this->hasOne(Schedlimit::class);
    }
}
