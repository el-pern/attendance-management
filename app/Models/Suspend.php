<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suspend extends Model
{
    protected $fillable = [
        'suspend_date',
        'user_id',
        'grade_id',
        'subject_id'
    ];

    protected $casts = [
        'suspend_date' => 'datetime'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function grade(){
        return $this->belongsTo(Grade::class);
    }

    public function subject(){
        return $this->belongsTo(Subject::class);
    }


}
