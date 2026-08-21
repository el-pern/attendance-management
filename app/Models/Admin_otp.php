<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin_otp extends Model
{
    protected $fillable = [
        
        'admin_id',
        'otp',
        'expires_at'

    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    public function isExpired(){
        return $this->expires_at < now();
    }
}
