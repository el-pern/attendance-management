<?php

namespace App\Http\Controllers;

use App\Models\Drop;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Student;
use App\Models\Guardian;
use App\Models\Arcstudent;
use App\Models\Arcguardian;
use Illuminate\Http\Request;
use App\Imports\StudentImport;
use Illuminate\Validation\Rule;
use App\Mail\GuardianOrientMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    //crud for students

    public function viewSections(){


        $sections = Section::orderBy('grade_id')->orderBy('name')->get();
        $grades = Grade::get();

        return view('Adm/Student/addstudent', compact('sections', 'grades'));

    }

    public function viewStudents(){

        $students = Student::with(['section.grade'])
        ->join('sections', 'students.section_id', '=', 'sections.id')
        ->join('grades', 'sections.grade_id', '=', 'grades.id')
        ->orderBy('grades.id')
        ->orderBy('sections.name')
        ->orderBy('students.lname')
        ->orderBy('students.fname')
        ->select('students.*')
        ->get();

        return view('Adm/Student/student', compact('students'));
    }


    public function addStudent(Request $request){

        // Validate inputs
    $inputs = $request->validate([
        'sheet_url' => 'required|url',
        'section_id' => 'required|exists:sections,id'
    ]);

    try {
        // Fetch the CSV file from the provided URL
        $response = Http::get($request->sheet_url);
        
        if (!$response->successful()) {
            return redirect()->back()->with('error', 'Failed to fetch CSV file from the provided URL. Please ensure the link is publicly accessible.');
        }

        // Check if the response is actually CSV content
        $contentType = $response->header('Content-Type');
        if (!str_contains($contentType, 'text/csv') && !str_contains($contentType, 'text/plain')) {
            return redirect()->back()->with('error', 'The URL does not point to a valid CSV file. Please use a public CSV link.');
        }

        // Save temporarily with a unique filename
        $tempFileName = 'temp_student_import_' . time() . '.csv';
        Storage::put($tempFileName, $response->body());

        // Import the data with section_id
        Excel::import(
            new StudentImport($request->section_id), 
            Storage::path($tempFileName)
        );

        // Delete temporary file
        Storage::delete($tempFileName);

        return redirect('/admin/view-students')->with('success', 'Students and guardians imported');

    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        // Handle validation errors from the import
        $failures = $e->failures();
        $errorMessages = [];
        
        foreach ($failures as $failure) {
            $errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
        }
        
        return redirect()->back()->with('error', 'Validation errors: ' . implode(' | ', $errorMessages));
        
    } catch (\Exception $e) {
        // Clean up temp file if it exists
        if (isset($tempFileName) && Storage::exists($tempFileName)) {
            Storage::delete($tempFileName);
        }
        
        return redirect()->back()->with('error', 'Error importing data: ' . $e->getMessage());
    }
    }

    public function editStudent(Student $student){

        $sections = Section::orderBy('grade_id')->orderBy('name')->get();
        $grades = Grade::get();

        return view('Adm/Student/editstudent', 
        compact('student', 'sections', 'grades'));
    }

    public function updStudent(Student $student, Request $request){

        $oldFname = $student->fname;
        $oldLname = $student->lname;
        $oldEmail = $student->email;
        $oldAddress = $student->address;
        $oldStudentID = $student->student_id;
        $oldSection = $student->section_id;

        $inputs = $request->validate([
            'fname' => 
            ['required',
            'regex:/^(?!.*^[A-Za-z]\s*$)[A-Za-z ]{2,50}$/',
            'min:2',
            'max:50'],

            'lname' => 
            ['required',
            'regex:/^(?!.*^[A-Za-z]\s*$)[A-Za-z ]{2,50}$/',
            'min:2',
            'max:50'],

            'email' => ['required', 'email', 'max:50', Rule::unique('students','email')->ignore($student->id)],
            'address' => ['required', 'min:1', 'max:100'],
            'student_id' => ['required', 'digits:9', Rule::unique('students','student_id')->ignore($student->id)],
            'section_id' => ['required']
        ]);


        if($oldFname !== $inputs['fname'] || $oldLname !== $inputs['lname']
        || $oldEmail !== $inputs['email'] || $oldAddress !== $inputs['address']
        || $oldStudentID !== $inputs['student_id'] || $oldSection != $inputs['section_id']){

            $student->fname = $inputs['fname'];
            $student->lname = $inputs['lname'];
            $student->email = $inputs['email'];
            $student->address = $inputs['address'];
            $student->student_id = $inputs['student_id'];
            $student->section_id = $inputs['section_id'];
            $student->save();

            $logger = new Activity_logController();
            $message = "Admin ".auth()->guard('admin')->user()->email." updated student ".$request->lname.", ".$request->fname
            ." with student no. ".$request->student_id;

            //generates random notif id
            $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $notif_randnum = "";
            for($i = 0; $i < 6; $i++){
                $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
            }
            //generated notif id
            $repnotif_id = $notif_randnum;
            $logger->logActivity($request, $repnotif_id, $message);

            return redirect('/admin/view-students')->with('success', 'Student updated successfully');

        }else{
            return back()->with('info', 'No changes were made');
        }

    }

    public function deleteStudent(Student $student, Request $request){

        $guardianData = null;
        if($student->guardian && $student->guardian->email){
            $guardianData = [
                'name' => $student->guardian->name,
                'email' => $student->guardian->email,
            ];
            // Delete the guardian
            $student->guardian->delete();
        }


        $archivedStudent = Arcstudent::create([
            'lname' => $student->lname,
            'fname' => $student->fname,
            'email' => $student->email,
            'address' => $student->address,
            'student_id' => $student->student_id,
            'section_id' => $student->section_id
        ]);

        if($guardianData){
        Arcguardian::create([
            'name' => $guardianData['name'],
            'email' => $guardianData['email'],
            'arcstudent_id' => $archivedStudent->id  // Use the ID of the newly created Arcstudent
        ]);
        }


        $student->delete();

        $studentName = $student->lname.", ".$student->fname;

        $logger = new Activity_logController();
        $message = "Admin ".auth()->guard('admin')->user()->email." archived student ".$studentName
        ." with student no. ".$student->student_id;

        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;
        $logger->logActivity($request, $repnotif_id, $message);

        return redirect('/admin/view-students')->with('success', 'Student archived successfully');
    }

    public function viewArcStuds(){
        $students = Arcstudent::with(['section.grade'])
        ->join('sections', 'arcstudents.section_id', '=', 'sections.id')
        ->join('grades', 'sections.grade_id', '=', 'grades.id')
        ->orderBy('grades.id')
        ->orderBy('sections.name')
        ->orderBy('arcstudents.lname')
        ->orderBy('arcstudents.fname')
        ->select('arcstudents.*')
        ->get();

        $dropped = Drop::pluck('arcstudent_id')->toArray();

        return view('Adm/Student/archivedstud', compact('students', 'dropped'));
    }

    public function restoreStudent(Arcstudent $arcstud, Request $request){

        $arcguardData = null;
        if($arcstud->guardian && $arcstud->guardian->email){
            $arcguardData = [
                'name' => $arcstud->guardian->name,
                'email' => $arcstud->guardian->email
            ];
            $arcstud->guardian->delete();
        }


        $restoredStud = Student::create([
            'lname' => $arcstud->lname,
            'fname' => $arcstud->fname,
            'email' => $arcstud->email,
            'address' => $arcstud->address,
            'student_id' => $arcstud->student_id,
            'section_id' => $arcstud->section_id
        ]);

        if($arcguardData){
            Guardian::create([
                'name' => $arcguardData['name'],
                'email' => $arcguardData['email'],
                'student_id' => $restoredStud->id
            ]);
        }

        $studentName = $arcstud->lname.", ".$arcstud->fname;

        $arcstud->delete();

        $logger = new Activity_logController();
        $message = "Admin ".auth()->guard('admin')->user()->email." restored student ".$studentName
        ." with student no. ".$arcstud->student_id;

        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;
        $logger->logActivity($request, $repnotif_id, $message);

        return redirect('/admin/arc-students')->with('success', 'Student restored successfully');

    }

    public function markAsDropped(Arcstudent $arcstud, Request $request){

        Drop::create([
            'arcstudent_id' => $arcstud->id
        ]);

        $studentName = $arcstud->lname.", ".$arcstud->fname;

        $logger = new Activity_logController();
        $message = "Admin ".auth()->guard('admin')->user()->email." marked student ".$studentName
        ." as dropped.";

        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;
        $logger->logActivity($request, $repnotif_id, $message);

        return redirect('/admin/arc-students')->with('success', 'Archived student marked as dropped');

    }

    //guardians

    public function viewGuardians(){

        $guardians = Guardian::with('student')
        ->join('students', 'guardians.student_id', '=', 'students.id')
        ->orderBy('guardians.name')
        ->orderBy('students.lname')
        ->orderBy('students.fname')
        ->select('guardians.*')
        ->get();

        return view('Adm/Student/guardian', compact('guardians'));

    }

    public function editGuardian(Guardian $guardian){

        $students = Student::with('section')
        ->orderBy('lname')
        ->orderBy('fname')->get();
        $grades = Grade::get();

        return view('Adm/Student/editguardian', compact('guardian', 'students', 'grades'));

    }

    public function updGuardian(Guardian $guardian, Request $request){

        $oldName = $guardian->name;
        $oldEmail = $guardian->email;
        $oldStud = $guardian->student_id;

        $inputs = $request->validate([

            'name' => 
            ['required',
            'regex:/^(?!.*^[A-Za-z]\s*$)[A-Za-z ]{2,50}$/',
            'min:2',
            'max:50'],

            'email' => ['required', 'email', 'max:50', Rule::unique('guardians','email')->ignore($guardian->id)],

            'grade_id' => 'required',
            'student_id' => 'required'

        ]);

        if($oldName !== $inputs['name'] || $oldEmail !== $inputs['email']
        || $oldStud != $inputs['student_id']){

            $guardian->name = $inputs['name'];
            $guardian->email = $inputs['email'];
            $guardian->student_id = $inputs['student_id'];
            $guardian->save();

            $logger = new Activity_logController();
            $message = "Admin ".auth()->guard('admin')->user()->email." updated guardian from "
            .$oldName." to ".$inputs['name']
            ." with e-mail ".$inputs['email'];

            //generates random notif id
            $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $notif_randnum = "";
            for($i = 0; $i < 6; $i++){
                $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
            }
            //generated notif id
            $repnotif_id = $notif_randnum;

            $logger->logActivity($request, $repnotif_id, $message);

            return redirect('/admin/guardians')->with('success', 'Guardian edited successfully');

        }else{
            return back()->with('info', 'No changes were made');
        }

    }

    public function sendOrientMail(Guardian $guardian, Request $request){

        $logger = new Activity_logController();
        $message = "Admin ".auth()->guard('admin')->user()->email." sent orientation mail to ".$guardian->email;

        try {
            Mail::to($guardian->email)
            ->send(new GuardianOrientMail(auth()->guard('admin')->user()->name, 
                    auth()->guard('admin')->user()->email));
        } catch (\Exception $e) {
            \Log::error('Failed to send orientation notif: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send attendance system orientation message.']);
        }

        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;

        $logger->logActivity($request, $repnotif_id, $message);

        return redirect('/admin/guardians')->with('success', 'E-mail sent to guardian '.$guardian->email);

    }
}
