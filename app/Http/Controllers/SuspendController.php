<?php

namespace App\Http\Controllers;

use App\Mail\SusMail;
use App\Models\Student;
use App\Models\Suspend;
use App\Mail\AllSusMail;
use App\Models\Guardian;
use App\Models\Inst_sub;
use App\Mail\LiftSusMail;
use App\Models\Allsuspend;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SuspendController extends Controller
{

    public function suspendClass(Request $request){

        $inst_sub = Inst_sub::where('user_id', auth()->user()->id)->first();

        $instructor = Instructor::where('email', auth()->user()->email)->first();

        $suspendDate = now()->format('Y-m-d');

        $suspend = Suspend::where('suspend_date', $suspendDate)
        ->where('user_id', auth()->user()->id)
        ->first();

        
        if(now()->format('w') == 0 || now()->format('w') == 6){
            return back()->with('error', 'Cannot suspend during weekends');
        }elseif($suspend){
            return back()->with('error', 'Class suspension already set');
        }

        Suspend::create([
            'suspend_date' => now(),
            'user_id' => auth()->user()->id,
            'grade_id' => $instructor->section->grade_id,
            'subject_id' => $inst_sub->subject_id
            
        ]);

        $this->notifyGuardiansForInstructorSuspension($instructor);

        return back()->with('success', 'All handled classes suspended');
    }

    public function liftSuspension(Suspend $sus, Request $request){

        // Authorization check
        if($sus->user_id !== auth()->user()->id){
            return back()->with('error', 'Unauthorized action');
        }

        // Prevent lifting past suspensions
        if($sus->suspend_date < now()->startOfDay()){
            return back()->with('error', 'Cannot lift past suspensions');
        }

        $instructor = Instructor::where('id', auth()->user()->id)->first();

        $this->notifyGuardiansForLiftSuspension($instructor);

        $sus->delete();

        return back()->with('success', 'Suspension lifted for your classes');

    }

    public function suspendAllClasses(Request $request){

        $suspendDate = now()->format('Y-m-d');
    
        $suspend = Allsuspend::where('suspend_date', $suspendDate)->first();
    
        if($suspend){
            return back()->with('error', 'Class suspension already set');
        }elseif(now()->format('w') == 0 || now()->format('w') == 6){
            return back()->with('error', 'Cannot suspend during weekends');
        }


        Allsuspend::create([
            'suspend_date' => now()
        ]);

        $this->notifyAllGuardians();

        return back()->with('success', 'All classes suspended');

    }

    /**
     * Send suspension notification to guardians of students in instructor's grade level
     */
    private function notifyGuardiansForInstructorSuspension(Instructor $instructor)
    {
        try {
            // Get all students in the same grade level as the instructor
            $students = Student::whereHas('section', function($query) use ($instructor) {
                $query->where('grade_id', $instructor->section->grade_id);
            })->with('guardian')->get();

            // Get instructor's full name
            $instructorName = $instructor->fname . ' ' . $instructor->lname;

            // Send email to each guardian
            foreach($students as $student) {
                if($student->guardian && $student->guardian->email) {
                    try {
                        Mail::to($student->guardian->email)
                            ->send(new SusMail($student->guardian, $instructorName));
                    } catch (\Exception $e) {
                        // Log individual email failures but continue
                        Log::error('Failed to send suspension email to guardian: ' . $student->guardian->email . ' - ' . $e->getMessage());
                    }
                }
            }

        } catch (\Exception $e) {
            Log::error('Error in notifyGuardiansForInstructorSuspension: ' . $e->getMessage());
        }
    }

    /**
     * Send lift suspension notification to guardians of students in instructor's grade level
     */
    private function notifyGuardiansForLiftSuspension(Instructor $instructor)
    {
        try {
            // Get all students in the same grade level as the instructor
            $students = Student::whereHas('section', function($query) use ($instructor) {
                $query->where('grade_id', $instructor->section->grade_id);
            })->with('guardian')->get();

            // Get instructor's full name
            $instructorName = $instructor->fname . ' ' . $instructor->lname;

            // Send email to each guardian
            foreach($students as $student) {
                if($student->guardian && $student->guardian->email) {
                    try {
                        Mail::to($student->guardian->email)
                            ->send(new LiftSusMail($student->guardian, $instructorName));
                    } catch (\Exception $e) {
                        Log::error('Failed to send lift suspension email to guardian: ' . $student->guardian->email . ' - ' . $e->getMessage());
                    }
                }
            }

        } catch (\Exception $e) {
            Log::error('Error in notifyGuardiansForLiftSuspension: ' . $e->getMessage());
        }
    }

    /**
     * Send suspension notification to ALL guardians
     */
    private function notifyAllGuardians()
    {
        try {
            // Get all guardians with email addresses
            $guardians = Guardian::whereNotNull('email')->get();

            // Send email to each guardian
            foreach($guardians as $guardian) {
                try {
                    Mail::to($guardian->email)
                        ->send(new AllSusMail($guardian));
                } catch (\Exception $e) {
                    // Log individual email failures but continue
                    Log::error('Failed to send all-class suspension email to guardian: ' . $guardian->email . ' - ' . $e->getMessage());
                }
            }

        } catch (\Exception $e) {
            Log::error('Error in notifyAllGuardians: ' . $e->getMessage());
        }
    }

}
