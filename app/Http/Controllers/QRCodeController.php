<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Qr;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function getQR(User $user, Request $request){

        $studnomsg = [
            'studno.required' => "Please enter the student number.",
            'studno.digits' => "Student number must be exactly 9 digits.",
            'studno.exists' => "Student number does not exist"
        ];

        $request->validate([
        'studno' => 'required|digits:9|exists:students,student_id',
        ], $studnomsg);

        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;

        $studno = $request->input('studno');
        $getStud = Student::where('student_id', $studno)->first();

        $exist_qr = Qr::where('student_id', $getStud->id)->first();

        if($exist_qr){
            return back()->withErrors(['studno' => 'QR code already exists for this student number.']);
        }
        
        Qr::create([
            'qr_key' => $repnotif_id,
            'student_id' => $getStud->id
        ]);

        $qrcode = QrCode::size(100)->generate($repnotif_id);

        $logger = new Activity_logController();
        $message = "Admin ".auth()->guard('admin')->user()->email." generated QR code for student no. ".$studno;

        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;

        $logger->logActivity($request, $repnotif_id, $message);

        return view('Adm/qr', compact('qrcode'));
    }
}
