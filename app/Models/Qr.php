<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qr extends Model
{
    protected $fillable = [
        'qr_key',
        'student_id'
    ];

    public function student(){
        return $this->belongsTo(Student::class);
    }

}
