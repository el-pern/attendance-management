<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Attendance::with(['student', 'section', 'subject'])->get();
    }

    public function headings(): array
    {
        return [
            'Last Name',
            'First Name',
            'Section',
            'Subject',
            'Status',
            'Date',
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->student->lname,
            $attendance->student->fname,
            $attendance->section->name,
            $attendance->subject->name,
            $attendance->status,
            $attendance->att_date->format('Y-m-d H:i:s'),
        ];
    }
}
