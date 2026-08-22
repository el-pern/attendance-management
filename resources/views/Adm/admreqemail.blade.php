<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>E-mail Request</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('SAS.png') }}">

    <!-- Friggin bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous">
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
            margin-bottom: 20px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        p {
            text-align: center;
            color: #232528;
            margin-bottom: 30px;
            font-size: 14px;
        }
        input[type="email"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #232528;
            background: white;
            font-size: 16px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        input[type="email"]:focus {
            outline: none;
            background: #f5f5f5;
        }
        .btn-container {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        .btn {
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 14px;
            flex: 1;
        }
        .btn-primary {
            background: #232528;
            border: 2px solid #232528;
            color: white;
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
        .text-danger {
            font-size: 14px;
            margin-top: -15px;
            margin-bottom: 15px;
            color: #232528;
        }
    </style>
</head>
<body>
    
    <div class="container">
        <h3>Admin E-mail</h3>
        <p>Please enter your e-mail for OTP verification.</p>

        <form action="/admotp" method="POST">
            @csrf
            <input type="email" name="email" placeholder="Enter your email" required>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <div class="btn-container">
                <button class="btn btn-primary" type="submit">Confirm</button>
            </div>
        </form>
        <div class="btn-container">
            <a href="/adminlogin" style="flex: 1;"><button class="btn btn-secondary" style="width: 100%;">Cancel</button></a>
        </div>
    </div>

</body>
</html>

