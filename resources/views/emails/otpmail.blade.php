<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OTP Verification</title>

    <link rel="stylesheet" href={{ asset("css/otpmailstyle.css") }}>
</head>
<body>
    
    <div class="email-container">
        <div class="header">
            <h1>OTP Verification</h1>
        </div>
        
        <div class="content">
            <p class="message">Hello {{ $userName }},</p>
            
            <p class="message">
                You have requested a verification code. Use the OTP below to proceed:
            </p>
            
            <div class="otp-box">
                {{ $otp }}
            </div>
            
            <p class="message">
                This OTP will expire in <strong>5 minutes</strong>.
            </p>
            
            <div class="warning">
                <strong>Security Notice:</strong> If you didn't request this OTP, please ignore this email and ensure your account is secure.
            </div>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; {{ date('Y') }} Student Attendance System. All rights reserved.</p>
        </div>
    </div>

</body>
</html>