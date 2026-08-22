@extends('Layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User Account</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('SAS.png') }}">

    <!-- Friggin bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous">
    
    <link rel="stylesheet" href={{asset("css/regstyle.css")}}>
</head>
<body>

    <div class="homelink text-center">
    <a href="/admin"><button class="btn btn-secondary">🏠 Home</button></a>
    </div>

    <br>
    <div class="register-container">
        <div class="header-section">
            <div class="header-icon">👤</div>
            <h3>Create User/Instructor</h3>
            <div class="subtitle">Create an instructor account</div>
        </div>
        
        <form action="/createuser" method="POST">
            @csrf
            
            <div class="form-row">
                <div class="form-group">
                    <label for="fname">First Name <span class="required">*</span></label>
                    <input type="text" name="fname" id="fname" placeholder="Enter first name" required>
                    @error('fname')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="lname">Last Name <span class="required">*</span></label>
                    <input type="text" name="lname" id="lname" placeholder="Enter last name" required>
                    @error('lname')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required>
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <input type="hidden" name="password" id="password">

            <div class="form-group">
                <label for="subject_id">Subject <span class="required">*</span></label>
                <select name="subject_id" id="subject" required>

                    <option value="">-- Select Subject --</option>
                    @foreach($subjects as $subject)

                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>

                    @endforeach

                </select>
            </div>

            
            <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">Create Account</button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-body">
                    Confirm that the details you entered are correct?
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success flex-fill">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
                </div>
            </div>
            </div>
        </form>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-oBqDVmMz4fnFO9gybBogGzPztE1M5rZG/8Xlqh8fATrSWJZDmmW4Ll48dWkOVbCH"
    crossorigin="anonymous"></script>

    @endsection
</body>
</html>