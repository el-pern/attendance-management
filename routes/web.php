<?php

use App\Models\Subject;
use App\Models\Instructor;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SuspendController;
use App\Http\Controllers\VacationController;
use App\Http\Controllers\InstructorController;


//user routes

//routes only accessible if account setup is complete
Route::middleware(['auth', 'instructor.setup'])->group(function(){

    Route::get('/', [InstructorController::class, 'home']);
    Route::post('/get-attendance-data', [InstructorController::class, 'getAttendanceData'])
    ->name('instructor.get-attendance-data');

    Route::post('/authotp', [UserController::class, 'sendOTP']);
    Route::get('/authotp', [UserController::class, 'viewOTPPage']);
    Route::post('/authotp/resend', [UserController::class, 'resendOTP'])
    ->name('auth-otp.resend');
    Route::post('/changepass', [UserController::class, 'verifyOTP']);

    Route::get('/changepass', [UserController::class, 'changePass']);
    Route::post('/changepass/cancel', [UserController::class, 'cancelResetPass']);
    Route::post('/changepass/confirm', [UserController::class, 'changePassFR']);

    Route::get('/edit-profile/{instructor}', [InstructorController::class, 'editInstructor'])
    ->name('instructor.edit-profile');
    Route::put('/edit-profile/{instructor}', [InstructorController::class, 'updInstructor'])
    ->name('instructor.update-profile');

    Route::get('/instreq', [InstructorController::class, 'viewInstReqForm']);
    Route::post('/instreq', [InstructorController::class, 'sendInstRequest']);

    Route::get('/checkattendance', [InstructorController::class, 'viewAttendance']);
    Route::post('/checkattendance', [InstructorController::class, 'addAttendance']);
    Route::put('/editattendance/{att}', [InstructorController::class, 'editAttendance'])
    ->name('edit-att');

    Route::post('/shift', [InstructorController::class, 'setShift']);


    Route::post('/suspend', [SuspendController::class, 'suspendClass']);
    Route::delete('/liftsus/{sus}', [SuspendController::class, 'liftSuspension']);

    //schedule routes for class schedules
    Route::post('/addsched', [InstructorController::class, 'addSchedule']);
    Route::put('/editsched/{schedule}', [InstructorController::class, 'editSchedule'])
    ->name('edit-sched');

});

Route::post('/', [UserController::class, 'cancelOTP']);

Route::middleware(['guest'])->group(function() {

    Route::get('/forgot', [UserController::class, 'viewEmailForm']);
    Route::post('/otp', [UserController::class, 'sendOTP']);

    Route::get('/otp', [UserController::class, 'viewOTPPage']);
    Route::post('/otp/resend', [UserController::class, 'resendOTP'])
    ->name('otp.resend');

    Route::post('/resetpass', [UserController::class, 'verifyOTP']);

    Route::get('/resetpass', [UserController::class, 'resetPass']);
    Route::post('/resetpass/cancel', [UserController::class, 'cancelResetPass']);
    Route::post('/resetpass/confirm', [UserController::class, 'resetPassFR']);

});

Route::get('/instructor/fill', [InstructorController::class, 'viewSetupForm'])
->middleware(['auth', 'instructor.setup']);

Route::post('/instructor/setup', [InstructorController::class, 'addInstructor'])
->name('instructor.setup');

Route::get('/login', function () {
    return view('User/login');
})->name('login');
Route::post('/login', [UserController::class, 'login']);

Route::middleware(['web'])->group(function (){
    Route::post('/logout', [UserController::class, 'logout']);}
);


//admin routes
Route::get('/adminlogin', function () {
        return view('Adm/admlogin');
    })->name('adminlogin');
    Route::post('/adminlogin', [AdminController::class, 'login']);

//admin otp

Route::get('/admforgot', [AdminController::class, 'viewEmailForm']);
Route::post('/admotp', [AdminController::class, 'sendOTP']);
Route::get('/admotp', [AdminController::class, 'viewOTPPage']);
Route::post('/admotp/resend', [AdminController::class, 'resendOTP'])
->name('admotp.resend');
Route::post('/admotp/verify', [AdminController::class, 'verifyOTP']);
Route::post('/admotp/cancel', [AdminController::class, 'cancelOTP']);
Route::get('/admresetpass', [AdminController::class, 'resetPass']);

Route::post('/admresetpass/cancel', [AdminController::class, 'cancelResetPass']);
Route::post('/admresetpass/confirm', [AdminController::class, 'resetPassFR']);


Route::middleware(['auth:admin'])->group(function () {

    Route::get('/admin', [AdminController::class, 'index'])->middleware('auth:admin');
    Route::get('/admin', [AdminController::class, 'home']);

    Route::post('/adminlogout', [AdminController::class, 'logout']);

    Route::post('/authadmotp', [AdminController::class, 'sendOTP']);
    Route::get('/authadmotp', [AdminController::class, 'viewOTPPage']);
    Route::post('/authadmotp/resend', [AdminController::class, 'resendOTP'])
    ->name('auth-admotp.resend');
    Route::post('/authadmotp/verify', [AdminController::class, 'verifyOTP']);
    Route::post('/authadmotp/cancel', [AdminController::class, 'cancelOTP']);

    Route::get('/admchangepass', [AdminController::class, 'changePass']);
    Route::post('/admchangepass/cancel', [AdminController::class, 'cancelResetPass']);
    Route::post('/admchangepass/confirm', [AdminController::class, 'changePassFR']);

    Route::get('/admin/verify-users', [AdminController::class, 'viewUnverified'])
    ->name('admin.verify.users');
    Route::post('/admin/verify-user/{user}', [AdminController::class, 'verifyUser'])
    ->name('admin.verify.users');

    Route::delete('/admin/decline-user/{user}', [AdminController::class, 'declineUser'])
    ->name('admin.decline.users');

    Route::get('/createuser', function(){

        $subjects = Subject::all();

        return view('User/reg', compact('subjects'));
    });
    Route::post('/createuser', [UserController::class, 'register']);

    Route::get('/attcsv', [InstructorController::class, 'viewCSVPage']);
    Route::post('/import', [InstructorController::class, 'importAttendance']);
    Route::post('/export', [InstructorController::class, 'exportAttendance']);

    Route::get('/admin/act-logs', [AdminController::class, 'viewAllActLogs']);
    Route::get('/admin/recent-logs', [AdminController::class, 'viewActLogs']);


    Route::get('/admin/qr-code', function(){
    return view('Adm/qr');
    });
    Route::post('/admin/qr-code', [QRCodeController::class, 'getQR'])->name('admin.qr-code');

    Route::get('/admin/reqnotifs', [AdminController::class, 'viewReqNotifs']);
    Route::get('/admin/allreqnotifs', [AdminController::class, 'viewAllReqNotifs']);

    //crud for classes (students, sections, instructors)

    Route::get('/admin/view-classes', [AdminController::class, 'viewClasses']);
    Route::post('/admin/suspend', [SuspendController::class, 'suspendAllClasses']);

    Route::get('/admin/holidays', [HolidayController::class, 'viewHolidays']);
    Route::get('/admin/addholiday', [HolidayController::class, 'viewHolidayForm']);
    Route::post('/admin/holidays', [HolidayController::class, 'addHoliday']);
    Route::get('/admin/edit-holiday/{holiday}', [HolidayController::class, 'editHoliday'])
    ->name('admin.edit-holiday');
    Route::put('/admin/edit-holiday/{holiday}', [HolidayController::class, 'updHoliday'])
    ->name('admin.upd-holiday');
    Route::delete('/admin/del-holiday/{holiday}', [HolidayController::class, 'delHoliday'])
    ->name('admin.del-holiday');

    Route::get('/admin/vacations', [VacationController::class, 'viewVacations']);
    Route::get('/admin/addvacation', [VacationController::class, 'viewVacationForm']);
    Route::post('/admin/vacations', [VacationController::class, 'addVacation']);
    Route::get('/admin/edit-vacation/{vacation}', [VacationController::class, 'editVacation'])
    ->name('admin.edit-vacation');
    Route::put('/admin/edit-vacation/{vacation}', [VacationController::class, 'updVacation'])
    ->name('admin.upd-vacation');
    Route::delete('/admin/del-vacation/{vacation}', [VacationController::class, 'deleteVacation'])
    ->name('admin.del-vacation');


    Route::get('/admin/add-section', [SectionController::class, 'viewGrades']);
    Route::post('/admin/view-sections', [SectionController::class, 'addSection']);
    Route::get('/admin/view-sections', [SectionController::class, 'viewSections']);

    /*editSection to view edit section page
    updSection to update the section in the database*/
    Route::get('/admin/edit-section/{section}', [SectionController::class, 'editSection'])
    ->name('admin.edit-section');
    Route::put('/admin/edit-section/{section}', [SectionController::class, 'updSection'])
    ->name('admin.update-section');
    Route::delete('/admin/delete-section/{section}', [SectionController::class, 'deleteSection'])
    ->name('admin.delete-section');

    Route::get('/admin/view-students', [StudentController::class, 'viewStudents']);
    Route::get('/admin/arc-students', [StudentController::class, 'viewArcStuds']);
    Route::get('/admin/add-student', [StudentController::class, 'viewSections']);
    Route::post('/admin/view-students', [StudentController::class, 'addStudent']);

    /*editStudent to view edit student page
    updStudent to update the student in the database*/
    Route::get('/admin/edit-student/{student}', [StudentController::class, 'editStudent'])
    ->name('admin.edit-student');
    Route::put('/admin/edit-student/{student}', [StudentController::class, 'updStudent'])
    ->name('admin.update-student');
    Route::delete('/admin/delete-student/{student}', [StudentController::class, 'deleteStudent'])
    ->name('admin.delete-student');
    Route::delete('/admin/restore-student/{arcstud}', [StudentController::class, 'restoreStudent'])
    ->name('admin.restore-student');
    Route::post('/admin/dropstud/{arcstud}', [StudentController::class, 'markAsDropped'])
    ->name('admin.mark-drop');

    Route::get('/admin/guardians', [StudentController::class, 'viewGuardians']);
    Route::get('/admin/edit-guardian/{guardian}', [StudentController::class, 'editGuardian'])
    ->name('admin.edit-guardian');
    Route::put('/admin/edit-guardian/{guardian}', [StudentController::class, 'updGuardian'])
    ->name('admin.upd-guardian');

    Route::post('/sendorientemail/{guardian}', [StudentController::class, 'sendOrientMail']);

    //instructor routes for admin
    Route::get('/admin/view-instructors', [InstructorController::class, 'viewInstructors']);

    Route::get('/admin/view-inst-subjects', [InstructorController::class, 'viewInstSubs']);
    Route::get('/admin/assign-inst-subject', [InstructorController::class, 'viewInstSubsForm']);
    Route::post('/admin/view-inst-subjects', [InstructorController::class, 'addInstSub']);

    Route::get('/admin/edit-inst-subject/{inst_sub}', [InstructorController::class, 'editSubject'])
    ->name('admin.edit-subject');
    Route::put('/admin/edit-inst-subject/{inst_sub}', [InstructorController::class, 'updSubject'])
    ->name('admin.update-subject');

    Route::delete('/admin/delete-instructor/{instructor}', [InstructorController::class, 'deleteInstructor'])
    ->name('admin.delete-instructor');

});