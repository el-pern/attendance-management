<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Suspension Lifted</title>
</head>
<body>

    <div class="email-container">
        <div class="header">
            <h1>Suspension Lifted</h1>
        </div>
        
        <div class="content">
            <p class="message">Salutations!</p>
            
            <p class="message">
                The class suspension set by Mr./Ms./Mrs. {{ $instructor }}
                has been lifted for today.
                <br>Please inform your child regarding this update.
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