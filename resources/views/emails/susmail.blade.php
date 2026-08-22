<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Classes Suspended</title>
</head>
<body>

    <div class="email-container">
        <div class="header">
            <h1>Class Suspension</h1>
        </div>
        
        <div class="content">
            <p class="message">Greetings!</p>
            
            <p class="message">
                We are informing you that class for Mr./Ms./Mrs. {{ $instructor }}
                is suspended for today due to their absence/leave.
                <br>Please inform your child and stay updated if suspension is lifted prior
                to instructor's presence.
            </p>
            

            <p class="message">
                Thank you for your understanding.
            </p>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; {{ date('Y') }} Student Attendance System. All rights reserved.</p>
        </div>
    </div>
    
</body>
</html>