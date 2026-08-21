<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Holiday;
use App\Models\Section;
use App\Models\Student;
use App\Mail\AdmotpMail;
use App\Models\Vacation;
use App\Models\Adm_notif;
use App\Models\Admin_otp;
use App\Models\Allsuspend;
use App\Models\Instructor;
use App\Mail\VerifyuserMail;
use App\Models\Activity_log;
use Illuminate\Http\Request;
use App\Mail\DeclineuserMail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Activity_logController;

class AdminController extends Controller
{

    public function logout(Request $request){

        //logout activity log
        $logger = new Activity_logController();
        $message = "Admin ".auth()->guard('admin')->user()->email." logged out";
        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }

        //generated notif id
        $repnotif_id = $notif_randnum;
        $logger->logActivity($request, $repnotif_id, $message);

        auth()->guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/adminlogin');
    }

    public function login(Request $request){

        $usercreds = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if(auth()->guard('admin')->attempt($usercreds)){


            //login activity log
            $logger = new Activity_logController();
            $message = "Admin ".auth()->guard('admin')->user()->email." logged in";

            //generates random notif id
            $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

            $notif_randnum = "";

            for($i = 0; $i < 6; $i++){

                $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];

            }

            //generated notif id
            $repnotif_id = $notif_randnum;
            $logger->logActivity($request, $repnotif_id, $message);

            $request->session()->regenerate();

            return redirect('/admin')
            ->with('success','You are now logged in as admin.')
            ->withCookie(cookie('admin_session', true, 120));
        }

        $user = Admin::where('email', $request->input('email'))->first();

        if(!$user){
            return back()->withErrors([
            'email' => 'Invalid e-mail'
         ])->onlyInput('email');
        }else{

        return back()->withErrors([
        'password' => 'Invalid password'
         ])->onlyInput('email');}
    }

    public function home(){

        $holidays = Holiday::where('holiday_date', now()->format('m-d'))->first();

        $today = now();
        $vacations = Vacation::all()->first(function($vac) use ($today) {
            return $vac->includesDate($today);
        });

        $allsuspend = Allsuspend::where('suspend_date', now()->format('Y-m-d'))->first();

        return view('Adm/admhome', compact('holidays', 'vacations', 'allsuspend'));

    }

    //otp and edit pass functions

    public function viewEmailForm(){
        return view('Adm/admreqemail');
    }

    public function sendOTP(Request $request){

        /* change password for logged in users
           reset password for guest emails */
        if(Auth::check()){
            $admin = Admin::where('email', auth()->user()->email)->first();
        }else{
            $request->validate([
            'email' => 'required'
            ]);

            $admin = Admin::where('email', $request->email)->first();

            if(!$admin){
                return back()->withErrors(['email' => 'E-mail invalid/not found']);
            }
        }

        //generates otp
        $chars = "0123456789";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated otp
        $otp = $notif_randnum;

        try {
            Mail::to($admin->email)->send(new AdmotpMail($otp, $admin->name));
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send OTP email. Please try again.']);
        }

        Admin_otp::updateOrCreate(
            ['admin_id' => $admin->id],
            [
                'otp' => Hash::make($otp),
                'expires_at' => now()->addMinutes(5)
            ]
        );

        $logger = new Activity_logController();
        $message = "OTP generated for admin e-mail {$admin->email}.";

        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;

        $logger->logActivity($request, $repnotif_id, $message);

        //create session var
        session(['otp_admin_id' => $admin->id]);

        if(Auth::check()){
            return redirect('/authadmotp');
        }else{
            return redirect('/admotp');
        }
        

    }

    public function resendOTP(Request $request){
        $adminID = session('otp_admin_id');
        $regAdminID = session('otp_regadmin_id');

        if(!$adminID && !$regAdminID){

            $message = "Session expired.";
            echo "<script>alert(" . json_encode($message) . ");</script>";

            if(Auth::check()){
                return redirect('/admin');
            }else{
                return redirect('/adminlogin');
            }
        }

        // Determine which admin
        $targetAdminID = $adminID ?? $regAdminID;
        $admin = Admin::findOrFail($targetAdminID);

        //generates otp
        $chars = "0123456789";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated otp
        $otp = $notif_randnum;

        try {
            Mail::to($admin->email)->send(new AdmotpMail($otp, $admin->name));
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send OTP email. Please try again.']);
        }

        Admin_otp::updateOrCreate(
            ['admin_id' => $admin->id],
            [
                'otp' => Hash::make($otp),
                'expires_at' => now()->addMinutes(5)
            ]
        );

        $logger = new Activity_logController();
        $message = "OTP resent for admin e-mail {$admin->email}.";

        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;

        $logger->logActivity($request, $repnotif_id, $message);

        return back()->with('success', 'New OTP sent to your email');
    }

    public function viewOTPPage(){

        $adminID = session('otp_admin_id');
        $regAdminID = session('otp_regadmin_id');

        if(!$adminID && !$regAdminID){
            if(Auth::check()){
                return redirect('/admin');
            }else{
                return redirect('/adminlogin');
            }
        }

        $targetAdminID = $adminID ?? $regAdminID;
        $admin = Admin::findOrFail($targetAdminID);
        $otpRec = Admin_otp::where('admin_id', $targetAdminID)
        ->first();

        return view('Adm/admotp', [
            'email' => $admin->email,
            'expires_at' => $otpRec->expires_at->timestamp * 1000 
        ]);
    }

    public function verifyOTP(Request $request){

        $request->validate(['otp' => 'required|numeric|digits:6']);

        $adminID = session('otp_admin_id');
        $regAdminID = session('otp_regadmin_id');

        if(!$adminID && !$regAdminID){

            $message = "Session expired.";
            echo "<script>alert(" . json_encode($message) . ");</script>";

            if(Auth::check()){
                return redirect('/admin');
            }else{
                return redirect('/adminlogin');
            }
        }

        // Determine which admin
        $targetAdminID = $adminID ?? $regAdminID;
        $admin = Admin::findOrFail($targetAdminID);

        $otpRec = Admin_otp::where('admin_id', $admin->id)
        ->where('expires_at', '>', now())
        ->first();

        if(!$otpRec){
            return back()->withErrors(['otp' => 'OTP expired/not found']);
        }elseif(!Hash::check($request->otp, $otpRec->otp)){
            return back()->withErrors(['otp' => 'Invalid OTP']);
        }

        $otpRec->delete();

        if(Auth::check()){

            session(['verified_admin_id' => $adminID]);
            session()->forget('otp_admin_id');

            $logger = new Activity_logController();
            $message = "OTP verified by admin e-mail {$admin->email}.";
            $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $notif_randnum = "";
            for($i = 0; $i < 6; $i++){
                $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
            }
            //generated notif id
            $repnotif_id = $notif_randnum;
        
            $logger->logActivity($request, $repnotif_id, $message);

            return redirect('/admchangepass')->with('success', 'OTP verified successfully');
        }else{
            if($adminID){
                session(['verified_admin_id' => $adminID]);
                session()->forget('otp_admin_id');

                $logger = new Activity_logController();
                $message = "OTP verified by admin e-mail {$admin->email}.";

                $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                $notif_randnum = "";
                for($i = 0; $i < 6; $i++){
                    $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
                }
                //generated notif id
                $repnotif_id = $notif_randnum;
            
                $logger->logActivity($request, $repnotif_id, $message);

                return redirect('/admresetpass')->with('success', 'OTP verified successfully');
            }elseif($regAdminID){
                session()->forget('otp_regadmin_id');

                $admin->email_verified_at = now();
                $admin->save();

                $logger = new Activity_logController();
                $message = "Admin e-mail {$admin->email} verified account.";

                $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                $notif_randnum = "";
                for($i = 0; $i < 6; $i++){
                    $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
                }
                //generated notif id
                $repnotif_id = $notif_randnum;
            
                $logger->logActivity($request, $repnotif_id, $message);

                $message = "✅ Account verified successfully.\nYou may log in.";
                echo "<script>alert(" . json_encode($message) . ");</script>";

                return redirect('/adminlogin');
            }
        }

    }

    public function cancelOTP(Request $request){

        $adminID = session('otp_admin_id');
        $regAdminID = session('otp_regadmin_id');

        if(!$adminID && !$regAdminID){

            $message = "Session expired.";
            echo "<script>alert(" . json_encode($message) . ");</script>";

            if(Auth::check()){
                return redirect('/admin');
            }else{
                return redirect('/adminlogin');
            }
        }

        // Determine which admin
        $targetAdminID = $adminID ?? $regAdminID;
        $admin = Admin::findOrFail($targetAdminID);

        $otpRec = Admin_otp::where('admin_id', $admin->id)
        ->first();

        if(!$otpRec){
            return back()->withErrors(['otp' => 'OTP not found']);
        }

        $otpRec->delete();
        if($adminID){
            session()->forget('otp_admin_id');
        }elseif($regAdminID){
            session()->forget('otp_regadmin_id');
        }

        if(Auth::check()){
            return redirect('/admin');
        }else{
            return redirect('/adminlogin');
        }

    }

    public function resetPass(){
        if(!session('verified_admin_id')){
            return redirect('/adminlogin');
        }

        $admin = Admin::findOrFail(session('verified_admin_id'));

        return view('Adm/admresetpass', [
            'email' => $admin->email
        ]);
    }

    public function resetPassFR(Request $request){

        $customMessages = [
        'password.regex' => 'Password must contain at least one letter, one number, and one special character.'
        ];

        $inputs = $request->validate([
            'password' => [
                'required',
                'min:8', 'max:20',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/',
                'confirmed'],
            'password_confirmation' => ['required']
        ], $customMessages);

        $adminID = session('verified_admin_id');
    
        if (!$adminID) {

            $message = "Session expired.";
            echo "<script>alert(" . json_encode($message) . ");</script>";

            return redirect('/adminlogin');
        }

        $admin = Admin::findOrFail($adminID);

        // Check if new password is same as old password
        if (Hash::check($request->password, $admin->password)) {
            return back()->withErrors([
                'password' => 'New password must be different from your current password.'
            ])->withInput();
        }

        $admin->password = bcrypt($inputs['password']);
        $admin->save();

        $logger = new Activity_logController();
        $message = "Password reset for admin e-mail {$admin->email}.";

        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;

        $logger->logActivity($request, $repnotif_id, $message);

        session()->forget('verified_admin_id');

        $message = "Password reset successfully.";
        echo "<script>alert(" . json_encode($message) . ");</script>";

        return redirect('/adminlogin');
    }

    public function cancelResetPass(Request $request){

        session()->forget('verified_admin_id');

        if(Auth::check()){
            return redirect('/admin');
        }else{
            return redirect('/adminlogin');
        }

    }

    public function changePass(){
        if(!session('verified_admin_id')){
            return redirect('/admin');
        }

        $admin = Admin::findOrFail(session('verified_admin_id'));

        return view('Adm/admchangepass', [
            'email' => $admin->email
        ]);
    }

    public function changePassFR(Request $request){

        $customMessages = [
        'password.regex' => 'Password must contain at least one letter, one number, and one special character.'
        ];

        $inputs = $request->validate([
            'password' => [
                'required',
                'min:8', 'max:20',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/',
                'confirmed'],
            'password_confirmation' => ['required']
        ], $customMessages);

        $adminID = session('verified_admin_id');
    
        if (!$adminID) {

            $message = "Session expired.";
            echo "<script>alert(" . json_encode($message) . ");</script>";

            return redirect('/adminlogin');
        }

        $admin = Admin::findOrFail($adminID);

        $admin->password = bcrypt($inputs['password']);
        $admin->save();

        $logger = new Activity_logController();
        $message = "Password changed for admin e-mail {$admin->email}.";

        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;

        $logger->logActivity($request, $repnotif_id, $message);

        session()->forget('verified_admin_id');

        return redirect('/admin')->with('success', 'Password changed successfully');

    }

    //act logs
    public function viewActLogs(){

        $actlogs = Activity_log::orderBy('log_date', 'desc')->limit(10)->get();

        return view('Adm/viewactlog', 
        compact('actlogs'));

    }
    public function viewAllActLogs(){

        $actlogs = Activity_log::orderBy('log_date', 'desc')->get();

        return view('Adm/viewactlog', 
        compact('actlogs'));

    }

    public function viewClasses(){

        $sections = Section::orderBy('grade_id')->orderBy('name')->get();
        $instructors = Instructor::with(['section.grade'])
        ->join('sections', 'instructors.section_id', '=', 'sections.id')
        ->join('grades', 'sections.grade_id', '=', 'grades.id')
        ->select('instructors.*')
        ->get();

        $students = Student::with(['section.grade'])
        ->join('sections', 'students.section_id', '=', 'sections.id')
        ->join('grades', 'sections.grade_id', '=', 'grades.id')
        ->select('students.*')
        ->get();

        return view('Adm/classes', compact('sections', 'instructors', 'students'));
    }

    public function viewReqNotifs(){

        $notifs = Adm_notif::where('admin_id', auth()->guard('admin')->user()->id)
        ->orderBy('notif_date', 'desc')->limit(5)->get();

        return view('Adm/admreqnotif', compact('notifs'));
    }

    public function viewAllReqNotifs(){

        $notifs = Adm_notif::where('admin_id', auth()->guard('admin')->user()->id)
        ->orderBy('notif_date', 'desc')
        ->get();

        return view('Adm/admreqnotif', compact('notifs'));
        
    }
    
}
