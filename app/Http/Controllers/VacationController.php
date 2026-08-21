<?php

namespace App\Http\Controllers;

use App\Models\Vacation;
use Illuminate\Http\Request;

class VacationController extends Controller
{
    public function viewVacations(){

        $vacations = Vacation::orderBy('start_period')->get();

        return view('Adm/Vacation/admvacation', compact('vacations'));

    }

    public function viewVacationForm(){
        return view('Adm/Vacation/addvacation');
    }

    public function addVacation(Request $request){

        $inputs = $request->validate([

            'name' => 'required',
            'start_month' => 'required',
            'start_day' => 'required',
            'end_month' => 'required',
            'end_day' => 'required'

        ]);

        $st_month = str_pad($inputs['start_month'], 2, '0', STR_PAD_LEFT);
        $st_day = str_pad($inputs['start_day'], 2, '0', STR_PAD_LEFT);
        $start_date = $st_month.'-'.$st_day;

        $end_month = str_pad($inputs['end_month'], 2, '0', STR_PAD_LEFT);
        $end_day = str_pad($inputs['end_day'], 2, '0', STR_PAD_LEFT);
        $end_date = $end_month.'-'.$end_day;

        $start_numeric = ($inputs['start_month'] * 100) + $inputs['start_day'];
        $end_numeric = ($inputs['end_month'] * 100) + $inputs['end_day'];
        
        if ($end_numeric == $start_numeric) {
            return back()->with('error', 'End period must not be equal to start period');
        }

        $existingName = Vacation::where('name', $inputs['name'])->first();
        if ($existingName) {
            return back()->with('error', 'A vacation period with this name already exists');
        }

        // Determine if new period spans year (e.g., Dec 25 to Jan 5)
        $new_spans_year = $end_numeric < $start_numeric;

        $allVacations = Vacation::all();
        foreach ($allVacations as $vacation) {
            // Parse existing vacation dates
            $existing_start_month = (int) substr($vacation->start_period, 0, 2);
            $existing_start_day = (int) substr($vacation->start_period, 3, 2);
            $existing_end_month = (int) substr($vacation->end_period, 0, 2);
            $existing_end_day = (int) substr($vacation->end_period, 3, 2);
            
            $existing_start = ($existing_start_month * 100) + $existing_start_day;
            $existing_end = ($existing_end_month * 100) + $existing_end_day;
            
            // Determine if existing period spans year
            $existing_spans_year = $existing_end < $existing_start;
            
            $overlaps = false;
            
            if (!$new_spans_year && !$existing_spans_year) {
                // Neither period spans year - simple comparison
                $overlaps = (
                    ($start_numeric >= $existing_start && $start_numeric <= $existing_end) ||
                    ($end_numeric >= $existing_start && $end_numeric <= $existing_end) ||
                    ($start_numeric <= $existing_start && $end_numeric >= $existing_end)
                );
            } elseif ($new_spans_year && !$existing_spans_year) {
                // New period spans year, existing doesn't
                // New period overlaps if existing is >= start OR <= end
                $overlaps = (
                    ($existing_start >= $start_numeric) || // Existing starts in Dec
                    ($existing_end <= $end_numeric) ||     // Existing ends in Jan
                    ($existing_start <= $end_numeric && $existing_end >= $start_numeric) // Existing contains part of new
                );
            } elseif (!$new_spans_year && $existing_spans_year) {
                // Existing period spans year, new doesn't
                // Overlaps if new is >= existing start OR <= existing end
                $overlaps = (
                    ($start_numeric >= $existing_start) || // New starts in Dec
                    ($end_numeric <= $existing_end) ||     // New ends in Jan
                    ($start_numeric <= $existing_end && $end_numeric >= $existing_start) // New contains part of existing
                );
            } else {
                // Both span year - they will always overlap
                $overlaps = true;
            }

                if ($overlaps) {
                    return back()->with('error', "This period overlaps with existing vacation: {$vacation->name} ({$vacation->start_period} to {$vacation->end_period})");
                }
        }

        Vacation::create([
            'name' => $inputs['name'],
            'start_period' => $start_date,
            'end_period' => $end_date
        ]);

        $logger = new Activity_logController();
        $message = auth()->guard('admin')->user()->email."added vacation named {$request->name}
        set for {$start_date} to {$end_date}.";

        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;

        $logger->logActivity($request, $repnotif_id, $message);

        return redirect('/admin/vacations')->with('success', 'Break period added');

    }

    public function editVacation(Vacation $vacation){

        return view('Adm/Vacation/editvacation', compact('vacation'));

    }

    public function updVacation(Vacation $vacation, Request $request){
        
        $oldName = $vacation->name;
        $oldStDate = $vacation->start_period;
        $oldEndDate = $vacation->end_period;

        $inputs = $request->validate([

            'name' => 'required',
            'start_month' => 'required',
            'start_day' => 'required',
            'end_month' => 'required',
            'end_day' => 'required'

        ]);

        $st_month = str_pad($inputs['start_month'], 2, '0', STR_PAD_LEFT);
        $st_day = str_pad($inputs['start_day'], 2, '0', STR_PAD_LEFT);
        $start_date = $st_month.'-'.$st_day;

        $end_month = str_pad($inputs['end_month'], 2, '0', STR_PAD_LEFT);
        $end_day = str_pad($inputs['end_day'], 2, '0', STR_PAD_LEFT);
        $end_date = $end_month.'-'.$end_day;

        $start_numeric = ($inputs['start_month'] * 100) + $inputs['start_day'];
        $end_numeric = ($inputs['end_month'] * 100) + $inputs['end_day'];
        
        if ($end_numeric == $start_numeric) {
            return back()->with('error', 'End period must not be equal to start period');
        }

        // Determine if new period spans year (e.g., Dec 25 to Jan 5)
        $new_spans_year = $end_numeric < $start_numeric;


        $existingName = Vacation::where('name', $inputs['name'])
        ->where('id', '!=', $vacation->id)
        ->first();
        if ($existingName) {
            return back()->with('error', 'A vacation period with this name already exists');
        }

        $allVacations = Vacation::where('id', '!=', $vacation->id)->get();
        foreach ($allVacations as $existing_vacation) {
            // Parse existing vacation dates
            $existing_start_month = (int) substr($existing_vacation->start_period, 0, 2);
            $existing_start_day = (int) substr($existing_vacation->start_period, 3, 2);
            $existing_end_month = (int) substr($existing_vacation->end_period, 0, 2);
            $existing_end_day = (int) substr($existing_vacation->end_period, 3, 2);
            
            $existing_start = ($existing_start_month * 100) + $existing_start_day;
            $existing_end = ($existing_end_month * 100) + $existing_end_day;


            // Determine if existing period spans year
            $existing_spans_year = $existing_end < $existing_start;
            
            $overlaps = false;
            
            if (!$new_spans_year && !$existing_spans_year) {
                // Neither period spans year - simple comparison
                $overlaps = (
                    ($start_numeric >= $existing_start && $start_numeric <= $existing_end) ||
                    ($end_numeric >= $existing_start && $end_numeric <= $existing_end) ||
                    ($start_numeric <= $existing_start && $end_numeric >= $existing_end)
                );
            } elseif ($new_spans_year && !$existing_spans_year) {
                // New period spans year, existing doesn't
                // New period overlaps if existing is >= start OR <= end
                $overlaps = (
                    ($existing_start >= $start_numeric) || // Existing starts in Dec
                    ($existing_end <= $end_numeric) ||     // Existing ends in Jan
                    ($existing_start <= $end_numeric && $existing_end >= $start_numeric) // Existing contains part of new
                );
            } elseif (!$new_spans_year && $existing_spans_year) {
                // Existing period spans year, new doesn't
                // Overlaps if new is >= existing start OR <= existing end
                $overlaps = (
                    ($start_numeric >= $existing_start) || // New starts in Dec
                    ($end_numeric <= $existing_end) ||     // New ends in Jan
                    ($start_numeric <= $existing_end && $end_numeric >= $existing_start) // New contains part of existing
                );
            } else {
                // Both span year - they will always overlap
                $overlaps = true;
            }

                if ($overlaps) {
                    return back()->with('error', "This period overlaps with existing vacation: {$existing_vacation->name} ({$existing_vacation->start_period} to {$existing_vacation->end_period})");
                }
            }

        if($oldName !== $inputs['name'] || $oldStDate !== $start_date
        || $oldEndDate !== $end_date){

            $vacation->name = $inputs['name'];
            $vacation->start_period = $start_date;
            $vacation->end_period = $end_date;
            $vacation->save();

            $logger = new Activity_logController();
            $message = "Admin ".auth()->guard('admin')->user()->email." updated vacation from ".$oldName." to ".$request->name.
            ", set for ".$start_date." to ".$end_date;

            //generates random notif id
            $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $notif_randnum = "";
            for($i = 0; $i < 6; $i++){
                $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
            }
            //generated notif id
            $repnotif_id = $notif_randnum;
            $logger->logActivity($request, $repnotif_id, $message);

            return redirect('/admin/vacations')->with('success', 'Break period edited');


        }else{
            return back()->with('info', 'No changes were made');
        }

    }

    public function deleteVacation(Vacation $vacation, Request $request){


        $vacation->delete();
        $logger = new Activity_logController();
        $message = "Admin ".auth()->guard('admin')->user()->email." deleted period named ".$vacation->name
        ." set for ".$vacation->start_period." to ".$vacation->end_period;

        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;
        $logger->logActivity($request, $repnotif_id, $message);

        return back()->with('success', 'Break period deleted');

    }

}
