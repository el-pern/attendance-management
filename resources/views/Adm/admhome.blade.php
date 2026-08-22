@extends('Layouts.app')
@section('content')


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" type="image/x-icon" href="{{ asset('SAS.png') }}">

    <!-- Friggin bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous">

    <title>StudAtt Admin</title>
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        .header-banner {
            background: #232528;
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header-logo {
            max-width: 120px;
            height: auto;
            margin-bottom: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .header-banner h1 {
            margin: 0;
            font-weight: 700;
            font-size: 32px;
            letter-spacing: 1px;
        }
        .header-banner .subtitle {
            margin-top: 10px;
            font-size: 16px;
            opacity: 0.9;
            font-weight: 400;
        }
        .content-container {
            background: white;
            border-radius: 10px;
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        .section-title {
            color: #232528;
            font-weight: 700;
            font-size: 20px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #232528;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .action-section {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin-bottom: 40px;
        }
        .main-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .action-card {
            background: white;
            border: 2px solid #232528;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
        }
        .action-card:hover {
            background: #232528;
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(35, 37, 40, 0.3);
        }
        .action-card:hover .card-icon {
            color: white;
        }
        .action-card:hover .card-title {
            color: white;
        }
        .card-icon {
            font-size: 36px;
            margin-bottom: 15px;
            color: #232528;
            transition: all 0.3s ease;
        }
        .card-title {
            color: #232528;
            font-weight: 600;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        .btn {
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 14px;
            border-radius: 6px;
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
        .btn-danger {
            background: #dc3545;
            border: 2px solid #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: white;
            color: #dc3545;
            border: 2px solid #dc3545;
        }
        .view-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 30px;
            border: 2px solid #232528;
        }
        .view-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .view-item {
            background: white;
            border: 2px solid #232528;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            text-decoration: none;
            color: #232528;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .view-item:hover {
            background: #232528;
            color: white;
            transform: scale(1.05);
        }
        .modal-content {
            border: 2px solid #232528;
            border-radius: 10px;
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
    @auth
    <div class="container">
        <div class="header-banner">
            <img src="{{ asset('SAS.png') }}" alt="SAS Logo" class="header-logo">
            <h1>SAS Administration Portal</h1>
            <div class="subtitle">Welcome, Admin {{ auth()->user()->name }}</div>
        </div>

        @if($holidays || $vacations)
    <div class="container">
        <div class="alert" style="background: #fff3cd; border: 2px solid #ffc107; border-radius: 10px; padding: 25px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="text-align: center;">
                <h4 style="color: #856404; margin-bottom: 15px;">
                    <strong>🏖️ No Classes Today</strong>
                </h4>
                @if($holidays)
                    <div style="background: white; padding: 15px; border-radius: 8px; margin-bottom: 10px;">
                        <h5 style="color: #232528; margin-bottom: 5px;">📅 Holiday</h5>
                        <p style="font-size: 18px; font-weight: 600; color: #666; margin: 0;">
                            {{ $holidays->name }}
                        </p>
                    </div>
                @endif
                @if($vacations)
                    @if($vacations)
                        @php
                            $startFormatted = \Carbon\Carbon::createFromFormat('m-d', $vacations->start_period)->format('F d');
                            $endFormatted = \Carbon\Carbon::createFromFormat('m-d', $vacations->end_period)->format('F d');
                        @endphp
                        <div style="background: white; padding: 15px; border-radius: 8px; margin-bottom: 10px;">
                            <h5 style="color: #232528; margin-bottom: 5px;">📅 {{ $vacations->name }}</h5>
                            <br>
                            <p style="font-size: 18px; font-weight: 600; color: #666; margin: 0;">
                                {{ $startFormatted }} to {{ $endFormatted }}
                            </p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endif

@if($allsuspend)
    <div class="container">
        <div class="alert" style="background: #f8d7da; border: 2px solid #dc3545; border-radius: 10px; padding: 25px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="text-align: center;">
                <h4 style="color: #721c24; margin-bottom: 15px;">
                    <strong>🚫 All Classes Suspended</strong>
                </h4>
                <div style="background: white; padding: 15px; border-radius: 8px;">
                    <p style="font-size: 18px; font-weight: 600; color: #666; margin: 0;">
                        All classes have been suspended for today.
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif

        <div class="content-container">
            <div class="section-title">Account Management</div>
            <div class="action-section">
                
                <form action="/authadmotp" method="POST" style="display: inline;">
                    @csrf
                    <button type="button" class="btn btn-primary"
                    data-bs-toggle="modal" data-bs-target="#changePassModal">
                        🔒 Change Password
                    </button>

                    <div class="modal fade" id="changePassModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                        <div class="modal-body">
                            Proceed to change password?
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Yes</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        </div>
                        </div>
                    </div>
                    </div>
                </form>
                
                <form action="/adminlogout" method="POST" style="display: inline;">
                    @csrf
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    🚪 Logout</button>

                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                        <div class="modal-body">
                            Are you sure you want to log out?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Log Out</button>
                        </div>
                        </div>
                    </div>
                    </div>
                </form>
            </div>

            <div class="section-title">Quick Actions</div>
            <div class="main-actions">
                <a href="/createuser" class="action-card">
                    <div class="card-icon">🆕👤</div>
                    <div class="card-title">Create User/Instructor</div>
                </a>

                <a href="/attcsv" class="action-card">
                    <div class="card-icon">📊</div>
                    <div class="card-title">Import/Export Attendance</div>
                </a>
                <a href="/admin/recent-logs" class="action-card">
                    <div class="card-icon">📋</div>
                    <div class="card-title">Activity Logs</div>
                </a>
                <a href="/admin/qr-code" class="action-card">
                    <div class="card-icon">📱</div>
                    <div class="card-title">Generate Student QR</div>
                </a>
                <a href="/admin/reqnotifs" class="action-card">
                    <div class="card-icon">🔔</div>
                    <div class="card-title">Notifications</div>
                </a>
                <a href="/admin/holidays" class="action-card">
                    <div class="card-icon">🎉</div>
                    <div class="card-title">Holidays</div>
                </a>
                <a href="/admin/vacations" class="action-card">
                    <div class="card-icon">🌞</div>
                    <div class="card-title">Vacation Periods</div>
                </a>

            </div>

            <div class="section-title">View Management</div>
            <div class="view-section">
                <div class="view-grid">
                    <a href="admin/view-classes" class="view-item">📚 Classes</a>
                    <a href="admin/view-sections" class="view-item">🏫 Sections</a>
                    <a href="admin/view-students" class="view-item">👨‍🎓 Students</a>
                    <a href="admin/view-instructors" class="view-item">👨‍🏫 Instructors</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-oBqDVmMz4fnFO9gybBogGzPztE1M5rZG/8Xlqh8fATrSWJZDmmW4Ll48dWkOVbCH"
    crossorigin="anonymous"></script>

    @endauth
    @endsection

</body>
</html>

