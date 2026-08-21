<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Adm_notif extends Model
{
    use HasFactory;

    protected $fillable = [
        'info',
        'notif_date',
        'admin_id'
    ];

    protected $casts = [
        'notif_date' => 'datetime'
    ];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }
}
