@extends('Layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Guardian</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('SAS.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        .page-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .header-banner {
            background: #232528;
            color: white;
            padding: 26px 30px;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }
        .header-banner h3 {
            margin: 0;
            font-weight: 700;
            font-size: 26px;
            letter-spacing: 0.5px;
        }
        .card-shell {
            background: white;
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }
        label {
            font-weight: 700;
            color: #232528;
            margin-bottom: 6px;
            display: block;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.8px;
        }
        input, select {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.25s ease;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #232528;
            box-shadow: 0 0 0 3px rgba(35,37,40,0.12);
        }
        .form-group {
            margin-bottom: 18px;
        }
        .btn {
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-radius: 10px;
            padding: 12px 18px;
        }
        .btn-secondary {
            background: white;
            border: 2px solid #232528;
            color: #232528;
        }
        .btn-secondary:hover {
            background: #232528;
            color: white;
        }
        .btn-primary {
            background: #232528;
            border: 2px solid #232528;
            color: white;
        }
        .btn-primary:hover {
            background: white;
            color: #232528;
        }
        .btn-success {
            background: #28a745;
            border: 2px solid #28a745;
        }
        .text-danger {
            margin-top: 6px;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <div class="page-container">
        <div class="header-banner">
            <h3>✏️ Edit Parent/Guardian</h3>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href='/admin/guardians' class="btn btn-secondary">← Back</a>
        </div>
        
        <div class="card-shell">
            <form action="{{ route('admin.edit-guardian', $guardian->id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" value="{{ $guardian->name }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" value="{{ $guardian->email }}" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="grade_id">Student Grade Level</label>
                    <select name="grade_id" id="grade" required>

                        @foreach($grades as $grade)
                                <option value="{{ $grade->id }}"
                                {{ $guardian->student->section->grade_id == $grade->id ? 'selected' : '' }}>
                                {{ $grade->roman_numeral }}</option>
                        @endforeach

                    </select>
                </div>

                <div class="form-group">
                    <label for="student_id">Student</label>
                    <select name="student_id" id="student" required>

                        <option value="">-- Select Student --</option>

                    </select>
                </div>


                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-primary w-100" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">Save Changes</button>
                </div>

                 <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-body">
                        Save changes for this guardian?
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Yes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    </div>
                    </div>
                </div>
                </div>
            

            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-oBqDVmMz4fnFO9gybBogGzPztE1M5rZG/8Xlqh8fATrSWJZDmmW4Ll48dWkOVbCH"
    crossorigin="anonymous"></script>

    <script>
        const allStudents = @json($students);
        const gradesData = @json($grades);
        const currentStudentId = {{ $guardian->student_id }};

        function populateStudents(gradeId, selectStudentId = null) {

            //students to select within grade selected/clicked
            const studentSel = document.getElementById('student');
            

            studentSel.innerHTML = '<option value="">-- Select Student --</option>';

            if(!gradeId) return;

            // Filter students whose section's grade_id matches selected grade
            const studentsInGrade = allStudents.filter(student => {
                return student.section && student.section.grade_id === gradeId;
            });

            if(studentsInGrade.length > 0) {
            // Enable the student dropdown
            studentSel.disabled = false;

            // Group students by section
            const studentsBySection = {};
            
            studentsInGrade.forEach(student => {
                const sectionName = student.section.name;
                if(!studentsBySection[sectionName]) {
                    studentsBySection[sectionName] = [];
                }
                studentsBySection[sectionName].push(student);
            });

            // Sort section names alphabetically
            const sortedSections = Object.keys(studentsBySection).sort();

            // Create optgroups for each section
            sortedSections.forEach(sectionName => {
                const optgroup = document.createElement('optgroup');
                optgroup.label = sectionName;

                // Add students to this section's optgroup
                studentsBySection[sectionName].forEach(student => {
                    const option = document.createElement('option');
                    option.value = student.id;
                    option.textContent = `${student.lname}, ${student.fname}`;

                    if(selectStudentId && student.id === selectStudentId) {
                        option.selected = true;
                    }

                    optgroup.appendChild(option);
                });

                studentSel.appendChild(optgroup);
            });
        }

        }

        // Listen for grade changes
        document.getElementById('grade').addEventListener('change', function(){
            const gradeId = parseInt(this.value);
            populateStudents(gradeId);
        });

        // Populate students on page load with the current guardian's student selected
        document.addEventListener('DOMContentLoaded', function() {
            const currentGradeId = parseInt(document.getElementById('grade').value);
            populateStudents(currentGradeId, currentStudentId);
        });

    </script>

    @endsection
    
</body>
</html>

