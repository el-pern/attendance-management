<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Instructor extends Model
{
    use HasFactory;
    protected $fillable = [
        'lname',
        'fname',
        'email',
        'address',
        'section_id'];

    public function section(){

        return $this->belongsTo(Section::class);
    }

}
