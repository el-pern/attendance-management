<?php

namespace App\Imports;

use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class AttendanceImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $student = Student::where('lname', $row['last_name'])
                         ->where('fname', $row['first_name'])
                         ->first();

        // Find section by name
        $section = Section::where('name', $row['section'])->first();

        // Find subject by name
        $subject = Subject::where('name', $row['subject'])->first();

        // Check if all required data exists
        if (!$student || !$section || !$subject) {
            Log::warning('Missing reference data for row', [
                'row' => $row,
                'student_found' => $student ? 'yes' : 'no',
                'section_found' => $section ? 'yes' : 'no',
                'subject_found' => $subject ? 'yes' : 'no',
            ]);
            return null; // Skip this record
        }

        return new Attendance([
            'student_id' => $student->id,
            'section_id' => $section->id,
            'subject_id' => $subject->id,
            'status' => $row['status'],
            'att_date' => \Carbon\Carbon::parse($row['date']),
        ]);
    }
}
