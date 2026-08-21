<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    protected $fillable = [

        'name',
        'email',
        'student_id'

    ];

    public function student(){
        return $this->belongsTo(Student::class);
    }
}
