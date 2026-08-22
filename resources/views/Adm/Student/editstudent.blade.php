@extends('Layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Student</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('SAS.png') }}">

    <!-- Friggin bootstrap 5 -->
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
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .header-banner {
            background: #232528;
            color: white;
            padding: 28px 32px;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            text-align: center;
        }
        .header-banner h3 {
            margin: 0;
            font-weight: 700;
            font-size: 26px;
            letter-spacing: 0.5px;
        }
        .form-container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }
        .form-group {
            margin-bottom: 25px;
        }
        label {
            font-weight: 700;
            color: #232528;
            margin-bottom: 8px;
            display: block;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.8px;
        }
        input[type="text"], input[type="email"], select {
            width: 100%;
            padding: 14px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            background: white;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus, input[type="email"]:focus, select:focus {
            outline: none;
            border-color: #232528;
            box-shadow: 0 0 0 3px rgba(35, 37, 40, 0.1);
        }
        .btn-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        .btn {
            padding: 14px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 14px;
            border-radius: 8px;
            border: 2px solid transparent;
        }
        .btn-primary {
            background: #232528;
            border-color: #232528;
            color: white;
            flex-grow: 1;
        }
        .btn-primary:hover {
            background: white;
            color: #232528;
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
        .back-btn-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .text-danger {
            font-size: 14px;
            margin-top: 6px;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="back-btn-container">
            <a href='/admin/view-students'><button class="btn btn-secondary">← Back</button></a>
        </div>

        <div class="header-banner">
            <h3>✏️ Edit Student</h3>
        </div>

        <div class="form-container">

            <form action="{{ route('admin.edit-student', $student->id) }}" method="POST">

                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="fname">First Name</label>
                    <input type="text" name="fname" id="fname" value="{{ $student->fname }}" required>
                    @error('fname')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="lname">Last Name</label>
                    <input type="text" name="lname" id="lname" value="{{ $student->lname }}" required>
                    @error('lname')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" value="{{ $student->email }}" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" value="{{ $student->address }}" required>
                    @error('address')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="student_id">Student No.</label>
                    <input type="text" name="student_id" id="student_id" value="{{ $student->student_id }}" required>
                    @error('student_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="section_id">Section</label>
                    <select name="section_id" id="section" required>

                        @foreach($grades as $grade)
                            <optgroup label ="{{ $grade->roman_numeral }}">
                            @foreach($sections->where('grade_id', $grade->id) as $section)
                            <option value="{{ $section->id }}" {{ $student->section_id == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                            @endforeach
                            </optgroup>
                        @endforeach

                    </select>
                    @error('section_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="btn-container">
                    <button class="btn btn-primary" type="submit">Save Changes</button>
                </div>

            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-oBqDVmMz4fnFO9gybBogGzPztE1M5rZG/8Xlqh8fATrSWJZDmmW4Ll48dWkOVbCH"
    crossorigin="anonymous"></script>

    @endsection

</body>
</html>

