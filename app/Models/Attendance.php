<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [

        'student_id',
        'section_id',
        'subject_id',
        'status',
        'att_date'
    ];

    protected $casts = [
        'att_date' => 'datetime',
    ];

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function section(){
        return $this->belongsTo(Section::class);
    }

    public function subject(){
        return $this->belongsTo(Subject::class);
    }
}
