<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Student Absence Notice</title>
</head>
<body>
    
    <div class="email-container">
        <div class="header">
            <h1>Student Absence Notice for {{ $studname }} - {{ $date }}</h1>
        </div>
        
        <div class="content">
            <p class="message">Dear {{ $guardian }},</p>
            
            <p class="message">
                We are writing to inform you that
                {{ $studname }} was marked absent from school on {{ $date }}.
                If this absence was due to illness or another valid reason,
                please reply to the provided e-mail of instructor,
                {{ $instructor_mail }} with documentation or an explanation.
            </p>

            <div class='summary'>
                <h3>📊 Attendance Summary</h3>
                <div class='summary-item'>
                    <strong>Total Absences This Year:</strong> {{ $absCount }}
                </div>
                <div class='summary-item'>
                    <strong>Absences This Week:</strong> {{ $absCountWeek }}
                </div>
            </div>

            <h3>📝 Required Action</h3>
                <p>Student is required to submit an excuse letter within <strong>24 hours</strong> 
                from receipt of this email. The excuse letter must include:</p>
                <ul>
                    <li>Student's full name and student ID</li>
                    <li>Dates of absences</li>
                    <li>Detailed explanation for each absence</li>
                    <li>Supporting documentation (medical certificate, official documents, etc.)</li>
                    <li>Date of letter issuance</li>
            </ul>

            <p><strong>Submit to:</strong> {{ $instructor_mail }} or visit the Faculty Office directly.</p>
            
            <div class='warning'>
                    <strong>WARNING:</strong> Failure to submit an excuse letter or reaching the dropout threshold 
                    of 40 absences may be eligible for dropping out.
            </div>

            <p style='margin-top: 30px;'>Best regards,<br>
            <strong>{{ $instructor }}</strong><br>
            Instructor<br>
            {{ $instructor_mail }}
            </p>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; {{ date('Y') }} Student Attendance System. All rights reserved.</p>
        </div>
    </div>


</body>
</html>