@extends('Layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Section</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('SAS.png') }}">

    <!-- Friggin bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous">
    <style>
        body {
            background: #f5f5f5;
            min-height: 100vh;
            padding: 40px 0;
        }
        .form-container {
            background: white;
            border: 2px solid #232528;
            padding: 40px;
            max-width: 600px;
            margin: 0 auto;
        }
        h3 {
            color: #232528;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .form-group {
            margin-bottom: 25px;
        }
        label {
            font-weight: 600;
            color: #232528;
            margin-bottom: 8px;
            display: block;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }
        select, input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #232528;
            background: white;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        select:focus, input[type="text"]:focus {
            outline: none;
            background: #f5f5f5;
        }
        .btn-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        .btn {
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 14px;
        }
        .btn-primary {
            background: #232528;
            border: 2px solid #232528;
            color: white;
            flex-grow: 1;
        }
        .btn-primary:hover {
            background: white;
            color: #232528;
            border: 2px solid #232528;
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
            margin-top: 5px;
            color: #232528;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="back-btn-container">
            <a href='/admin/view-sections'><button class="btn btn-secondary">← Back</button></a>
        </div>

        <div class="form-container">
            <h3>Edit Section</h3>

            <form action="{{ route('admin.edit-section', $section->id) }}" method="POST">

                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="grade_id">Grade Level</label>
                    <select name="grade_id" id="grade" required>

                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}" {{ $section->grade_id == $grade->id ? 'selected' : '' }}>{{ $grade->roman_numeral }}</option>
                        @endforeach

                    </select>
                    @error('grade_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name">Section Name</label>
                    <input type="text" name="name" id="name" value="{{ $section->name }}" required>
                    @error('name')
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

