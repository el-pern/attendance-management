@extends('Layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('SAS.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <title>Student Attendance System</title>
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        .main-wrapper {
            display: flex;
            gap: 20px;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .sidebar {
            width: 280px;
            background: white;
            border-radius: 10px;
            padding: 20px 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            height: fit-content;
            position: sticky;
            top: 20px;
        }
        .sidebar-header {
            padding: 20px;
            border-bottom: 2px solid #e0e0e0;
            margin-bottom: 20px;
            text-align: center;
        }
        .logo {
            max-width: 60px;
            height: auto;
            margin-bottom: 15px;
        }
        .sidebar-header h3 {
            color: #232528;
            font-weight: 700;
            font-size: 20px;
            margin: 0;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-menu li {
            margin: 0;
        }
        .sidebar-menu a,
        .sidebar-menu .menu-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #232528;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border-left: 3px solid transparent;
        }
        .sidebar-menu a:hover,
        .sidebar-menu .menu-item:hover {
            background: #f8f9fa;
            border-left-color: #232528;
            color: #232528;
        }
        .sidebar-menu a.active {
            background: #232528;
            color: white;
            border-left-color: #232528;
        }
        .menu-icon {
            font-size: 20px;
            margin-right: 12px;
            width: 24px;
            text-align: center;
        }
        .menu-text {
            font-weight: 600;
            font-size: 14px;
        }
        .main-content {
            flex: 1;
            min-width: 0;
        }
        .header-banner {
            background: #232528;
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }
        .header-logo {
            max-width: 80px;
            height: auto;
        }
        .header-content {
            text-align: left;
        }
        .header-banner h1 {
            margin: 0;
            font-weight: 700;
            font-size: 32px;
        }
        .subtitle {
            font-size: 16px;
            opacity: 0.9;
            margin-top: 5px;
        }
        .content-container {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        .calendar-section {
            margin-bottom: 30px;
        }
        .section-heading {
            color: #232528;
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .calendar-container {
            background: white;
            border: 2px solid #232528;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
        }
        .calendar-header h4 {
            color: #232528;
            font-weight: 700;
            font-size: 20px;
            margin: 0;
        }
        .month-nav {
            background: #232528;
            color: white;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .month-nav:hover {
            background: #3a3d42;
        }
        .calendar-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            margin-bottom: 10px;
        }
        .calendar-weekdays div {
            text-align: center;
            font-weight: 700;
            color: #232528;
            padding: 10px;
            font-size: 12px;
            text-transform: uppercase;
        }
        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }
        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-weight: 600;
            color: #232528;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .calendar-day:hover {
            background: #f8f9fa;
        }
        .calendar-day.other-month {
            color: #ccc;
        }
        .calendar-day.today {
            background: #232528;
            color: white;
            border-color: #232528;
        }
        .calendar-day.weekend {
            background: #f8f9fa;
            color: #999;
        }
        .sections-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .section-btn {
            background: white;
            border: 2px solid #232528;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            color: #232528;
        }
        .section-btn:hover {
            background: #232528;
            color: white;
            transform: translateY(-3px);
        }
        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .stat-card {
            background: white;
            border: 2px solid #232528;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }
        .stat-label {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .stat-value {
            color: #232528;
            font-size: 32px;
            font-weight: 700;
        }
        .stat-detail {
            color: #999;
            font-size: 12px;
            margin-top: 5px;
        }
        .alert-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .alert-box {
            border-radius: 10px;
            padding: 20px;
        }
        .alert-box.red {
            background: #fee;
            border: 2px solid #dc3545;
        }
        .alert-box.yellow {
            background: #fffbec;
            border: 2px solid #ffc107;
        }
        .alert-box h5 {
            font-weight: 700;
            margin-bottom: 15px;
        }
        .alert-item {
            padding: 10px;
            background: white;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .btn {
            padding: 12px 18px;
            font-weight: 600;
            transition: all 0.25s ease;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-size: 13px;
            border-radius: 8px;
            border: 2px solid transparent;
        }
        .btn-primary {
            background: #232528;
            border-color: #232528;
            color: white;
        }
        .btn-primary:hover {
            background: white;
            color: #232528;
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
        .chart-container {
            max-width: 400px;
            margin: 30px auto;
        }
        .student-list {
            margin-top: 30px;
        }
        .student-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
        }
        .status-badge.present {
            background: #d4edda;
            color: #155724;
        }
        .status-badge.late {
            background: #fff3cd;
            color: #856404;
        }
        .status-badge.absent {
            background: #f8d7da;
            color: #721c24;
        }
        .status-badge.not-marked {
            background: #e2e3e5;
            color: #383d41;
        }
        .dropped-section {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #e0e0e0;
        }
        .modal-content {
            border: 2px solid #232528;
            border-radius: 10px;
        }
        .modal-header {
            background: #232528;
            color: white;
            border-bottom: none;
            border-radius: 8px 8px 0 0;
            padding: 20px 30px;
        }
        .modal-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
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
        .btn-primary {
            background: #232528;
            border: 2px solid #232528;
        }
        .btn-primary:hover {
            background: white;
            color: #232528;
        }
        /* Search and Sort Styles */
        .input-group .btn-secondary {
            border: 2px solid #232528;
            background: white;
            color: #232528;
        }
        .input-group .btn-secondary:hover {
            background: #232528;
            color: white;
        }
        .form-control, .form-select {
            border: 2px solid #232528;
            padding: 10px 15px;
            font-size: 14px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #232528;
            box-shadow: 0 0 0 0.2rem rgba(35, 37, 40, 0.25);
        }
        #searchInput::placeholder {
            color: #999;
            font-size: 13px;
        }
        #studentListContainer {
            max-height: 500px;
            overflow-y: auto;
            padding-right: 5px;
        }
        #studentListContainer::-webkit-scrollbar {
            width: 6px;
        }
        #studentListContainer::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        #studentListContainer::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }
        #studentListContainer::-webkit-scrollbar-thumb:hover {
            background: #999;
        }

        .period-selector {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-bottom: 20px;
    }
    .period-btn {
        padding: 10px 20px;
        border: 2px solid #232528;
        background: white;
        color: #232528;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .period-btn:hover {
        background: #f8f9fa;
    }
    .period-btn.active {
        background: #232528;
        color: white;
    }
    .print-btn {
        background: #232528;
        color: white;
        border: 2px solid #232528;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .print-btn:hover {
        background: white;
        color: #232528;
    }
    
    @media print {
        @page {
            margin: 10mm;
            size: auto;
        }
        
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        body * {
            visibility: hidden;
        }
        
        .modal-backdrop {
            display: none !important;
        }
        
        #attendanceModal,
        #attendanceModal * {
            visibility: visible;
        }
        
        #attendanceModal {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            z-index: 1;
        }
        
        .modal-dialog {
            max-width: 100%;
            margin: 0;
            width: 100%;
        }
        
        .modal-content {
            border: none;
            box-shadow: none;
            border-radius: 0;
            background: white;
        }
        
        .modal-header,
        .btn-close,
        #searchInput,
        #searchBtn,
        #sortSelect,
        #clearBtn,
        .print-btn,
        .period-selector,
        .input-group,
        #searchDropped {
            display: none !important;
        }
        
        .modal-body {
            padding: 10px;
            width: 100%;
        }
        
        #printHeader {
            display: block !important;
            text-align: center;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 2px solid #232528;
            page-break-after: avoid;
        }
        
        #printHeader h2 {
            font-size: 18px;
            margin-bottom: 4px;
            font-weight: 700;
        }
        
        #printHeader p {
            font-size: 11px;
            margin: 2px 0;
        }
        
        /* Chart - Centered and Responsive */
        .chart-container {
            max-width: 40%;
            width: 40%;
            margin: 12px auto;
            page-break-inside: avoid;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .chart-container canvas {
            max-width: 100%;
            height: auto !important;
        }
        
        /* Analytics Grid - Flows naturally, avoid breaking inside */
        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin: 12px 0;
            page-break-inside: avoid;
        }
        
        .stat-card {
            padding: 8px;
            font-size: 10px;
            border: 1px solid #232528;
            text-align: center;
        }
        
        .stat-label {
            font-size: 9px;
            margin-bottom: 4px;
        }
        
        .stat-value {
            font-size: 16px;
            font-weight: 700;
        }
        
        .stat-detail {
            font-size: 8px;
            margin-top: 2px;
        }
        
        /* Alert Section - Avoid breaking inside */
        .alert-section {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            margin: 12px 0;
            page-break-inside: avoid;
        }
        
        .alert-box {
            padding: 8px;
            font-size: 10px;
            border: 1px solid;
        }
        
        .alert-box.red {
            background: #fee;
            border-color: #dc3545;
        }
        
        .alert-box.yellow {
            background: #fffbec;
            border-color: #ffc107;
        }
        
        .alert-box h5 {
            font-size: 11px;
            margin-bottom: 6px;
            font-weight: 700;
        }
        
        .alert-item {
            padding: 6px;
            background: white;
            margin-bottom: 4px;
            font-size: 9px;
            border: 1px solid #e0e0e0;
        }
        
        /* Student List - Allow content to flow across pages */
        .student-list {
            margin-top: 12px;
        }
        
        .student-item {
            padding: 6px 8px;
            font-size: 10px;
            border: 1px solid #e0e0e0;
            margin-bottom: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .status-badge {
            padding: 3px 10px;
            font-size: 9px;
            border-radius: 10px;
            font-weight: 600;
        }
        
        .status-badge.present {
            background: #d4edda;
            color: #155724;
        }
        
        .status-badge.late {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-badge.absent {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-badge.not-marked {
            background: #e2e3e5;
            color: #383d41;
        }
        
        /* Dropped Students Section - Allow content to flow */
        .dropped-section {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 2px solid #e0e0e0;
        }
        
        .dropped-section h5 {
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 8px;
        }
        
        /* Hide "Showing X students" text */
        #studentListContainer .text-muted {
            display: none !important;
        }
        
        /* Badges */
        .badge {
            padding: 3px 8px;
            font-size: 9px;
            border-radius: 8px;
            font-weight: 600;
        }
        
        .badge.bg-danger {
            background: #dc3545 !important;
            color: white !important;
        }
        
        .badge.bg-warning {
            background: #ffc107 !important;
            color: #856404 !important;
        }
        
        .badge.bg-secondary {
            background: #6c757d !important;
            color: white !important;
        }
        
        /* Landscape Orientation - Same styles, content flows naturally */
        @media print and (orientation: landscape) {
            @page {
                margin: 10mm;
            }
            
            /* Keep same readable sizes */
            .modal-body {
                padding: 10px;
            }
            
            #printHeader h2 {
                font-size: 18px;
            }
            
            #printHeader p {
                font-size: 11px;
            }
            
            .chart-container {
                max-width: 35%;
                width: 35%;
            }
            
            .stat-card {
                font-size: 10px;
            }
            
            .stat-value {
                font-size: 16px;
            }
            
            .alert-box {
                font-size: 10px;
            }
            
            .alert-box h5 {
                font-size: 11px;
            }
            
            .student-item {
                font-size: 10px;
            }
            
            .status-badge {
                font-size: 9px;
            }
        }
        
        /* Portrait Specific */
        @media print and (orientation: portrait) {
            @page {
                margin: 10mm;
            }
            
            .chart-container {
                max-width: 40%;
                width: 40%;
            }
        }
    }
    
    #printHeader {
        display: none;
    }
    </style>
</head>
<body>
    
    @auth
        <div class="main-wrapper">
            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="sidebar-header">
                    <h3>Menu</h3>
                </div>
                <ul class="sidebar-menu">
                    <li>
                        <a href='/' class="menu-item active">
                            <span class="menu-icon">🏠</span>
                            <span class="menu-text">Home</span>
                        </a>
                    </li>
                    <li>
                        <a href='/checkattendance' class="menu-item">
                            <span class="menu-icon">✅</span>
                            <span class="menu-text">Check Attendance</span>
                        </a>
                    </li>
                    <li>
                        <a href='/instreq' class="menu-item">
                            <span class="menu-icon">📝</span>
                            <span class="menu-text">Request to Admin</span>
                        </a>
                    </li>
                    <li>
                        <div class="menu-item" data-bs-toggle="modal" data-bs-target="#acctSetModal">
                            <span class="menu-icon">⚙️</span>
                            <span class="menu-text">Account Settings</span>
                        </div>
                    </li>
                    <li>
                        <div class="menu-item" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <span class="menu-icon">🚪</span>
                            <span class="menu-text">Logout</span>
                        </div>
                    </li>
                </ul>
            </aside>

            <!-- Main Content -->
            <div class="main-content">
                <div class="header-banner">
                    <img src="{{ asset('SAS.png') }}" alt="SAS Logo" class="header-logo">
                    <div class="header-content">
                        <h1>Instructor Portal</h1>
                        <div class="subtitle">Welcome, {{ auth()->user()->name }}!</div>
                    </div>
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

    @if($suspend)
    <div class="container">
        <div class="alert" style="background: #fff3cd; border: 2px solid #ffc107; border-radius: 10px; padding: 25px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="text-align: center;">
                <h4 style="color: #856404; margin-bottom: 15px;">
                    <strong>⏸️ Classes Suspended</strong>
                </h4>
                <div style="background: white; padding: 15px; border-radius: 8px;">
                    <p style="font-size: 18px; font-weight: 600; color: #666; margin: 0;">
                        Your classes have been suspended for today.
                    </p>
                </div>
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
                    <!-- Calendar Section -->
                    <div class="calendar-section">
                        <h3 class="section-heading">
                            Attendance Calendar
                        </h3>
                        @if(!$shift)
                            <div class="alert alert-warning text-center">
                                <strong>⚠️ Please set your shift first</strong>
                                <br>Go to <a href="/checkattendance">Check Attendance</a> to set your shift.
                            </div>
                        @else
                            <div class="calendar-container">
                                <div class="calendar-header">
                                    <button class="month-nav" id="prevMonth">‹</button>
                                    <h4 id="currentMonth"></h4>
                                    <button class="month-nav" id="nextMonth">›</button>
                                </div>
                                <div class="calendar-weekdays">
                                    <div>Sun</div>
                                    <div>Mon</div>
                                    <div>Tue</div>
                                    <div>Wed</div>
                                    <div>Thu</div>
                                    <div>Fri</div>
                                    <div>Sat</div>
                                </div>
                                <div class="calendar-days" id="calendarDays"></div>
                            </div>

                            <div id="sectionsDisplay" style="display: none;">
                                <h5 class="text-center mb-3">Select a Section</h5>
                                <div id="selectedDate" class="text-center text-muted mb-3"></div>
                                <div class="sections-container" id="sectionsContainer"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Modal -->
        <div class="modal fade" id="attendanceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h3 id="modalTitle"></h3>
                    <small id="periodDisplay" class="text-white-50"></small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Print Header (only visible when printing) -->
                <div id="printHeader">
                    <h2 id="printTitle"></h2>
                    <p id="printPeriod"></p>
                    <p id="printDate"></p>
                </div>

                <!-- Period Selector -->
                <div class="period-selector">
                    <button class="period-btn active" data-period="daily">📅 Daily</button>
                    <button class="period-btn" data-period="weekly">📊 Weekly</button>
                    <button class="period-btn" data-period="monthly">📈 Monthly</button>
                    <button class="print-btn" onclick="printStatistics()">🖨️ Print</button>
                </div>

                <!-- Chart Section -->
                <div class="chart-container">
                    <canvas id="attendanceChart"></canvas>
                </div>

                <!-- Analytics Grid -->
                <div class="analytics-grid" id="analyticsGrid"></div>

                <!-- Alert Sections -->
                <div class="alert-section">
                    <div class="alert-box red">
                        <h5>🚨 Students with Frequent Absences (3+ days)</h5>
                        <div id="frequentAbsences"></div>
                    </div>
                    <div class="alert-box yellow">
                        <h5>⚠️ Students with Low Punctuality (<80%)</h5>
                        <div id="lowPunctuality"></div>
                    </div>
                </div>

                <!-- Student List -->
                <div class="student-list">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control" placeholder="Search students by name or ID...">
                                <button class="btn btn-secondary" type="button" id="searchBtn">
                                    🔍
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-select" id="sortSelect">
                                    <option value="name-asc">Sort by: Name (A-Z)</option>
                                    <option value="name-desc">Sort by: Name (Z-A)</option>
                                    <option value="status">Sort by: Status</option>
                                    <option value="id-asc">Sort by: Student ID</option>
                                    <option value="id-desc">Sort by: Student ID (Desc)</option>
                                </select>
                                <button class="btn btn-secondary" type="button" id="clearBtn">
                                    Clear
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div id="studentListContainer">
                        <!-- Student items will be rendered here -->
                    </div>
                </div>

                <!-- Dropped Students -->
                <div class="dropped-section" id="droppedSection"></div>
            </div>
        </div>
    </div>
</div>

        <!-- Account Settings Modal -->
        <div class="modal fade" id="acctSetModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Account Settings</h3>
                    </div>
                    <div class="modal-body">
                        <a href='{{ route('instructor.edit-profile', $instructor->id) }}'>
                            <button class="btn btn-primary w-100 mb-3">✏️ Edit Profile</button>
                        </a>
                        <form action='/authotp' method='POST'>
                            @csrf
                            <button class="btn btn-primary w-100">🔒 Change Password</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        
<!-- Logout Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <p style="font-size: 18px; margin: 0;">Are you sure you want to logout?</p>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <div class="d-flex gap-2 w-100">
                    <button type="button" class="btn btn-secondary flex-fill" data-bs-dismiss="modal">Cancel</button>
                    <form action="/logout" method="POST" class="flex-fill">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
            
    @else
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center p-5">
                        <img src="{{ asset('SAS.png') }}" alt="SAS Logo" style="max-width: 80px; margin-bottom: 20px;">
                        <p style="font-size: 18px;">Please login to continue.</p>
                        <a href="/login" class="btn btn-primary w-100">Login</a>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        @auth
    const sections = @json($visibleSections);
    let currentDate = new Date();
    let selectedDate = null;
    let chartInstance = null;
    let currentSectionId = null;
    let currentPeriod = 'daily';
    let periodButtonListenersAttached = false; // Track if listeners are attached

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        document.getElementById('currentMonth').textContent = 
            currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const daysInPrevMonth = new Date(year, month, 0).getDate();
        
        const calendarDays = document.getElementById('calendarDays');
        calendarDays.innerHTML = '';
        
        const today = new Date();
        const isCurrentMonth = today.getFullYear() === year && today.getMonth() === month;
        
        // Previous month days
        for (let i = firstDay - 1; i >= 0; i--) {
            const day = daysInPrevMonth - i;
            const dayEl = createDayElement(day, 'other-month', null);
            calendarDays.appendChild(dayEl);
        }
        
        // Current month days
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const dayOfWeek = date.getDay();
            const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;
            const isToday = isCurrentMonth && day === today.getDate();
            
            let classes = [];
            if (isWeekend) classes.push('weekend');
            if (isToday) classes.push('today');
            
            const dayEl = createDayElement(day, classes.join(' '), date);
            calendarDays.appendChild(dayEl);
        }
        
        // Next month days
        const remainingCells = 42 - (firstDay + daysInMonth);
        for (let day = 1; day <= remainingCells; day++) {
            const dayEl = createDayElement(day, 'other-month', null);
            calendarDays.appendChild(dayEl);
        }
    }
    
    function createDayElement(day, className, date) {
        const div = document.createElement('div');
        div.className = `calendar-day ${className}`;
        div.textContent = day;
        
        if (date && !className.includes('other-month')) {
            div.style.cursor = 'pointer';
            div.addEventListener('click', () => selectDate(date));
        }
        
        return div;
    }
    
    function selectDate(date) {
        selectedDate = date;
        
        // Check if selected date is a weekend
        const dayOfWeek = date.getDay();
        const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;
        
        // Show sections
        document.getElementById('sectionsDisplay').style.display = 'block';
        
        const container = document.getElementById('sectionsContainer');
        container.innerHTML = '';
        
        if (isWeekend) {
            // Show weekend message
            document.getElementById('selectedDate').innerHTML = 
                '<span style="color: #dc3545; font-weight: 600;">⚠️ No classes during weekends</span><br>' +
                'Selected: ' + date.toLocaleDateString('en-US', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
        } else {
            // Show normal date and sections
            document.getElementById('selectedDate').textContent = 
                'Selected: ' + date.toLocaleDateString('en-US', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
            
            sections.forEach(section => {
                const btn = document.createElement('div');
                btn.className = 'section-btn';
                btn.textContent = section.name;
                btn.onclick = () => loadAttendanceData(section.id, date);
                container.appendChild(btn);
            });
        }
        
        // Scroll to sections
        document.getElementById('sectionsDisplay').scrollIntoView({ behavior: 'smooth' });
    }
    
    async function loadAttendanceData(sectionId, date) {
        currentSectionId = sectionId;
        selectedDate = date;
        currentPeriod = 'daily'; // Reset to daily when loading new section
        periodButtonListenersAttached = false; // Reset listener flag
        await fetchAttendanceData(sectionId, date, currentPeriod);
    }

    async function fetchAttendanceData(sectionId, date, period) {
        try {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}`;
            
            const response = await fetch('/get-attendance-data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    section_id: sectionId,
                    date: formattedDate,
                    period: period
                })
            });
            
            const data = await response.json();
            displayAttendanceModal(data);
            
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to load attendance data');
        }
    }

    function displayAttendanceModal(data) {
        document.getElementById('modalTitle').textContent = 
            `${data.section.name} - ${data.date}`;
        
        document.getElementById('periodDisplay').textContent = 
            `${data.period.charAt(0).toUpperCase() + data.period.slice(1)} Statistics: ${data.periodText}`;
        
        // Update print header
        document.getElementById('printTitle').textContent = 
            `${data.section.name} Attendance Report`;
        document.getElementById('printPeriod').textContent = 
            `${data.period.charAt(0).toUpperCase() + data.period.slice(1)} Statistics: ${data.periodText}`;
        document.getElementById('printDate').textContent = 
            `Generated on: ${new Date().toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            })}`;
        
        // Update period buttons
        document.querySelectorAll('.period-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.period === data.period) {
                btn.classList.add('active');
            }
        });
        
        // Store the original data for filtering/sorting
        window.currentStudentData = data.students;
        
        // Render Chart
        const ctx = document.getElementById('attendanceChart');
        if (chartInstance) {
            chartInstance.destroy();
        }
        
        chartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Late', 'Absent', 'Not Marked'],
                datasets: [{
                    data: [
                        data.chart.present,
                        data.chart.late,
                        data.chart.absent,
                        data.chart.notMarked
                    ],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#6c757d']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: `Attendance Status - ${data.date}`
                    }
                }
            }
        });
        
        // Render Analytics
        const analyticsGrid = document.getElementById('analyticsGrid');
        analyticsGrid.innerHTML = `
            <div class="stat-card">
                <div class="stat-label">Attendance Rate</div>
                <div class="stat-value">${data.analytics.attendanceRate}%</div>
                <div class="stat-detail">${data.analytics.activeDays} active days</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Punctuality Rate</div>
                <div class="stat-value">${data.analytics.punctualityRate}%</div>
                <div class="stat-detail">On-time arrivals</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Late</div>
                <div class="stat-value" style="color: #ffc107;">${data.analytics.totalLate}</div>
                <div class="stat-detail">Late arrivals</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Absences</div>
                <div class="stat-value" style="color: #dc3545;">${data.analytics.totalAbsences}</div>
                <div class="stat-detail">Absent days</div>
            </div>
        `;
        
        // Render Frequent Absences
        const frequentAbsences = document.getElementById('frequentAbsences');
        if (data.frequentAbsences.length === 0) {
            frequentAbsences.innerHTML = '<p class="text-muted">No students with frequent absences. Great job!</p>';
        } else {
            frequentAbsences.innerHTML = data.frequentAbsences.map(student => `
                <div class="alert-item">
                    <strong>${student.name}</strong>
                    <span class="float-end badge bg-danger">${student.absences} absences</span>
                </div>
            `).join('');
        }
        
        // Render Low Punctuality
        const lowPunctuality = document.getElementById('lowPunctuality');
        if (data.lowPunctuality.length === 0) {
            lowPunctuality.innerHTML = '<p class="text-muted">All students have good punctuality!</p>';
        } else {
            lowPunctuality.innerHTML = data.lowPunctuality.map(student => `
                <div class="alert-item">
                    <strong>${student.name}</strong>
                    <br>
                    <small>Present: ${student.present} | Late: ${student.late}</small>
                    <span class="float-end badge bg-warning text-dark">${student.punctuality}%</span>
                </div>
            `).join('');
        }
        
        // Render Student List with initial data
        renderStudentList(data.students);
        
        // Setup search and sort event listeners
        setupSearchAndSort();
        
        // Render Dropped Students
        const droppedSection = document.getElementById('droppedSection');
        if (data.droppedStudents.length > 0) {
            droppedSection.style.display = 'block';
            droppedSection.innerHTML = `
                <h5 class="text-center mb-3">🚫 Dropped Students (${data.droppedStudents.length})</h5>
                <div class="row mb-3">
                    <div class="col-12">
                        <input type="text" id="searchDropped" class="form-control" placeholder="Search dropped students...">
                    </div>
                </div>
                <div id="droppedStudentsList">
                    ${data.droppedStudents.map(student => `
                        <div class="student-item" style="opacity: 0.7;">
                            <div>
                                <strong>${student.fname} ${student.lname}</strong>
                                <br>
                                <small class="text-muted">${student.student_id}</small>
                            </div>
                            <span class="badge bg-secondary">Dropped</span>
                        </div>
                    `).join('')}
                </div>
            `;
            
            // Add search functionality for dropped students
            const searchDroppedInput = document.getElementById('searchDropped');
            if (searchDroppedInput) {
                searchDroppedInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    const allDropped = data.droppedStudents;
                    const filtered = allDropped.filter(student => 
                        student.fname.toLowerCase().includes(searchTerm) ||
                        student.lname.toLowerCase().includes(searchTerm) ||
                        student.student_id.toLowerCase().includes(searchTerm)
                    );
                    
                    const droppedList = document.getElementById('droppedStudentsList');
                    droppedList.innerHTML = filtered.map(student => `
                        <div class="student-item" style="opacity: 0.7;">
                            <div>
                                <strong>${student.fname} ${student.lname}</strong>
                                <br>
                                <small class="text-muted">${student.student_id}</small>
                            </div>
                            <span class="badge bg-secondary">Dropped</span>
                        </div>
                    `).join('');
                });
            }
        } else {
            droppedSection.style.display = 'none';
        }
        
        // Show modal
        const modalElement = document.getElementById('attendanceModal');
        let modalInstance = bootstrap.Modal.getInstance(modalElement);
        
        if (!modalInstance) {
            modalInstance = new bootstrap.Modal(modalElement);
        }
        modalInstance.show();
        
        // Attach period button event listeners ONLY ONCE
        if (!periodButtonListenersAttached) {
            document.querySelectorAll('.period-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentPeriod = this.dataset.period;
                    fetchAttendanceData(currentSectionId, selectedDate, currentPeriod);
                });
            });
            periodButtonListenersAttached = true;
        }
    }

    function printStatistics() {
        window.print();
    }
    
    // ... keep renderStudentList and setupSearchAndSort functions exactly as they are ...
    
    function renderStudentList(students) {
        const studentListContainer = document.getElementById('studentListContainer');
        
        if (students.length === 0) {
            studentListContainer.innerHTML = `
                <div class="text-center py-5">
                    <div style="font-size: 48px; color: #ccc;">📭</div>
                    <p class="text-muted">No students found</p>
                </div>
            `;
            return;
        }
        
        studentListContainer.innerHTML = `
            <div class="mb-2 text-muted">
                <small>Showing ${students.length} students</small>
            </div>
            ${students.map(student => `
                <div class="student-item">
                    <div>
                        <strong>${student.name}</strong>
                        <br>
                        <small class="text-muted">${student.student_id}</small>
                    </div>
                    <span class="status-badge ${student.status.toLowerCase().replace(' ', '-')}">${student.status}</span>
                </div>
            `).join('')}
        `;
    }
    
    function setupSearchAndSort() {
        const searchInput = document.getElementById('searchInput');
        const searchBtn = document.getElementById('searchBtn');
        const sortSelect = document.getElementById('sortSelect');
        const clearBtn = document.getElementById('clearBtn');
        
        // Search function
        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase();
            let filteredStudents = window.currentStudentData;
            
            if (searchTerm.trim() !== '') {
                filteredStudents = window.currentStudentData.filter(student => 
                    student.name.toLowerCase().includes(searchTerm) ||
                    student.student_id.toLowerCase().includes(searchTerm)
                );
            }
            
            // Apply current sort
            applySort(filteredStudents);
        }
        
        // Sort function
        function applySort(students) {
            const sortValue = sortSelect.value;
            let sortedStudents = [...students];
            
            switch(sortValue) {
                case 'name-asc':
                    sortedStudents.sort((a, b) => a.name.localeCompare(b.name));
                    break;
                case 'name-desc':
                    sortedStudents.sort((a, b) => b.name.localeCompare(a.name));
                    break;
                case 'status':
                    const statusOrder = {
                        'Present': 1,
                        'Late': 2,
                        'Absent': 3,
                        'Not Marked': 4
                    };
                    sortedStudents.sort((a, b) => statusOrder[a.status] - statusOrder[b.status]);
                    break;
                case 'id-asc':
                    sortedStudents.sort((a, b) => a.student_id.localeCompare(b.student_id));
                    break;
                case 'id-desc':
                    sortedStudents.sort((a, b) => b.student_id.localeCompare(a.student_id));
                    break;
            }
            
            renderStudentList(sortedStudents);
        }
        
        // Event listeners
        searchBtn.addEventListener('click', performSearch);
        searchInput.addEventListener('keyup', (e) => {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
        
        sortSelect.addEventListener('change', () => {
            const searchTerm = searchInput.value.toLowerCase();
            let filteredStudents = window.currentStudentData;
            
            if (searchTerm.trim() !== '') {
                filteredStudents = window.currentStudentData.filter(student => 
                    student.name.toLowerCase().includes(searchTerm) ||
                    student.student_id.toLowerCase().includes(searchTerm)
                );
            }
            
            applySort(filteredStudents);
        });
        
        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            sortSelect.value = 'name-asc';
            renderStudentList(window.currentStudentData);
        });
        
        // Initialize with default sort
        applySort(window.currentStudentData);
    }
    
    document.getElementById('prevMonth').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
        document.getElementById('sectionsDisplay').style.display = 'none';
    });
    
    document.getElementById('nextMonth').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
        document.getElementById('sectionsDisplay').style.display = 'none';
    });
    
    renderCalendar();
    @endauth
    </script>
</body>
</html>

@endsection

