@extends('Layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" type="image/x-icon" href="{{ asset('SAS.png') }}">

    <!-- Friggin bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous">

    <title>Add Instructor</title>
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        .header-banner {
            background: #232528;
            color: white;
            padding: 25px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header-banner h5 {
            margin: 0 0 10px 0;
            font-weight: 600;
            font-size: 16px;
            opacity: 0.9;
        }
        .header-banner h3 {
            margin: 0;
            font-weight: 700;
            font-size: 28px;
            letter-spacing: 1px;
        }
        .content-container {
            background: white;
            border-radius: 10px;
            padding: 40px;
            max-width: 700px;
            margin: 0 auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
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
        input[type="text"], select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            background: white;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus, select:focus {
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
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 14px;
            border-radius: 6px;
        }
        .btn-primary {
            background: #232528;
            border: 2px solid #232528;
            color: white;
            flex: 1;
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
        .btn-success {
            background: #28a745;
            border: 2px solid #28a745;
            color: white;
        }
        .btn-success:hover {
            background: white;
            color: #28a745;
            border: 2px solid #28a745;
        }
        .btn-danger {
            background: #dc3545;
            border: 2px solid #dc3545;
            color: white;
            width: 100%;
            margin-top: 20px;
        }
        .btn-danger:hover {
            background: white;
            color: #dc3545;
            border: 2px solid #dc3545;
        }
        .text-danger {
            font-size: 14px;
            margin-top: 5px;
            color: #dc3545;
        }
        .modal-content {
            border: 2px solid #232528;
            border-radius: 10px;
        }
        .modal-header {
            background: #232528;
            color: white;
            border-bottom: none;
            border-radius: 8px 8px 0 0;
            padding: 20px 30px;
        }
        .modal-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }
        .modal-body {
            padding: 30px;
            font-size: 16px;
            color: #232528;
        }
        .modal-footer {
            border-top: 2px solid #232528;
            padding: 20px 30px;
        }
    </style>
</head>
<body>
    
    <div class="container">
        <div class="header-banner">
            <h5>👤 {{ auth()->user()->name }}</h5>
            <h3>Finish Account Setup</h3>
        </div>

        <div class="content-container">
            <form action="{{ route('instructor.setup') }}" method="POST">

                @csrf
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" placeholder="Enter your address" required>
                    @error('address')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="section_id">Section</label>
                    <select name="section_id" id="section" required>

                        <option value="">-- Select Section --</option>

                        @foreach($grades as $grade)
                            <optgroup label ="{{ $grade->roman_numeral }}">
                            @foreach($sections->where('grade_id', $grade->id) as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                            </optgroup>
                        @endforeach

                    </select>
                    @error('section_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="btn-container">
                    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">Submit</button>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-body">
                        Do you confirm that details entered are correct?
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Yes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    </div>
                    </div>
                </div>
                </div>

            </form>

            <form action="/logout" method="POST">
                @csrf
                <button class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#getoutModal">
                    🚪 Logout
                </button>

                <!-- Modal -->
                <div class="modal fade" id="getoutModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body text-center py-4">
                                <p style="font-size: 18px; margin: 0;">Are you sure you want to log out?</p>
                            </div>
                            <div class="modal-footer border-top-0 pt-0">
                                <button type="button" class="btn btn-secondary flex-fill" data-bs-dismiss="modal">Cancel</button>
                                <div class="d-flex gap-2 w-100">
                                    <button type="submit" class="btn btn-danger flex-fill">Logout</button>
                                </div>
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

</body>
</html>

@endsection