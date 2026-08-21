<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'grade_id'];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

}
