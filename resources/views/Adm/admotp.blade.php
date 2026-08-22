@extends('Layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Friggin bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous">
    
    <title>Admin OTP Verification</title>
    <style>
        body {
            background: #f5f5f5;
            min-height: 100vh;
            padding: 40px 0;
        }
        .container {
            background: white;
            border: 2px solid #232528;
            padding: 40px;
            max-width: 600px;
            margin: 0 auto;
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
        .countdown {
            font-weight: 700;
            color: #232528;
        }
        .countdown.expired {
            color: #232528;
            font-weight: 700;
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
        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #232528;
            background: white;
            font-size: 16px;
            transition: all 0.3s ease;
            text-align: center;
            letter-spacing: 5px;
            font-weight: 600;
        }
        input[type="text"]:focus {
            outline: none;
            background: #f5f5f5;
        }
        .btn-container {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
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
            background: #232528;
            border: 2px solid #232528;
            color: white;
        }
        .btn-success:hover {
            background: white;
            color: #232528;
            border: 2px solid #232528;
        }
        .text-danger {
            font-size: 14px;
            margin-top: 5px;
            color: #232528;
        }
        .resend {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #232528;
            color: #232528;
            font-size: 14px;
        }
        .resend form {
            display: inline;
        }
        .resend button {
            background: none;
            border: none;
            color: #232528;
            font-weight: 600;
            text-decoration: underline;
            cursor: pointer;
            padding: 0;
            font-size: 14px;
            text-transform: none;
            letter-spacing: normal;
        }
        .resend button:hover {
            text-decoration: none;
        }
        .modal-content {
            border: 2px solid #232528;
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
        <h3>Verify OTP</h3>
        <p>Enter the 6-digit OTP sent to {{ $email }}.<br>
            OTP expires in <span id="countdown" class="countdown">Loading...</span>.
        </p>

        <form
            @if(Auth::check()) 
                action="/authadmotp/verify"
            @else
                action="/admotp/verify"
            @endif
         method="POST">
            @csrf
            <div class="form-group">
                <label for="otp">Enter OTP</label>
                <input type="text" name="otp" id="otp" maxlength="6" required>
                @error('otp')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="btn-container">
                <button type="submit" class="btn btn-primary flex-grow-1">Confirm</button>
            </div>
        </form>
        
        @if(!session('otp_regadmin_id'))
        <form
        
        @if(Auth::check())
            action="/authadmotp/cancel"
        @else
            action="/admotp/cancel"
        @endif
        
        method="POST">
            @csrf
            <div class="btn-container">
                <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"
                class="btn btn-secondary flex-grow-1">Cancel</button>
            </div>
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-body">
                        Are you sure you want to cancel OTP verification?
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Yes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    </div>
                    </div>
                </div>
                </div>
        </form>
        @endif
        
        <div class="resend">
            @if(Auth::check())
                Didn't receive the OTP?
                <form action="/authadmotp/resend" method="POST">
                    @csrf
                    <button type="submit">Resend</button>
                </form>
            @else
                Didn't receive the OTP?
                <form action="/admotp/resend" method="POST">
                    @csrf
                    <button type="submit">Resend</button>
                </form>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>

        const expiresAt = {{ $expires_at }};

        const countdownElement = document.getElementById('countdown');

        const countdown = setInterval(function() {

            const now = new Date().getTime();
            const distance = expiresAt - now;

            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            

            countdownElement.innerHTML = minutes + ":" + (seconds < 10 ? "0" : "") + seconds;

            if(distance < 0){
                //OTP Expired

                countdownElement.innerHTML = "EXPIRED";
                countdownElement.classList.add('expired');
                clearInterval(countdown);
                alert('⌛ OTP has expired. Please request a new one.');

            }
            
        }, 1000);


    </script>

    @endsection
</body>
</html>

