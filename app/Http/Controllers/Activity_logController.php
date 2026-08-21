<?php

namespace App\Http\Controllers;

use App\Models\Activity_log;
use Illuminate\Http\Request;

class Activity_logController extends Controller
{

    public function logActivity(Request $request, $id_no, $log_msg){

        date_default_timezone_set('Asia/Manila');
        $curDate = date('Y-m-d h:i:s A');

        $inputs = [
            'id_no' => $id_no,
            'log_msg' => $log_msg,
            'log_date' => $curDate
        ];

        Activity_log::create($inputs);
    }
}

