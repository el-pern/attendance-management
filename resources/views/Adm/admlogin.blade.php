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

    <title>Admin Login</title>

    <style>
        body {
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .container {
            background: white;
            border: 2px solid #232528;
            padding: 40px;
            max-width: 500px;
            width: 100%;
        }
        h3 {
            color: #232528;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
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
        .required {
            color: #232528;
        }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #232528;
            background: white;
            font-size: 16px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        input[type="email"]:focus, input[type="password"]:focus {
            outline: none;
            background: #f5f5f5;
        }
        .otp-link {
            display: block;
            text-align: right;
            margin-top: -15px;
            margin-bottom: 20px;
        }
        .otp-link a {
            color: #232528;
            text-decoration: underline;
            font-size: 14px;
            text-transform: none;
            letter-spacing: normal;
        }
        .otp-link a:hover {
            text-decoration: none;
        }
        button {
            width: 100%;
            padding: 12px 30px;
            font-weight: 600;
            background: #232528;
            border: 2px solid #232528;
            color: white;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 14px;
            cursor: pointer;
        }
        button:hover {
            background: white;
            color: #232528;
        }
        .text-danger {
            font-size: 14px;
            margin-top: -15px;
            margin-bottom: 15px;
            color: #232528;
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #232528;
        }
        .register-link a {
            color: #232528;
            font-weight: 600;
            text-decoration: underline;
        }
        .register-link a:hover {
            text-decoration: none;
        }
    </style>
</head>
<body>
    
    <div class="container">
    <h3>Admin Login</h3>

    <form action="/adminlogin" method="POST">
        @csrf
        <label>E-mail <span class="required">*</span></label>
        <input type="email" name="email" required>
        @error('email')
            <div class="text-danger">{{ $message }}</div>
        @enderror

        <label>Password <span class="required">*</span></label>
        <input type="password" name="password" required>
        <label class="otp-link"><a href="/admforgot">Forgot password?</a></label>
        @error('password')
            <br><div class="text-danger">{{ $message }}</div>
        @enderror

        <button>Login</button>

    </form>

    </div>

</body>
</html>