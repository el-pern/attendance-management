<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendance Rules</title>
</head>
<body>

    <div class="email-container">
        <div class="header">
            <h1>Attendance Rules</h1>
        </div>
        
        <div class="content">
            <p class="message">Good day!</p>
            
            <p class="message">We are excited to have your student with us this year.</p>

            <p class="message">
                This email contains important information about our <strong>Student Attendance System</strong> 
                and policies that will help ensure your student's academic success.
            </p>


            <div class='section'>
                <h3>📋 Attendance System Overview</h3>
                
                <div class='info-box'>
                    <strong>🔔 Automated Notifications:</strong> You will receive automated email alerts regarding 
                    your student's attendance. Our system monitors attendance in real-time to keep you informed.
                </div>
                
                <div class='policy-list'>
                    <div class='policy-item'>
                        <div class='policy-icon'>📧</div>
                        <div class='policy-text'>
                            <strong>Weekly Absence Alert:</strong>
                            If your student incurs 2 consecutive absences within a single week,
                            you will receive an immediate email notification with 
                            attendance summary and required actions.
                        </div>
                    </div>
                    
                    <div class='policy-item'>
                        <div class='policy-icon'>📊</div>
                        <div class='policy-text'>
                            <strong>Attendance Tracking:</strong> All absences are recorded and tracked 
                            throughout the school year. You can request an attendance report at any time.
                        </div>
                    </div>
                    
                    <div class='policy-item'>
                        <div class='policy-icon'>⏰</div>
                        <div class='policy-text'>
                            <strong>Real-Time Updates:</strong> Attendance is recorded daily, and our system 
                            processes records to ensure accurate monitoring.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class='section'>
            <h3>📝 Excuse Letter Requirements</h3>
                    
            <p>When your student is absent, an excuse letter must be submitted within <strong>24 hours</strong>. 
            The letter must include:</p>
            
            <ul style='line-height: 2;'>
                <li>✅ Student's full name and ID number</li>
                <li>✅ Date of letter issuance</li>
                <li>✅ Detailed reason for absence</li>
                <li>✅ Supporting documents (medical certificate, official letters, etc.)</li>
                <li>✅ Guardian's signature and contact information</li>
            </ul>
            
            </div>


            <p class="message">We look forward to a successful academic year!
                Please don't hesitate to reach out if you have 
                any questions or concerns about our attendance system.</p>
                
            <p style='margin-top: 30px;'>Best regards,<br>
            <strong>{{ $admin }}</strong><br>
            Administrator<br>
            {{ $adm_mail }}
            </p>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; {{ date('Y') }} Student Attendance System. All rights reserved.</p>
        </div>
    </div>
    
</body>
</html>