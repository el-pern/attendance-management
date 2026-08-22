<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registered</title>
</head>
<body>
    
    <div class="email-container">
        <div class="header">
            <h1>Account Created</h1>
        </div>
        
        <div class="content">
            <p class="message">Hello {{ $userName }},</p>
            
            <p class="message">
                You can now be able to log in to your user account.
                <br><strong>E-mail: </strong>{{ $userMail }}
                <br><strong>Password: </strong>{{ $pass }}
            </p>
            
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; {{ date('Y') }} Student Attendance System. All rights reserved.</p>
        </div>
    </div>

</body>
</html>