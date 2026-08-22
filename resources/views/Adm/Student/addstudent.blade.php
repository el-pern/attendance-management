@extends('Layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Import Student Data</title>

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
        input[type="url"], select {
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
        .btn-success {
            background: #28a745;
            border-color: #28a745;
            color: white;
        }
        .btn-success:hover {
            background: white;
            color: #28a745;
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
        .modal-content {
            border: 2px solid #232528;
            border-radius: 12px;
        }
        .modal-header {
            background: #232528;
            color: white;
            border-bottom: none;
            border-radius: 12px 12px 0 0;
            padding: 20px 30px;
        }
        .modal-body {
            padding: 30px;
            font-size: 16px;
            color: #232528;
        }
        .modal-footer {
            border-top: 2px solid #e0e0e0;
            padding: 20px 30px;
        }
        .help-text {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 12px;
            margin-top: 12px;
            font-size: 13px;
            color: #555;
            border-left: 3px solid var(--primary-dark);
        }
        
        .help-text h6 {
            font-weight: 600;
            margin-bottom: 6px;
            font-size: 13px;
        }
        
        .help-text p {
            margin: 0;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="back-btn-container">
            <a href='/admin/view-students'><button class="btn btn-secondary">← Back</button></a>
        </div>
        
        <div class="header-banner">
            <h3>👤 Import Students</h3>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">
                <strong>Success!</strong> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <strong>Error!</strong> {{ session('error') }}
            </div>
        @endif

        <div class="form-container">

            <div class="help-text">
                    <h6>How to get CSV link:</h6>
                    <p>
                        1. Go to File → Share → Publish to Web<br>
                        2. Select sheet & .csv format<br>
                        3. Click 'Publish' and copy link
                    </p>
            </div>

            <form action="/admin/view-students" method="POST">

                @csrf
                
                <div class="form-group">
                    <label for="fname">GSheets Link</label>
                    <input type="url" name="sheet_url"
                    id="sheet_url" placeholder="Enter public CSV link" required>
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
                </div>

                <div class="btn-container">
                    <button class="btn btn-primary flex-grow-1" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">Import</button>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-body">
                        Do you confirm that GSheets CSV URL is valid and correct?
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

    @endsection

</body>
</html>

