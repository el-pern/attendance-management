<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    protected $fillable = [

        'name',
        'start_period',
        'end_period'

    ];

    public function includesDate($date)
    {
        $checkDate = Carbon::parse($date);
        $startMonth = (int) substr($this->start_period, 0, 2);
        $startDay = (int) substr($this->start_period, 3, 2);
        $endMonth = (int) substr($this->end_period, 0, 2);
        $endDay = (int) substr($this->end_period, 3, 2);
        
        $start = Carbon::create($checkDate->year, $startMonth, $startDay)->startOfDay();
        $end = Carbon::create($checkDate->year, $endMonth, $endDay)->endOfDay();
        
        // Handle year-spanning vacations (e.g., 12-20 to 01-05)
        if ($end->lt($start)) {
            $end->addYear();
        }
        
        return $checkDate->between($start, $end, true);
    }
}
