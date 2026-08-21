<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arcguardian extends Model
{
    protected $fillable = [

        'name',
        'email',
        'arcstudent_id'

    ];

    public function arcstud(){
        return $this->belongsTo(Arcstudent::class);
    }
}
