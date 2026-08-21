<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drop extends Model
{
    protected $fillable = [
        'arcstudent_id'
    ];

    public function arcstudent(){
        return $this->belongsTo(Arcstudent::class);
    }
}
