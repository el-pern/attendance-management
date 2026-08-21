<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{

    use HasFactory;
    protected $fillable = [
        'lname',
        'fname',
        'email',
        'address',
        'student_id',
        'section_id'];

    public function section(){

        return $this->belongsTo(Section::class);
    }

    public function guardian(){
        return $this->hasOne(Guardian::class, 'student_id');
    }

    public function qr(){
        return $this->hasOne(Qr::class, 'student_id');
    }
}

