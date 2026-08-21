<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Guardian;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\DB;

class StudentImport implements ToModel, WithHeadingRow, WithValidation
{

    protected $sectionId;

    public function __construct($sectionId)
    {
        $this->sectionId = $sectionId;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Use database transaction to ensure both student and guardian are created together
        return DB::transaction(function () use ($row) {
            $student = Student::create([
                'lname' => $row['lname'] ?? $row['last_name'] ?? null,
                'fname' => $row['fname'] ?? $row['first_name'] ?? null,
                'email' => $row['email'] ?? null,
                'address' => $row['address'] ?? null,
                'student_id' => $row['student_id'] ?? null,
                'section_id' => $this->sectionId,
            ]);

            if (!empty($row['guardian']) || !empty($row['guardian_email'])) {
                Guardian::create([
                    'name' => $row['guardian'] ?? $row['guardian_name'] ?? null,
                    'email' => $row['guardian_email'] ?? $row['guardian_e'] ?? null,
                    'student_id' => $student->id,
                ]);
            }

            return $student;
        });
    }


    /**
     * Define validation rules for the import
     */
    public function rules(): array
    {
        return [
            'email' => 'nullable|email',
            'guardian_email' => 'nullable|email',
            'student_id' => 'required',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'email.email' => 'The student email must be a valid email address.',
            'guardian_email.email' => 'The guardian email must be a valid email address.',
            'student_id.required' => 'Student ID is required.',
        ];
    }
}
