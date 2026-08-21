<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = [

        'name',
        'holiday_date'

    ];

    public function isOnDate($date)
    {
        $checkDate = Carbon::parse($date);
        $month = (int) substr($this->holiday_date, 0, 2);
        $day = (int) substr($this->holiday_date, 3, 2);
        
        return $checkDate->month === $month && $checkDate->day === $day;
    }
}
