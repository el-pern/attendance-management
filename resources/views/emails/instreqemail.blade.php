<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Instructor Request</title>

</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Instructor Request</h1>
        </div>
        
        <div class="content">
            <p class="message">Hello {{ $admin }},</p>
            
            <p class="message">
                Instructor {{ $instructor }} has requested to <strong>{{ $inst_request }}</strong>.
                <br>Student: <strong>{{ $student_name }} - {{ $student_id }}</strong>
                <br>Reason: <strong>{{ $reason }}</strong>.
            </p>
            

            <p class="message">
                Thank you!
            </p>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; {{ date('Y') }} Student Attendance System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>