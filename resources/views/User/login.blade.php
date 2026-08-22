<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance System | Login</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('SAS.png') }}">

    <!-- Friggin bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous">

    <link rel="stylesheet" href={{ asset('css/loginstyle.css') }}>
</head>
<body>
    <div class="login-container">
        <div class="login-banner">
            <img src="{{ asset('SAS.png') }}" alt="SAS Logo" class="header-logo">
        </div>
        
        <div class="login-form-container">
            <h3>Login</h3>
            <form action="/login" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" name="email" id="email" placeholder="Enter your email" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div class="otp-link"><a href="/forgot">Forgot password?</a></div>
                </div>
                
                <button type="submit">Login</button>
            </form>
            
        </div>
    </div>
</body>
</html>

