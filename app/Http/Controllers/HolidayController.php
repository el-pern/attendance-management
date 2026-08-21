<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function viewHolidays(){

        $holidays = Holiday::orderBy('holiday_date')->get();

        return view('Adm/Holiday/admholiday', compact('holidays'));

    }

    public function viewHolidayForm(){
        return view('Adm/Holiday/addholiday');
    }

    public function addHoliday(Request $request){

        $inputs = $request->validate([
            'name' => 'required',
            'hol_month' => 'required',
            'hol_day' => 'required'
        ]);

        $month = str_pad($inputs['hol_month'], 2, '0', STR_PAD_LEFT);
        $day = str_pad($inputs['hol_day'], 2, '0', STR_PAD_LEFT);
    
        $holiday_date = $month . '-' . $day;

        $existingHolidayName = Holiday::where('name', $inputs['name'])->first();
        $existingHolidayDate = Holiday::where('holiday_date', $holiday_date)->first();

        if($existingHolidayName && $existingHolidayDate){
            return redirect('/admin/addholiday')->with('error', 'Holiday already exists');
        }elseif($existingHolidayDate){
            return redirect('/admin/addholiday')->with('error', 'Holiday with this date already exists');
        }elseif($existingHolidayName){
            return redirect('/admin/addholiday')->with('error', 'Holiday with this name already exists');
        }

        Holiday::create([
            'name' => $inputs['name'],
            'holiday_date' => $holiday_date
        ]);

        $logger = new Activity_logController();
        $message = auth()->guard('admin')->user()->email." added holiday named {$request->name} set for {$holiday_date}.";

        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;

        $logger->logActivity($request, $repnotif_id, $message);

        return redirect('/admin/holidays')->with('success', 'Holiday added');

    }

    public function editHoliday(Holiday $holiday){

        return view('Adm/Holiday/editholiday', compact('holiday'));

    }

    public function updHoliday(Holiday $holiday, Request $request){

        $oldName = $holiday->name;
        $oldDate = $holiday->holiday_date;

        $inputs = $request->validate([
            'name' => 'required',
            'hol_month' => 'required',
            'hol_day' => 'required'
        ]);

        $month = str_pad($inputs['hol_month'], 2, '0', STR_PAD_LEFT);
        $day = str_pad($inputs['hol_day'], 2, '0', STR_PAD_LEFT);
    
        $holiday_date = $month . '-' . $day;

        $existingHolidayName = Holiday::where('name', $inputs['name'])
        ->where('id', '!=', $holiday->id)
        ->first();
        
    $existingHolidayDate = Holiday::where('holiday_date', $holiday_date)
        ->where('id', '!=', $holiday->id)
        ->first();
    
        if($existingHolidayName && $existingHolidayDate){
            return back()->with('error', 'Holiday already exists');
        } elseif($existingHolidayName){
            return back()->with('error', 'Holiday with this name already exists');
        } elseif($existingHolidayDate){
            return back()->with('error', 'Holiday with this date already exists');
        }

        if($oldName !== $inputs['name'] || $oldDate !== $holiday_date){

            $holiday->name = $inputs['name'];
            $holiday->holiday_date = $holiday_date;
            $holiday->save();

            $logger = new Activity_logController();
            $message = "Admin ".auth()->guard('admin')->user()->email." updated holiday from ".$oldName." to ".$request->name.
            ", set for ".$holiday_date.", formerly ".$oldDate;

            //generates random notif id
            $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $notif_randnum = "";
            for($i = 0; $i < 6; $i++){
                $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
            }
            //generated notif id
            $repnotif_id = $notif_randnum;
            $logger->logActivity($request, $repnotif_id, $message);

            return redirect('/admin/holidays')->with('success', 'Holiday edited');

        }else{
            return back()->with('info', 'No changes were made');
        }



    }


    public function delHoliday(Holiday $holiday, Request $request){

        $holiday->delete();

        $logger = new Activity_logController();
        $message = "Admin ".auth()->guard('admin')->user()->email." deleted holiday named ".$holiday->name
        ." set at ".$holiday->holiday_date;

        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;
        $logger->logActivity($request, $repnotif_id, $message);

        return back()->with('success', 'Holiday deleted');

    }


}
