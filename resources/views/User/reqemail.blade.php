<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>E-mail Request</title>

    <!-- Friggin bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .email-container {
            background: white;
            border-radius: 15px;
            padding: 50px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .header-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        h3 {
            color: #232528;
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 28px;
            letter-spacing: 1px;
        }
        p {
            color: #666;
            margin-bottom: 30px;
            font-size: 15px;
            line-height: 1.6;
        }
        input[type="email"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            background: white;
            font-size: 16px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        input[type="email"]:focus {
            outline: none;
            border-color: #232528;
            box-shadow: 0 0 0 3px rgba(35, 37, 40, 0.1);
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
            border-radius: 6px;
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
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(35, 37, 40, 0.3);
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
            color: #dc3545;
            text-align: left;
        }
    </style>
</head>
<body>
    
    <div class="email-container">
        <div class="header-icon">📧</div>
        <h3>E-mail</h3>
        <p>Please enter your e-mail for OTP verification.</p>

        <form action="/otp" method="POST">
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
            <a href="/login" style="flex: 1;"><button class="btn btn-secondary" style="width: 100%;">Cancel</button></a>
        </div>
    </div>

</body>
</html>

