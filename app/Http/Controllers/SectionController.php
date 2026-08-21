<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Section;
use App\Http\Controllers\Activity_logController;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    //crud for sections

    public function viewGrades(){

        //for viewing grades in dropdown for add section page

        $grades = Grade::get();
        return view('Adm/Section/addsection', compact('grades'));
    }

    public function viewSections(){

        $sections = Section::orderBy('grade_id')->orderBy('name')->get();

        return view('Adm/Section/section', compact('sections'));
    }

    public function addSection(Request $request){

        $inputs = $request->validate([
            'name' => ['required', 'min:1', 'max:20'],
            'grade_id' => ['required']
        ]);

        Section::create($inputs);

        $logger = new Activity_logController();
        $message = "Admin ".auth()->guard('admin')->user()->email." added section ".$request->name;

        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;
        $logger->logActivity($request, $repnotif_id, $message);

        return redirect('/admin/view-sections')->with('success', 'Section added successfully');
    }

    public function editSection(Section $section){

        $grades = Grade::all();

        return view('Adm/Section/editsection', 
        compact('section', 'grades'));
    }

    public function updSection(Section $section, Request $request){

        $oldName = $section->name;
        $oldGrade = $section->grade_id;

        $inputs = $request->validate([
            'name' => ['required', 'min:1', 'max:20'],
            'grade_id' => ['required']
        ]);

        if($oldName !== $inputs['name'] || $oldGrade != $inputs['grade_id']){
            $section->name = $inputs['name'];
            $section->grade_id = $inputs['grade_id'];
            $section->save();

            $logger = new Activity_logController();
            $message = "Admin ".auth()->guard('admin')->user()->email." updated section ".$oldName." to ".$request->name;

            //generates random notif id
            $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $notif_randnum = "";
            for($i = 0; $i < 6; $i++){
                $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
            }
            //generated notif id
            $repnotif_id = $notif_randnum;
            $logger->logActivity($request, $repnotif_id, $message);

            return redirect('/admin/view-sections')->with('success', 'Section updated successfully');
        }else{
            return back()->with('info', 'No changes were made');
        }
    }

    public function deleteSection(Section $section, Request $request){

        $sectionName = $section->name;
        $section->delete();

        $logger = new Activity_logController();
        $message = "Admin ".auth()->guard('admin')->user()->email." deleted section ".$sectionName;

        //generates random notif id
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $notif_randnum = "";
        for($i = 0; $i < 6; $i++){
            $notif_randnum .= $chars[random_int(0, strlen($chars) - 1)];
        }
        //generated notif id
        $repnotif_id = $notif_randnum;
        $logger->logActivity($request, $repnotif_id, $message);

        return redirect('/admin/view-sections')->with('success', 'Section deleted successfully');
    }
}
