<?php

namespace App\Http\Controllers;

use App\Models\Qr;
use App\Models\Drop;
use App\Models\User;
use App\Models\Admin;
use App\Models\Grade;
use App\Models\Shift;
use App\Models\Holiday;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Suspend;
use App\Models\Inst_sub;
use App\Models\Schedule;
use App\Models\Vacation;
use App\Mail\InstreqMail;
use App\Models\Adm_notif;
use App\Models\Classhide;
use App\Models\Allsuspend;
use App\Models\Arcstudent;
use App\Models\Attendance;
use App\Models\Instructor;
use App\Models\Schedlimit;
use Illuminate\Http\Request;
use App\Mail\GuardianAbsMail;
use Illuminate\Validation\Rule;
use App\Exports\AttendanceExport;
use App\Imports\AttendanceImport;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class InstructorController extends Controller
{

    public function viewSetupForm(){

        $sections = Section::orderBy('grade_id')->orderBy('name')->get();
        $grades = Grade::get();

        return view('User/addinstructor', compact('sections', 'grades'));
    }

    public function home(){

    $user = auth()->user();
    $instructor = Instructor::where('email', $user->email)->first();

    // Get instructor's shift
    $shift = Classhide::where('user_id', $user->id)->first();
    
    // Get instructor's subject
    $inst_sub = Inst_sub::where('user_id', $user->id)->first();
    
    // Get sections in the same grade level
    $sections = Section::where('grade_id', $instructor->section->grade_id)
        ->orderBy('name')
        ->get();

    // Get schedules for filtering by shift
    $all_schedules = Schedule::where('subject_id', $inst_sub->subject_id)
        ->whereIn('section_id', $sections->pluck('id'))
        ->with('schedlimit', 'section')
        ->get();

    $suspend = Suspend::
        where('user_id', auth()->user()->id)
        ->where('suspend_date', now()->format('Y-m-d'))
        ->first();

    $allsuspend = Allsuspend::where('suspend_date', now()->format('Y-m-d'))->first();

    $holidays = Holiday::where('holiday_date', now()->format('m-d'))->first();

    $today = now();
    $vacations = Vacation::all()->first(function($vac) use ($today) {
        return $vac->includesDate($today);
    });

    $morningEnd = \Carbon\Carbon::parse('12:00:00');
    $afternoonStart = \Carbon\Carbon::parse('12:00:00');

    // Filter sections based on shift
    $visibleSections = collect();
    if($shift && $shift->shift){
        $shiftName = $shift->shift->inst_shift;
        
        foreach($sections as $section){
            $schedule = $all_schedules->where('section_id', $section->id)->first();
            
            if(!$schedule){
                // Include sections without schedules
                $visibleSections->push($section);
                continue;
            }
            
            $scheduleStart = \Carbon\Carbon::parse($schedule->start_time->format('H:i:s'));
            
            if($shiftName === 'Morning'){
                if($scheduleStart->lessThan($morningEnd)){
                    $visibleSections->push($section);
                }
            } elseif($shiftName === 'Afternoon'){
                if($scheduleStart->greaterThanOrEqualTo($afternoonStart)){
                    $visibleSections->push($section);
                }
            } else {
                // Whole Day - show all
                $visibleSections->push($section);
            }
        }
    } else {
        $visibleSections = $sections;
    }

    return view('User/home', compact(
        'user',
        'instructor',
        'suspend',
        'allsuspend',
        'holidays',
        'vacations',
        'visibleSections',
        'inst_sub',
        'shift'));
}

// Add this new method for AJAX requests
public function getAttendanceData(Request $request){
    
    $request->validate([
        'section_id' => 'required|exists:sections,id',
        'date' => 'required|date',
        'period' => 'required|in:daily,weekly,monthly'
    ]);

    $user = auth()->user();
    $inst_sub = Inst_sub::where('user_id', $user->id)->first();
    $date = \Carbon\Carbon::parse($request->date);
    $section = Section::find($request->section_id);
    $period = $request->period;

    // Get students in the section
    $students = Student::where('section_id', $request->section_id)
        ->orderBy('lname')
        ->orderBy('fname')
        ->get();

    // Determine date range based on period
    switch($period) {
        case 'daily':
            $startDate = $date->copy()->startOfDay();
            $endDate = $date->copy()->endOfDay();
            break;
        case 'weekly':
            // Get Monday to Friday only
            $startDate = $date->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
            $endDate = $date->copy()->startOfWeek(\Carbon\Carbon::MONDAY)->addDays(4); // Monday + 4 days = Friday
            break;
        case 'monthly':
            $startDate = $date->copy()->startOfMonth();
            $endDate = $date->copy()->endOfMonth();
            break;
    }

    // Get attendance for the selected date (for daily view)
    $attendances = Attendance::where('section_id', $request->section_id)
        ->where('subject_id', $inst_sub->subject_id)
        ->whereDate('att_date', $date)
        ->with('student')
        ->get();

    // Get attendance for the period (for statistics)
    $periodAttendances = Attendance::where('section_id', $request->section_id)
        ->where('subject_id', $inst_sub->subject_id)
        ->whereBetween('att_date', [$startDate, $endDate])
        ->get();

    // For weekly and monthly, filter out weekends from attendance data
    if($period === 'weekly' || $period === 'monthly') {
        $periodAttendances = $periodAttendances->filter(function($attendance) {
            $dayOfWeek = \Carbon\Carbon::parse($attendance->att_date)->dayOfWeek;
            // 0 = Sunday, 6 = Saturday
            return $dayOfWeek !== 0 && $dayOfWeek !== 6;
        });
    }

    // Calculate statistics for daily view
    $totalStudents = $students->count();
    $presentCount = $attendances->where('status', 'Present')->count();
    $lateCount = $attendances->where('status', 'Late')->count();
    $absentCount = $attendances->where('status', 'Absent')->count();
    $notMarked = $totalStudents - $attendances->count();

    // Calculate active days in period (excluding weekends)
    $uniqueDates = $periodAttendances->pluck('att_date')->map(function($date){
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    })->unique();

    // Filter out weekends from unique dates
    $weekdayDates = $uniqueDates->filter(function($dateStr) {
        $dayOfWeek = \Carbon\Carbon::parse($dateStr)->dayOfWeek;
        return $dayOfWeek !== 0 && $dayOfWeek !== 6;
    });

    $totalDays = $weekdayDates->count();

    // Calculate period statistics
    $periodPresentCount = $periodAttendances->whereIn('status', ['Present', 'Late'])->count();
    $attendanceRate = $totalDays > 0 ? round(($periodPresentCount / ($totalStudents * $totalDays)) * 100, 1) : 0;

    // Punctuality rate (on-time arrivals)
    $punctualityRate = $totalDays > 0 ? round(($periodAttendances->where('status', 'Present')->count() / ($totalStudents * $totalDays)) * 100) : 0;

    // Period totals
    $periodLateCount = $periodAttendances->where('status', 'Late')->count();
    $periodAbsentCount = $periodAttendances->where('status', 'Absent')->count();

    // Students with frequent absences (3+ days in period)
    $frequentAbsences = [];
    foreach($students as $student){
        $absences = $periodAttendances->where('student_id', $student->id)
            ->where('status', 'Absent')
            ->count();
        if($absences >= 3){
            $frequentAbsences[] = [
                'name' => $student->fname . ' ' . $student->lname,
                'absences' => $absences
            ];
        }
    }

    // Students with low punctuality (<80%)
    $lowPunctuality = [];
    foreach($students as $student){
        $studentAttendances = $periodAttendances->where('student_id', $student->id);
        $studentPresent = $studentAttendances->where('status', 'Present')->count();
        $studentTotal = $studentAttendances->count();
        
        if($studentTotal > 0){
            $punctuality = round(($studentPresent / $studentTotal) * 100);
            if($punctuality < 80){
                $lowPunctuality[] = [
                    'name' => $student->fname . ' ' . $student->lname,
                    'present' => $studentPresent,
                    'late' => $studentAttendances->where('status', 'Late')->count(),
                    'punctuality' => $punctuality
                ];
            }
        }
    }

    // Get dropped students
    $droppedStudents = Arcstudent::where('section_id', $request->section_id)
        ->with('drop')
        ->whereHas('drop')
        ->orderBy('lname')
        ->orderBy('fname')
        ->get();

    // Format period display text
    $periodText = '';
    switch($period) {
        case 'daily':
            $periodText = $date->format('F d, Y');
            break;
        case 'weekly':
            // Show Monday to Friday
            $monday = $date->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
            $friday = $monday->copy()->addDays(4);
            $periodText = $monday->format('M d') . ' - ' . $friday->format('M d, Y');
            break;
        case 'monthly':
            $periodText = $date->format('F Y');
            break;
    }

    return response()->json([
        'section' => $section,
        'date' => $date->format('F d, Y'),
        'period' => $period,
        'periodText' => $periodText,
        'dateRange' => [
            'start' => $startDate->format('Y-m-d'),
            'end' => $endDate->format('Y-m-d')
        ],
        'chart' => [
            'present' => $presentCount,
            'late' => $lateCount,
            'absent' => $absentCount,
            'notMarked' => $notMarked
        ],
        'analytics' => [
            'attendanceRate' => $attendanceRate,
            'punctualityRate' => $punctualityRate,
            'totalLate' => $periodLateCount,
            'totalAbsences' => $periodAbsentCount,
            'activeDays' => $totalDays
        ],
        'frequentAbsences' => $frequentAbsences,
        'lowPunctuality' => $lowPunctuality,
        'droppedStudents' => $droppedStudents,
        'students' => $students->map(function($student) use ($attendances){
            $attendance = $attendances->where('student_id', $student->id)->first();
            return [
                'id' => $student->id,
                'student_id' => $student->student_id,
                'name' => $student->fname . ' ' . $student->lname,
                'status' => $attendance ? $attendance->status : 'Not Marked'
            ];
        })
    ]);
}

    public function addInstructor(Request $request){

        $user_name = explode(' ', auth()->user()->name);
        
        if (count($user_name) > 1) {
            $inst_lname = array_pop($user_name); // Removes and gets last word
            $inst_fname = implode(' ', $user_name); // Gets remaining words
        } else {
            $inst_fname = $user_name[0];
            $inst_lname = '';
        } //handles users with no last name

        $instructor = $request->validate([
            'address' => ['required', 'min:1', 'max:100'],
            'section_id' => ['required', 'unique:instructors,section_id']
        ]);

        Instructor::create([

            'lname' => $inst_lname,
            'fname' => $inst_fname,
            'email' => auth()->user()->email,
            'address' => $instructor['address'],
            'section_id' => $instructor['section_id']

        ]
        );

        $logger = new Activity_logController();
        $message = "User ".auth()->user()->email." completed account setup";

        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;

        $logger->logActivity($request, $repnotif_id, $message);

        return redirect('/')->with('success', 'Account setup complete');
    }

    //attendance management

    public function viewAttendance(){

        $inst_subs = Inst_sub::where('user_id', auth()->user()->id)->first();

        $instructors = Instructor::where('email', auth()->user()->email)->first();

        $sections = Section::where('grade_id', $instructors->section->grade_id)
        ->orderBy('name')->get();

        $all_schedules = Schedule::where('subject_id', $inst_subs->subject_id)
        ->whereIn('section_id', $sections->pluck('id'))
        ->with('schedlimit', 'section')
        ->get();

        $suspend = Suspend::
        where('user_id', auth()->user()->id)
        ->where('suspend_date', now()->format('Y-m-d'))
        ->first();

        $allsuspend = Allsuspend::where('suspend_date', now()->format('Y-m-d'))->first();

        $morningStart = \Carbon\Carbon::parse('06:00:00');
        $morningEnd = \Carbon\Carbon::parse('12:00:00');
        $afternoonStart = \Carbon\Carbon::parse('12:00:00');
        $afternoonEnd = \Carbon\Carbon::parse('18:00:00');

        $shifts = Shift::all();

        $hides = Classhide::with('shift')
        ->where('user_id', auth()->user()->id)
        ->first();

        $schedules = $all_schedules;
        if($hides && $hides->shift){
            $shiftName = $hides->shift->inst_shift;
        
            $schedules = $all_schedules->filter(function($schedule) use ($shiftName, $morningEnd, $afternoonStart) {
            $scheduleStart = \Carbon\Carbon::parse($schedule->start_time->format('H:i:s'));
            $scheduleEnd = \Carbon\Carbon::parse($schedule->end_time->format('H:i:s'));
            
            if($shiftName === 'Morning'){
                // Only show classes that start before 12 PM
                return $scheduleStart->lessThan($morningEnd);
            } elseif($shiftName === 'Afternoon'){
                // Only show classes that end after 12 PM
                return $scheduleEnd->greaterThan($afternoonStart);
            }
            // Whole Day shift shows all classes
            return true;
            });
        }

        // Get section IDs that have schedules (filtered by shift)
        $sectionsWithSchedules = $schedules->pluck('section_id')->toArray();
    
        // Get section IDs that don't have any schedule at all
        $sectionsWithoutSchedules = $sections->pluck('id')->diff($all_schedules->pluck('section_id'))->toArray();

        // Sections to display: those without schedules OR those with schedules within shift
        $visibleSectionIds = array_merge($sectionsWithSchedules, $sectionsWithoutSchedules);

        $holidays = Holiday::where('holiday_date', now()->format('m-d'))->first();

        $today = now();
        $vacations = Vacation::all()->first(function($vac) use ($today) {
            return $vac->includesDate($today);
        });

        $todayAttendance = Attendance::where('subject_id', $inst_subs->subject_id)
        ->whereDate('attendances.att_date', today())
        ->whereIn('attendances.section_id', $sections->pluck('id'))
        ->join('students', 'attendances.student_id', '=', 'students.id')
        ->orderBy('students.lname')
        ->orderBy('students.fname')
        ->select('attendances.*')
        ->with(['student', 'section'])
        ->get();

        //students in each section
        $students = Student::with(['guardian', 'qr'])
        ->whereIn('section_id', $sections->pluck('id'))
        ->orderBy('lname')
        ->orderBy('fname')
        ->get()
        ->groupBy('section_id');

        $arcstuds = Arcstudent::with('drop')
        ->whereIn('section_id', $sections->pluck('id'))
        ->orderBy('lname')
        ->orderBy('fname')
        ->get()
        ->groupBy('section_id');

        $drops = Drop::whereHas('arcstudent', function($query) use ($sections) {
            $query->whereIn('section_id', $sections->pluck('id'));
        })
        ->with('arcstudent')
        ->get()
        ->groupBy('arcstudent.section_id');

        $attendanceBySection = $todayAttendance->groupBy('section_id');
        $subject = Subject::find($inst_subs->subject_id);

        return view('User/attendance', compact(
            'inst_subs',
            'instructors',
            'sections',
            'suspend',
            'allsuspend',
            'schedules',
            'shifts',
            'hides',
            'holidays',
            'vacations',
            'todayAttendance',
            'students',
            'arcstuds',
            'drops',
            'attendanceBySection',
            'subject',
            'visibleSectionIds'));
    }

    public function setShift(Request $request){

        $inputs = $request->validate([
            'shift_id' => 'required'
        ]);

        $exist_hide = Classhide::where('user_id', auth()->user()->id)->first();

        if($exist_hide){

            if($exist_hide->shift_id != $inputs['shift_id']){

            $exist_hide->shift_id = $inputs['shift_id'];
            $exist_hide->save();
            $classhide = $exist_hide;
            }else{
                return back()->with('info', 'Shift is already set as '.$exist_hide->shift->inst_shift);
            }

        }else{
        $classhide = Classhide::create([

            'user_id' => auth()->user()->id,
            'shift_id' => $inputs['shift_id']
            
        ]);}

        $logger = new Activity_logController();
        $message = "Instructor ".auth()->user()->email." set their shift to ".$classhide->shift->inst_shift;
        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;
        $logger->logActivity($request, $repnotif_id, $message);
        return redirect('/checkattendance')->with('success', 'Shift has been set');

    }

    public function addAttendance(Request $request){

        $attendance_inp = $request->validate([
            'student_id' => 'nullable|string',
            'qr_key' => 'required'
        ]);
        
        $shift = Classhide::where('user_id', auth()->user()->id)->first();

        $suspend = Suspend::
        where('user_id', auth()->user()->id)
        ->where('suspend_date', now()->format('Y-m-d'))
        ->first();

        $allsuspend = Allsuspend::where('suspend_date', now()->format('Y-m-d'))->first();

        if(!$shift){
            return redirect('/checkattendance')->with('error', 'Shift must be set first');
        }elseif($suspend){
            return redirect('/checkattendance')->with('error', 'Classes you handle are suspended today');
        }elseif($allsuspend){
            return redirect('/checkattendance')->with('error', 'All classes are suspended today');
        }

        $today = now();
        $todayFormatted = $today->format('m-d'); // Format: MM-DD
        
        $isHoliday = Holiday::all()->contains(function($holiday) use ($today) {
            return $holiday->isOnDate($today);
        });

        if($isHoliday){
            return redirect('/checkattendance')->with('error', 'No classes during a holiday');
        }

        // Check if today is within vacation period
        $isVacation = Vacation::all()->contains(function($vacation) use ($today) {
            return $vacation->includesDate($today);
        });

        
        if($isVacation){
            return redirect('/checkattendance')->with('error', 'No classes during a break period');
        }

        $inst_sub = Inst_sub::where('user_id', auth()->user()->id)->first();

        $instructor = Instructor::where('email', auth()->user()->email)->first();
            
        $qr = Qr::where('qr_key', $attendance_inp['qr_key'])->first();
        
        if(!$qr){
            return redirect('/checkattendance')->with('error', 'Invalid QR code');
        }
        
        $student = Student::find($qr->student_id);

        if(!$student){
            return redirect('/checkattendance')->with('error', 'Student not found for this QR code');
        }


        if($request->filled('student_id') && $student->student_id !== $attendance_inp['student_id']){
            //checks if a qr key matches the id number
            return redirect('/checkattendance')->with('error', 'Invalid QR key');
        }elseif($instructor->section->grade_id !== $student->section->grade_id){
            return redirect('/checkattendance')->with('error', 'Student number not in the same grade level');
        }elseif(now()->format('w') == 0 || now()->format('w') == 6){
            return redirect('/checkattendance')->with('error', 'No classes during weekends');
        }

        $existingAttendance = Attendance::where('student_id', $student->id)
        ->where('subject_id', $inst_sub->subject_id)
        ->whereDate('att_date', today())
        ->first();

        if($existingAttendance){
            return redirect('/checkattendance')->with('error', 'Attendance already recorded for this student today');
        }
        
        $schedule = Schedule::where('section_id', $student->section_id)
        ->where('subject_id', $inst_sub->subject_id)
        ->with('schedlimit')
        ->first();

        $morningStart = \Carbon\Carbon::parse('06:00:00');
        $morningEnd = \Carbon\Carbon::parse('12:00:00');
        $afternoonStart = \Carbon\Carbon::parse('12:00:00');
        $afternoonEnd = \Carbon\Carbon::parse('18:00:00');

        $status = 'Present';
        
        $curr_time = now();

        
        if($schedule && $schedule->schedlimit){
            $schedlim = $schedule->schedlimit;
            
            // Get today's date with the schedule times
            $startTime = \Carbon\Carbon::parse(today()->format('Y-m-d') . ' ' . $schedule->start_time->format('H:i:s'));
            $endTime = \Carbon\Carbon::parse(today()->format('Y-m-d') . ' ' . $schedule->end_time->format('H:i:s'));

            $shift_name = $shift->shift->inst_shift;

            $scheduleStart = \Carbon\Carbon::parse($schedule->start_time->format('H:i:s'));
            $scheduleEnd = \Carbon\Carbon::parse($schedule->end_time->format('H:i:s'));

            if($shift_name === 'Morning'){

                if($scheduleStart->greaterThan($morningEnd)
                    && $scheduleStart->greaterThanOrEqualTo($afternoonStart)
                    && $scheduleStart->lessThanOrEqualTo($afternoonEnd)){
                    return redirect('/checkattendance')->with('error', 'Class schedule outside of morning hours');
                }

            }elseif($shift_name === 'Afternoon'){

                if($scheduleStart->lessThan($afternoonStart)
                    && $scheduleStart->greaterThanOrEqualTo($morningStart)
                    && $scheduleStart->lessThanOrEqualTo($morningEnd)){
                    return redirect('/checkattendance')->with('error', 'Class schedule outside of afternoon hours');
                }

            }

            $lateTime = \Carbon\Carbon::parse(today()->format('Y-m-d') . ' ' . $schedlim->late_time->format('H:i:s'));
            $absentTime = \Carbon\Carbon::parse(today()->format('Y-m-d') . ' ' . $schedlim->absent_time->format('H:i:s'));
            
            // Determine status based on current time
            if($curr_time->greaterThanOrEqualTo($absentTime) && $curr_time->lessThanOrEqualTo($endTime)){
                $status = 'Absent';
            } elseif($curr_time->greaterThanOrEqualTo($lateTime) && $curr_time->lessThan($absentTime)){
                $status = 'Late';
            } elseif($curr_time->lessThan($lateTime) && $curr_time->greaterThanOrEqualTo($startTime)) {
                $status = 'Present';
            } else {
                return back()->with('error', 'Student not yet scheduled for attendance');
            }
        }

        try{
            Attendance::create(
            [
                'student_id' => $student->id,
                'section_id' => $student->section_id,
                'subject_id' => $inst_sub->subject_id,
                'status' => $status,
                'att_date' => now()
            ]
            );


            $logger = new Activity_logController();
            $message = "Instructor ".auth()->user()->email." checked attendance, marked ".$status." for student "
            .$student->lname.", ".$student->fname;

            //generates random notif id
            $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $notif_randnum = "";
            for($i = 0; $i < 6; $i++){
                $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
            }
            //generated notif id
            $repnotif_id = $notif_randnum;

            $logger->logActivity($request, $repnotif_id, $message);

            if($status === 'Absent'){
            $this->checkConsecutiveAbsences($student, $instructor);
            }


            return redirect('/checkattendance')->with('success', 'Attendance recorded - '.$status);
        }catch(Exception $e){
            return redirect('/checkattendance')->with('error', $e->getMessage());
        }

    }

    public function editAttendance(Attendance $att, Request $request){

        $oldStat = $att->status;

        $inputs = $request->validate([
            'status' => 'required'
        ]);

        if($oldStat != $inputs['status']){

            $att->status = $inputs['status'];
            $att->save();

            $logger = new Activity_logController();
            $message = "Instructor ".auth()->user()->name." changed attendance status for "
            .$att->student->lname.", ".$att->student->fname;

            //generates random notif id
            $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $notif_randnum = "";
            for($i = 0; $i < 6; $i++){
                $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
            }
            //generated notif id
            $repnotif_id = $notif_randnum;
            $logger->logActivity($request, $repnotif_id, $message);

            if($inputs['status'] === 'Absent'){
            $instructor = Instructor::where('email', auth()->user()->email)->first();
            $this->checkConsecutiveAbsences($att->student, $instructor);
            }

            return redirect('/checkattendance')->with('success', 'Attendance edited - '.$att->status);

        }else{
            return redirect('/checkattendance')->with('info', 'No changes were made');
        }


    }

    private function checkConsecutiveAbsences($student, $instructor){
        // Get start and end of current week (Monday to Sunday)
        $startOfWeek = now()->startOfWeek(\Carbon\Carbon::MONDAY);
        $endOfWeek = now()->endOfWeek(\Carbon\Carbon::SUNDAY);

        $absentDates = Attendance::where('student_id', $student->id)
        ->where('status', 'Absent')
        ->whereBetween('att_date', [$startOfWeek, $endOfWeek])
        ->orderBy('att_date', 'asc')
        ->pluck('att_date')
        ->map(function($date) {
            return \Carbon\Carbon::parse($date)->startOfDay();
        })
        ->unique()
        ->values();

        // Get all attendance records for this student for the current week, ordered by date
        $weekAttendance = Attendance::where('student_id', $student->id)
            ->whereBetween('att_date', [$startOfWeek, $endOfWeek])
            ->orderBy('att_date', 'asc')
            ->get();

        // Check for 2 consecutive days
        $hasConsecutiveAbsences = false;

        for($i = 0; $i < $absentDates->count() - 1; $i++){
            $currentDate = $absentDates[$i];
            $nextDate = $absentDates[$i + 1];

            // Check if dates are consecutive (1 day apart)
            if($currentDate->diffInDays($nextDate) == 1){
                $hasConsecutiveAbsences = true;
                break;
            }
        }

        // Send email if there are 2 consecutive absences
        if($hasConsecutiveAbsences){
            $this->sendAbsenceNoticeEmail($student, $instructor);
        }
    }

private function sendAbsenceNoticeEmail($student, $instructor){
    // Get guardian information
    $guardian = $student->guardian;
    
    if(!$guardian || !$guardian->email){
        \Log::warning("No guardian email for student ID: " . $student->id);
        return;
    }

    // Calculate total absences this year
    $absCount = Attendance::where('student_id', $student->id)
        ->where('status', 'Absent')
        ->whereYear('att_date', now()->year)
        ->count();

    // Calculate absences this week
    $startOfWeek = now()->startOfWeek(\Carbon\Carbon::MONDAY);
    $endOfWeek = now()->endOfWeek(\Carbon\Carbon::SUNDAY);
    $absCountWeek = Attendance::where('student_id', $student->id)
        ->where('status', 'Absent')
        ->whereBetween('att_date', [$startOfWeek, $endOfWeek])
        ->count();

    
    $studname = $student->fname . ' ' . $student->lname;
    $date = now()->format('F d, Y');
    $guardianName = $guardian->name;
    $instructor_name = $instructor->fname." ".$instructor->lname;
    $instructor_mail = $instructor->email;

    try {
        \Mail::to($guardian->email)->send(
            new \App\Mail\GuardianAbsMail(
                $studname,
                $date,
                $guardianName,
                $absCount,
                $absCountWeek,
                $instructor_name,
                $instructor_mail,
            )
        );
        
        \Log::info("Absence notice email sent to guardian: " . $guardian->email . " for student: " . $studname);
        
    } catch(\Exception $e) {
        \Log::error('Failed to send absence notice email: ' . $e->getMessage());
    }
}

    public function viewCSVPage(){
        return view('Adm/attcsv');
    }

    public function importAttendance(Request $request)
    {
        $request->validate([
            'csv_url' => 'required|url',
        ]);

        try {
        // Fetch the CSV file from the URL
        $response = Http::get($request->csv_url);

        if (!$response->successful()) {
            return redirect()->back()->with('error', 'Failed to fetch CSV file from the provided URL.');
        }

        // Save temporarily
        $tempFileName = 'temp_' . time() . '.csv';
        Storage::put($tempFileName, $response->body());

        // Import the data
        Excel::import(new AttendanceImport, Storage::path($tempFileName));

        // Delete temporary file
        Storage::delete($tempFileName);

        return redirect()->back()->with('success', 'Attendance data imported');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing data: ' . $e->getMessage());
        }
    }

    public function exportAttendance(Request $request){

        $request->validate([
        'spreadsheet_url' => 'required|url',
        ]);
    
        try {
        // Use your existing AttendanceExport class
        $export = new AttendanceExport();
        
        // Get the data
        $collection = $export->collection();
        $headings = $export->headings();
        
        // Check if there's data to export
        if ($collection->isEmpty()) {
            return redirect()->back()->with('error', 'No attendance data to export.');
        }
        
        // Build CSV content
        $csvRows = [];
        
        // Add headers
        $csvRows[] = $headings;
        
        // Add data rows
        foreach ($collection as $item) {
            $csvRows[] = $export->map($item);
        }
        
        // Convert to CSV string
        $csv = '';
        foreach ($csvRows as $row) {
            $escapedRow = array_map(function($value) {
                // Convert to string and escape
                $value = (string) $value;
                // If contains comma, newline, or quote, wrap in quotes
                if (strpos($value, ',') !== false || 
                    strpos($value, "\n") !== false || 
                    strpos($value, '"') !== false) {
                    return '"' . str_replace('"', '""', $value) . '"';
                }
                return $value;
            }, $row);
            $csv .= implode(',', $escapedRow) . "\n";
        }
        

        // Send to Google Apps Script Web App
        $response = Http::timeout(30)
            ->withHeaders([
                'Content-Type' => 'text/plain; charset=utf-8'
            ])
            ->withBody($csv, 'text/plain')
            ->post($request->spreadsheet_url);


            if ($response->successful()) {
                $result = $response->json();
            
            if (isset($result['status']) && $result['status'] === 'success') {
                return redirect()->back()->with('success', 
                    'Attendance data exported');
            } else {
                return redirect()->back()->with('error', 
                    'Export failed: ' . ($result['message'] ?? 'Unknown error'));
            }
        } else {
            return redirect()->back()->with('error', 
                'Failed to connect to Google Sheets. HTTP Status: ' . $response->status() . 
                '. Response: ' . $response->body());
        }

        } catch (\Exception $e) {
        \Log::error('Export Error Details', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        return redirect()->back()->with('error', 'Error exporting data: ' . $e->getMessage());
        }
    }

    public function addSchedule(Request $request){

        $customMessages = [
            'end_time.after' => 'End time must be after start time.'
        ];
        
        $setsched = $request->validate([

            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'section_id' => 'required|exists:sections,id'
        ], $customMessages);

        $startTime = \Carbon\Carbon::parse($setsched['start_time']);
        $endTime = \Carbon\Carbon::parse($setsched['end_time']);

        $duration = $startTime->diffInMinutes($endTime);

        //late time is 25% of duration
        $lateMinutes = round($duration * 0.25);
        $lateTime = $startTime->copy()->addMinutes($lateMinutes);

        //absent time is 40% of duration
        $absentMinutes = round($duration * 0.40);
        $absentTime = $startTime->copy()->addMinutes($absentMinutes);

        $inst_sub = Inst_sub::where('user_id', auth()->user()->id)->first();

        try{
        // Create schedule
        $schedule = Schedule::create([
            'start_time' => $setsched['start_time'],
            'end_time' => $setsched['end_time'],
            'subject_id' => $inst_sub->subject_id,
            'section_id' => $setsched['section_id']
        ]);


        Schedlimit::create([
            'late_time' => $lateTime->format('H:i:s'),
            'absent_time' => $absentTime->format('H:i:s'),
            'schedule_id' => $schedule->id,
        ]);

        $logger = new Activity_logController();
        $message = "User ".auth()->user()->email." added class schedule for ".
        $schedule->section->name;
        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;
        $logger->logActivity($request, $repnotif_id, $message);

        return redirect('/checkattendance')->with('success', 'Class schedule added');
        }catch(\Exception $e){
            \Log::error('Failed to add schedule: ' . $e->getMessage());
            return redirect('/checkattendance')->with('error', $e->getMessage());
        }

    }

    public function editSchedule(Schedule $schedule, Request $request){

        $customMessages = [
            'end_time.after' => 'End time must be after start time.'
        ];
        
        $setsched = $request->validate([

            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time']
        ], $customMessages);

        $inst_sub = Inst_sub::where('user_id', auth()->user()->id)->first();

        $schedlim = Schedlimit::where('schedule_id', $schedule->id)->first();

        $currentStartTime = $schedule->start_time->format('H:i');
        $currentEndTime = $schedule->end_time->format('H:i');

        if($currentStartTime !== $setsched['start_time']
        || $currentEndTime !== $setsched['end_time']){

            $startTime = \Carbon\Carbon::parse($setsched['start_time']);
            $endTime = \Carbon\Carbon::parse($setsched['end_time']);

            $duration = $startTime->diffInMinutes($endTime);

            //late time is 25% of duration
            $lateMinutes = round($duration * 0.25);
            $lateTime = $startTime->copy()->addMinutes($lateMinutes);

            //absent time is 40% of duration
            $absentMinutes = round($duration * 0.40);
            $absentTime = $startTime->copy()->addMinutes($absentMinutes);

            $schedule->start_time = $setsched['start_time'].':00';
            $schedule->end_time = $setsched['end_time'].':00';
            $schedule->save();

            $schedlim->late_time = $lateTime->format('H:i:s');
            $schedlim->absent_time = $absentTime->format('H:i:s');
            $schedlim->save();

            $logger = new Activity_logController();
            $message = "User ".auth()->user()->email." updated class schedule for ".
            $schedule->section->name;

            //generates random notif id
            $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $notif_randnum = "";
            for($i = 0; $i < 6; $i++){
                $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
            }
            //generated notif id
            $repnotif_id = $notif_randnum;
            $logger->logActivity($request, $repnotif_id, $message);

            return back()->with('success', 'Class schedule edited');

        }else{
            return back()->with('info', 'No changes were made');
        }


    }

    /*student management
    e.g. instructor request for archiving/restoring students*/

    public function viewInstReqForm(){

        $instructor = Instructor::where('email', auth()->user()->email)->first();
        $instructorGradeId = $instructor->section->grade_id;
    
        $students = Student::with('section.grade')
        ->whereHas('section', function($query) use ($instructorGradeId) {
            $query->where('grade_id', $instructorGradeId);
        })
        ->orderBy('lname')
        ->orderBy('fname')
        ->get();
    
        $arcstuds = Arcstudent::with('section.grade')
        ->whereHas('section', function($query) use ($instructorGradeId) {
            $query->where('grade_id', $instructorGradeId);
        })
        ->orderBy('lname')
        ->orderBy('fname')
        ->get();

        return view('User/instreq', compact('instructor',
        'students', 'arcstuds'));

    }

    public function sendInstRequest(Request $request){

        $request->validate([

            'inst_request' => 'required',
            'student_id' => 'required',
            'reason' => 'required_if:inst_request,Archive Student'
        
        ]);

        $instructor = Instructor::where('email', auth()->user()->email)->first();

        if($request->inst_request === "Archive Student"){
            $student = Student::find($request->student_id);
        }elseif($request->inst_request === "Restore Student"){
            $student = Arcstudent::find($request->student_id);
        }


        $student_name = $student->fname.' '.$student->lname;

        $notif = "Instructor ".auth()->user()->email." has requested to ".$request->inst_request.
                 " for student ".$student_name.". Please check your inbox.";

        $admin = Admin::first();

        Adm_notif::create(
            [
                'info' => $notif,
                'notif_date' => now(),
                'admin_id' => $admin->id
            ]
        );

        $reason = $request->reason ?? 'Returning';

        try {
            Mail::to($admin->email)->send(new InstreqMail(
                $admin->name,
                auth()->user()->name,
                $request->inst_request,
                $student_name,
                $student->student_id,
                $reason));
        } catch (\Exception $e) {
            \Log::error('Failed to send inst request: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send instructor request e-mail.']);
        }

        $logger = new Activity_logController();
        $message = auth()->user()->email." sent an instructor request";

        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;

        $logger->logActivity($request, $repnotif_id, $message);


        return back()->with('success', 'Instructor request sent');
    }


    //admin functions for instructors
    public function viewInstructors(){

        $instructors = Instructor::with(['section.grade'])
        ->join('sections', 'instructors.section_id', '=', 'sections.id')
        ->join('grades', 'sections.grade_id', '=', 'grades.id')
        ->orderBy('instructors.lname')
        ->orderBy('instructors.fname')
        ->orderBy('grades.roman_numeral')
        ->orderBy('sections.name')
        ->select('instructors.*')
        ->get();

        return view('Adm/Instructor/instructor', compact('instructors'));
    }


    public function viewInstSubs(){

        $inst_subs = Inst_sub::with(['user', 'subject'])
        ->join('users', 'inst_subs.user_id', "=", 'users.id')
        ->join('subjects', 'inst_subs.subject_id', '=', 'subjects.id')
        ->orderBy('users.name')
        ->select('inst_subs.*')
        ->get();

        return view('Adm/Instructor/instsubject', compact('inst_subs'));

    }

    /*
    public function viewInstSubsForm(){

        //view dropdown boxes for instructor subject form
        $users = User::whereNotNull('email_verified_at')
        ->orderBy('name')->get();
        $subjects = Subject::get();

        return view('Adm/Instructor/addinstsub', compact('users', 'subjects'));

    }

    public function addInstSub(Request $request){


        $customMessages = [
            'user_id.unique' => 'Instructor is already assigned to a subject'
        ];

        $inputs = $request->validate([

            'user_id' => ['required', Rule::unique('inst_subs', 'user_id')],
            'subject_id' => 'required'

        ], $customMessages);

        Inst_sub::create($inputs);

        $user = User::findOrFail($request->user_id);
        $subject = Subject::findOrFail($request->subject_id);

        $logger = new Activity_logController();
        $message = "Admin ".auth()->guard('admin')->user()->email." assigned subject ".$subject->name." to instructor "
        .$user->name;

        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;

        $logger->logActivity($request, $repnotif_id, $message);

        return redirect('/admin/view-inst-subjects')->with('success', 'Subject assigned successfully');

    }*/

    //edit instructor function for users
    public function editInstructor(User $user, Instructor $instructor){

        $sections = Section::orderBy('grade_id')->orderBy('name')->get();
        $grades = Grade::get();

        return view('User/editinstructor', 
        compact('user', 'instructor', 'sections', 'grades'));
    }


    public function updInstructor(Instructor $instructor, Request $request){


        $oldAddress = $instructor->address;
        $oldSection = $instructor->section_id;

        $inputs = $request->validate([
            'address' => ['required', 'min:1', 'max:100'],
            'section_id' => ['required', 'unique:instructors,section_id,'.$instructor->id]
        ]);

        if($oldAddress !== $inputs['address'] || $oldSection != $inputs['section_id']){
            $instructor->address = $inputs['address'];
            $instructor->section_id = $inputs['section_id'];
            $instructor->save();

            $logger = new Activity_logController();
            $message = "User ".$instructor->lname.", ".$instructor->fname." updated their profile";

            //generates random notif id
            $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $notif_randnum = "";
            for($i = 0; $i < 6; $i++){
                $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
            }
            //generated notif id
            $repnotif_id = $notif_randnum;
            $logger->logActivity($request, $repnotif_id, $message);

            return redirect('/')->with('success', 'Profile updated successfully');
        }else{
            return back()->with('info', 'No changes were made');
        }


    }

    //rest of the admin functions
    public function editSubject(Inst_sub $inst_sub){

        $subjects = Subject::get();

        return view('Adm/Instructor/editinstsub', compact('inst_sub', 'subjects'));

    }

    
    public function updSubject(Inst_sub $inst_sub, Request $request){


        $oldSub = $inst_sub->subject->name;

        $inputs = $request->validate([
            'subject_id' => ['required']
        ]);

        if($inst_sub->subject_id != $inputs['subject_id']){

            $newSub = Subject::findOrFail($inputs['subject_id']);

            $inst_sub->subject_id = $inputs['subject_id'];
            $inst_sub->save();

            $logger = new Activity_logController();
            $message = "Admin ".auth()->guard('admin')->user()->email." updated subject "
            .$oldSub." to ".$newSub->name." for instructor ".$inst_sub->user->name;

            //generates random notif id
            $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $notif_randnum = "";
            for($i = 0; $i < 6; $i++){
                $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
            }
            //generated notif id
            $repnotif_id = $notif_randnum;
            $logger->logActivity($request, $repnotif_id, $message);

            return redirect('/admin/view-inst-subjects')->with('success', 'Subject updated successfully');

        }else{
            return back()->with('info', 'No changes were made');    
        }


    }


    public function deleteInstructor(Instructor $instructor, Inst_sub $inst_sub, User $user, Request $request){


        $instructorName = $instructor->lname.", ".$instructor->fname;
        $instructorEmail = $instructor->email;

        $instructor->delete();
        $inst_sub->where('user_id', $user->id)->delete();
        $user->where('email', $instructorEmail)->delete();

        $logger = new Activity_logController();
        $message = "Admin ".auth()->guard('admin')->user()->email." deleted instructor ".$instructorName
        ." with email ".$instructorEmail;

        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;
        $logger->logActivity($request, $repnotif_id, $message);

        return redirect('/admin/view-instructors')->with('success', 'Instructor deleted successfully');
    }
}
