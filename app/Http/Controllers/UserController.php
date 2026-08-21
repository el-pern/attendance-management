<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Otp;
use App\Models\User;
use App\Mail\OtpMail;
use App\Mail\RegMail;
use App\Models\Instructor;
use App\Models\Inst_sub;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Activity_logController;

class UserController extends Controller
{

    public function logout(Request $request){

        //logout activity log
        $logger = new Activity_logController();
        $message = "User ".auth()->user()->email." logged out";
        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }

        //generated notif id
        $repnotif_id = $notif_randnum;
        $logger->logActivity($request, $repnotif_id, $message);

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function login(Instructor $instructor, Request $request){

        $usercreds = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if(auth()->attempt($usercreds) && auth()->user()->email_verified_at != null){


            //login activity log
            $logger = new Activity_logController();
            $message = "User ".auth()->user()->email." logged in";

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

            
            if(!$instructor->where('email', auth()->user()->email)->exists()){
                return redirect('/instructor/fill')->with('success', 'You are now logged in.');
            }else{
                return redirect('/')->with('success','You are now logged in.');
            }
        }

        $user = User::where('email', $request->input('email'))->first();

        if(!$user || $user->email_verified_at == null){
            return back()->withErrors([
            'email' => 'E-mail invalid/not found or not yet verified'
         ])->onlyInput('email');
        }else{

        return back()->withErrors([
        'password' => 'Invalid password'
         ])->onlyInput('email');}
    }

    //admin function for creating users, formerly user registers account
    public function register(Request $request){

        $customMessages = [
        'password.regex' => 'Password must contain at least one letter, one number, and one special character.'
        ];

        $inputs = $request->validate([
            'fname' => ['required', 'min:2', 'max:20'],
            'lname' => ['required', 'min:2', 'max:20'],
            'email' => ['required', 'email', Rule::unique('users','email')],
            'subject_id' => 'required'
        ], $customMessages);

        $inputs['name'] = $inputs['fname'].' '.$inputs['lname'];

        $name = $inputs['name'];
        $email = $inputs['email'];

        //unset as these fields do not exist in actual table
        unset($inputs['fname'], $inputs['lname']);

        //generates random chars for pass pattern
        $chars = "0123456789";
        $notif_randnum = "";
        for($i = 0; $i < 4; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;

        //registration activity log
        $pass_pattern = 'SAS-'.$notif_randnum;
        $inputs['password'] = bcrypt($pass_pattern);
        $user = User::create([

            'name' => $name,
            'email' => $email,
            'email_verified_at' => Carbon::now(),
            'password' => $inputs['password']


        ]);
        $inst_sub = Inst_sub::create([
            'user_id' => $user->id,
            'subject_id' => $inputs['subject_id']
        ]);

        
        try {
            Mail::to($email)->send(new RegMail($name, $email, $pass_pattern));
        } catch (\Exception $e) {
            \Log::error('Failed to send registration message: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send registration message.']);
        }

        $logger = new Activity_logController();
        $message = "New user account {$request->email} has been created.";

        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;

        $logger->logActivity($request, $repnotif_id, $message);
        
        return redirect('/createuser')->with('success', 'Instructor account created');
    }

    //otp and edit pass functions

    public function viewEmailForm(){
        return view('User/reqemail');
    }

    public function sendOTP(Request $request){


        /* change password for logged in users
           reset password for guest emails */
        if(Auth::check()){
            $user = User::where('email', auth()->user()->email)->first();
        }else{
            $request->validate([
            'email' => 'required'
            ]);

            $user = User::where('email', $request->email)->first();

            if(!$user){
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
            Mail::to($user->email)->send(new OtpMail($otp, $user->name));
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send OTP email. Please try again.']);
        }

        Otp::updateOrCreate(
            ['user_id' => $user->id],
            [
                'otp' => Hash::make($otp),
                'expires_at' => now()->addMinutes(5)
            ]
        );

        $logger = new Activity_logController();
        $message = "OTP generated for user e-mail {$user->email}.";

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
        session(['otp_user_id' => $user->id]);

        if(Auth::check()){
            return redirect('/authotp');
        }else{
            return redirect('/otp');
        }

    }

    public function resendOTP(Request $request){
        $userID = session('otp_user_id');

        if(!$userID){

            $message = "Session expired.";
            echo "<script>alert(" . json_encode($message) . ");</script>";

            return redirect('/');
        }

        $user = User::findOrFail($userID);

        //generates otp
        $chars = "0123456789";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated otp
        $otp = $notif_randnum;

        try {
            Mail::to($user->email)->send(new OtpMail($otp, $user->name));
        } catch (\Exception $e) {
            \Log::error('Failed to resend OTP email: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send OTP email. Please try again.']);
        }

        Otp::updateOrCreate(
            ['user_id' => $user->id],
            [
                'otp' => Hash::make($otp),
                'expires_at' => now()->addMinutes(5)
            ]
        );

        $logger = new Activity_logController();
        $message = "OTP resent for user e-mail {$user->email}.";

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
        
        
        if(!session('otp_user_id')){
            if(Auth::check()){
                return redirect('/');
            }else{
                return redirect('/login');
            }
        }

        $userID = session('otp_user_id');

        $otpRec = Otp::where('user_id', $userID)
        ->first();

        $user = User::findOrFail($userID);

        return view('User/otp', [
            'email' => $user->email,
            'expires_at' => $otpRec->expires_at->timestamp * 1000
        ]);
    }

    public function verifyOTP(Request $request){
        $request->validate(['otp' => 'required|numeric|digits:6']);

        $userID = session('otp_user_id');

        if(!$userID){

            $message = "Session expired.";
            echo "<script>alert(" . json_encode($message) . ");</script>";

            return redirect('/');
        }

        $user = User::findOrFail($userID);

        $otpRec = Otp::where('user_id', $user->id)
        ->where('expires_at', '>', now())
        ->first();

        if(!$otpRec){
            return back()->withErrors(['otp' => 'OTP expired/not found']);
        }elseif(!Hash::check($request->otp, $otpRec->otp)){
            return back()->withErrors(['otp' => 'Invalid OTP']);
        }

        $otpRec->delete();

        // Keep user ID in session for password reset
        session(['verified_user_id' => $userID]);
        session()->forget('otp_user_id');

        $logger = new Activity_logController();
        $message = "OTP verified by user e-mail {$user->email}.";

        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;

        $logger->logActivity($request, $repnotif_id, $message);

        if(Auth::check()){
            return redirect('/changepass')->with('success', 'OTP verified successfully');
        }else{
            return redirect('/resetpass')->with('success', 'OTP verified successfully');
        }
    }

    public function cancelOTP(Request $request){

        $userID = session('otp_user_id');

        if(!$userID){

            $message = "Session expired.";
            echo "<script>alert(" . json_encode($message) . ");</script>";

            return redirect('/');
        }

        $user = User::findOrFail($userID);

        $otpRec = Otp::where('user_id', $user->id)
        ->first();

        if(!$otpRec){
            return back()->withErrors(['otp' => 'OTP not found']);
        }

        $otpRec->delete();
        session()->forget('otp_user_id');

        return redirect('/');

    }

    /* reset pass for guest users
        change pass for auth users */

    public function resetPass(){

        if(!session('verified_user_id')){
            return redirect('/login');
        }

        $user = User::findOrFail(session('verified_user_id'));

        return view('User/resetpass', [
            'email' => $user->email
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

        $userID = session('verified_user_id');
    
        if (!$userID) {

            $message = "Session expired.";
            echo "<script>alert(" . json_encode($message) . ");</script>";

            return redirect('/login');
        }


        $user = User::findOrFail($userID);

        // Check if new password is same as old password
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'New password must be different from your current password.'
            ])->withInput();
        }
            
        $user->password = bcrypt($inputs['password']);
        $user->save();

        $logger = new Activity_logController();
        $message = "Password reset for user e-mail {$user->email}.";

        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;

        $logger->logActivity($request, $repnotif_id, $message);

        session()->forget('verified_user_id');

        $message = "Password reset successfully.";
        echo "<script>alert(" . json_encode($message) . ");</script>";

        return redirect('/login');
    }

    public function cancelResetPass(Request $request){

        session()->forget('verified_user_id');

        if(Auth::check()){
            return redirect('/');
        }else{
            return redirect('/login');
        }

    }

    public function changePass(){
        
        if(!session('verified_user_id')){
            return redirect('/');
        }

        $user = User::findOrFail(session('verified_user_id'));

        return view('User/changepass', [
            'email' => $user->email
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

        $userID = session('verified_user_id');
    
        if (!$userID) {

            $message = "Session expired.";
            echo "<script>alert(" . json_encode($message) . ");</script>";

            return redirect('/');
        }

        $user = User::findOrFail($userID);

        // Check if new password is same as old password
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'New password must be different from your current password.'
            ])->withInput();
        }
            
        $user->password = bcrypt($inputs['password']);
        $user->save();

        $logger = new Activity_logController();
        $message = "Password changed for user e-mail {$user->email}.";

        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;

        $logger->logActivity($request, $repnotif_id, $message);

        session()->forget('verified_user_id');

        return redirect('/')->with('success', 'Password changed successfully');

    }
}
