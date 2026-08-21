<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Arcstudent extends Model
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

    public function drop(){
        return $this->hasOne(Drop::class, 'arcstudent_id');
    }

    public function guardian(){
        return $this->hasOne(Arcguardian::class, 'arcstudent_id');
    }
}
