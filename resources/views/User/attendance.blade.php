@extends('Layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Student Attendance</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('SAS.png') }}">

    <!-- Friggin bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous">
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
            align-items: flex-start;
        }

        .main-content {
            flex: 1;
            min-width: 0;
            max-width: 100%; /* Add this */
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
        .sidebar-menu a.active,
        .sidebar-menu .menu-item.active {
            background: #232528;
            color: white;
            border-left-color: #232528;
        }
        .sidebar-menu .menu-icon {
            font-size: 18px;
            margin-right: 10px;
            width: 22px;
            text-align: center;
        }
        .sidebar-menu .menu-text {
            font-weight: 600;
            font-size: 14px;
        }
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 16px;
            left: 16px;
            z-index: 1001;
            background: #232528;
            color: white;
            border: none;
            padding: 10px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .main-content {
            flex: 1;
            min-width: 0;
        }
        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .header-banner {
            background: #232528;
            color: white;
            padding: 28px 32px;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap; /* Add this to allow wrapping on smaller screens */
        }
        .header-title {
            margin: 0;
            font-weight: 700;
            font-size: 26px;
            letter-spacing: 0.5px;
        }
        .header-subtitle {
            margin: 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .header-user {
            text-align: right;
            font-size: 14px;
            line-height: 1.5;
        }
        .action-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
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
        .btn.border-primary {
            background: white;
            border: 2px solid #232528;
            color: #232528;
            padding: 8px 15px;
        }
        .btn.border-primary:hover {
            background: #232528;
            color: white;
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
            background: #28a745;
            border: 2px solid #28a745;
            color: white;
        }
        .btn-success:hover {
            background: white;
            color: #28a745;
        }
        .btn.border-success:hover {
            background: #28a745;
            color: #232528;
        }
        .btn-warning {
            background: #ffc107;
            border: 2px solid #ffc107;
            color: #232528;
        }
        .btn-warning:hover {
            background: white;
            color: #232528;
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
        .card-shell {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
            overflow: hidden; /* Add this */
            width: 100%; /* Add this */
            box-sizing: border-box; /* Add this */
        }
        .card-shell h4 {
            font-weight: 700;
            color: #232528;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .card-shell p {
            margin: 0;
            color: #666;
        }
        label {
            font-weight: 700;
            color: #232528;
            margin-bottom: 8px;
            display: block;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.8px;
        }
        input[type="text"],
        select,
        input[type="time"] {
            width: 100%;
            padding: 14px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            background: white !important; /* Force white background */
            font-size: 16px;
            transition: all 0.3s ease;
            color: #232528 !important; /* Force text color */
            box-sizing: border-box;
            -webkit-appearance: none; /* Reset webkit styling */
            -moz-appearance: none; /* Reset firefox styling */
        }
        input[type="text"]:focus,
        select:focus,
        input[type="time"]:focus {
            outline: none;
            border-color: #232528;
            box-shadow: 0 0 0 3px rgba(35, 37, 40, 0.1);
        }
        .input-row {
            display: flex;
            gap: 12px;
            align-items: flex-end;
            flex-wrap: wrap;
        }
        .form-note {
            color: #666;
            font-size: 14px;
        }
        .text-danger {
            font-size: 14px;
            margin-top: 6px;
            color: #dc3545;
        }
        .table {
            border-radius: 8px;
            overflow: hidden;
            min-width: 600px;
        }
        .table thead th {
            background: #232528;
            color: white;
            border: none;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }
        .table tbody td {
            vertical-align: middle;
        }
        .section-toggle {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        .badge-info {
            background: #e8f1ff;
            color: #0d6efd;
            border: 1px solid #bcd3ff;
        }
        .badge-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffc107;
        }
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #a80112;
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
        .login-modal-content {
            background: white;
            border: 2px solid #232528;
            border-radius: 10px;
        }
        .login-modal-content .modal-body {
            text-align: center;
            padding: 40px;
        }
        .login-modal-content .modal-body p {
            font-size: 18px;
            color: #232528;
            margin-bottom: 30px;
        }
        details {
            background: white;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            margin-bottom: 16px;
            padding: 16px;
            color: #232528;
        }

        summary {
            list-style: none;
        }
        @media (max-width: 768px) {
            .action-bar {
                flex-direction: column;
            }
            .input-row {
                flex-direction: column;
            }
            .main-wrapper {
                flex-direction: column;
            }
            .sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                height: 100vh;
                z-index: 1000;
                transition: left 0.3s ease;
                overflow-y: auto;
                border-radius: 0;
                width: 240px;
            }
            .sidebar.active {
                left: 0;
            }
            .sidebar-toggle {
                display: block;
            }
            .sidebar-overlay.active {
                display: block;
            }
        }
        .table-controls {
            margin-bottom: 15px;
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .table-search {
            flex: 1;
            min-width: 200px;
            max-width: 400px;
        }

        .sort-btn {
            cursor: pointer;
            user-select: none;
            background: none;
            border: none;
            color: inherit;
            font: inherit;
            padding: 0;
            font-weight: inherit;
            text-transform: inherit;
            letter-spacing: inherit;
        }

        .sort-btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <button class="sidebar-toggle" id="sidebarToggle">☰</button>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main-wrapper">
        <!-- Sidebar Menu -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h3>Menu</h3>
            </div>
            <ul class="sidebar-menu">
                <li>
                    <a href='/' class="menu-item">
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
        <div class="page-container">
            <div class="header-banner">
                <div>
                    <p class="header-subtitle mb-1">Instructor Portal</p>
                    <h3 class="header-title">Student Attendance</h3>
                </div>
                <div class="header-user">
                    <p><strong>{{ auth()->user()->name }}</strong></p>
                    <p>{{ auth()->user()->email }}</p>
                </div>

     <div class="card-shell">
            <h4>🔍 Quick Scan</h4>
            <p class="form-note">Scan the QR code, then confirm.</p>
            <form action="/checkattendance" method="POST" class="mt-3">
                @csrf
                <div class="input-row">
                    <div class="flex-grow-1">
                        <label for="student_id">QR Code</label>
                        <input type="text" name="qr_key" id="qr_key" autofocus autocomplete="off">
                        @error('qr_key')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#checkModal">Check</button>
                    </div>
                </div>
                <!-- Confirm Scan Modal -->
                <div class="modal fade" id="checkModal" tabindex="-1" aria-labelledby="checkModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                Do you confirm that the student number is correct?
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Yes</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>


@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mt-3">
        <strong>Validation Errors:</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

    <hr>

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

    <div class="card-shell">
            <h4>🕒 Shift</h4>
            @if($hides)
                <div class="alert alert-info mb-3">Current Shift: {{ $hides->shift->inst_shift ?? 'N/A' }}</div>
            @else
                <div class="alert alert-warning mb-3">Set a shift first before checking attendance</div>
            @endif

            <form action="/shift" method="POST">
                @csrf
                <div class="input-row">
                    <div class="flex-grow-1">
                        <label for="shift_id">Select Shift</label>
                        <select name="shift_id" id="shift_id" required>
                            <option value="">-- Select Shift --</option>
                            @foreach($shifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->inst_shift }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#shiftModal">Set Shift</button>
                    </div>
                </div>
                <!-- Shift Confirm Modal -->
                <div class="modal fade" id="shiftModal" tabindex="-1" aria-labelledby="shiftModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                Proceed to set this shift?
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Set</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    

    <div class="card-shell">
            <h4>⏸️ Class Actions</h4>
            <div class="d-flex flex-wrap gap-2">
            @if(!$suspend)
            <form action="/suspend" method="POST" class="d-inline">
                @csrf
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#suspendModal">Suspend Classes</button>
                <!-- Suspend Confirm Modal -->
                <div class="modal fade" id="suspendModal" tabindex="-1" aria-labelledby="suspendModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                Proceed to suspend the classes you handle?
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-warning">Suspend</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            @else

                <form action="/liftsus/{{ $suspend->id }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#liftModal">Lift Suspension</button>

                    <div class="modal fade" id="liftModal" tabindex="-1" aria-labelledby="suspendModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                Proceed to lift suspension of the classes you handle?
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Lift</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>

                </form>

            @endif
        </div>
    </div>

    @foreach($sections as $section)

        @php

            $schedule = $schedules->firstWhere('section_id', $section->id);

        @endphp


        @if(in_array($section->id, $visibleSectionIds))
        <div class="card-shell">
                <button class="btn btn-primary w-100 section-toggle" type="button"
                    onclick="document.getElementById('details-{{ $section->id }}').toggleAttribute('open')">
                    <span>{{ $section->grade->roman_numeral }} - {{ $section->name }}</span>
                    <span class="badge bg-light text-dark">
                        @php
                            $count = $attendanceBySection->has($section->id)
                                ? $attendanceBySection->get($section->id)->count() : 0;
                        @endphp
                        {{ $count }} {{ $count != 1 ? 'students recorded' : 'student recorded' }}
                    </span>
                </button>
        @if(($attendanceBySection->has($section->id) && $attendanceBySection->get($section->id)->count() > 0)
        && ($students->has($section->id) && $students->get($section->id)->count() > 0))
        <details id="details-{{ $section->id }}" class="mt-2">
        <summary style="display:none;"></summary>

            @php
                $schedule = $schedules->firstWhere('section_id', $section->id);
            @endphp

            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
            <h6 class="mb-0">📅 Schedule</h6>
            @if(!$schedule)
                <button type="button" class="btn btn-success"
                    onclick="prepareAddSchedule({{ $section->id }}, '{{ $section->grade->roman_numeral }} - {{ $section->name }}')"
                    data-bs-toggle="modal" data-bs-target="#addSchedModal">+ Add Schedule</button>
            @else
                <span class="badge badge-info">{{ $schedule->start_time->format('g:i A') }} - {{ $schedule->end_time->format('g:i A') }}</span>
                <span class="badge badge-warning">{{ $schedule->schedlimit->late_time->format('g:i A') }}</span>
                <span class="badge badge-danger">{{ $schedule->schedlimit->absent_time->format('g:i A') }}</span>
                <button type="button" class="btn btn-secondary"
                    data-bs-toggle="modal" data-bs-target="#editSchedModal{{ $section->id }}">Edit</button>
                <!-- Edit Schedule Modal -->
                <div class="modal fade" id="editSchedModal{{ $section->id }}" tabindex="-1" aria-labelledby="editSchedModalLabel{{ $section->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Schedule Settings</h5>
                                <div class="alert alert-info mb-0 ms-2">
                                    <strong>{{ $section->grade->roman_numeral }} - {{ $section->name }}</strong>
                                </div>
                            </div>
                            <form action="{{ route('edit-sched', $schedule->id) }}" method="POST">
                                <div class="modal-body">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="section_id" id="edit_section_id">
                                    <input type="hidden" name="subject_id" value="{{ $inst_subs->subject_id }}">
                                    <label for="start_time">Start Time</label>
                                    <input type="time" name="start_time" id="edit_start_time" value="{{ $schedule->start_time->format('H:i') }}" required>
                                    <label for="end_time">End Time</label>
                                    <input type="time" name="end_time" id="edit_end_time" value="{{ $schedule->end_time->format('H:i') }}" required>
                                    <div class="alert alert-info mt-3" style="font-size: 13px;">
                                        <strong>ℹ️ Auto-calculated:</strong><br>
                                        • Late time: 25% of class duration<br>
                                        • Absent time: 40% of class duration
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Confirm</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <h4 class="mt-2">Today's Attendance</h4>

            <!-- Add search input -->
        <div class="table-controls">
            <input type="text" 
                   class="table-search form-control" 
                   placeholder="🔍 Search attendance" 
                   data-table="attendance-table-{{ $section->id }}">
        </div>
                        <div class="card-body p-0" style="overflow-x: auto;">
                            <table class="table table-striped table-hover mb-0" id="attendance-table-{{ $section->id }}">
                                <thead><tr>
                                    <th><button class="sort-btn" onclick="sortTable('attendance-table-{{ $section->id }}', 0, this)">Student Number</button></th>
                                    <th><button class="sort-btn" onclick="sortTable('attendance-table-{{ $section->id }}', 1, this)">Name</button></th>
                                    <th><button class="sort-btn" onclick="sortTable('attendance-table-{{ $section->id }}', 2, this)">Status</button></th>
                                    <th><button class="sort-btn" onclick="sortTable('attendance-table-{{ $section->id }}', 3, this)">Time</button></th>
                                    <th>Actions</th>
                                </tr></thead>
                                <tbody>
                                    @foreach($attendanceBySection->get($section->id) as $attendance)
                                        <tr>
                                            <td>{{ $attendance->student->student_id }}</td>
                                            <td>{{ $attendance->student->lname }}, {{ $attendance->student->fname }}</td>
                                            <td>
                                                @if($attendance->status == 'Present')
                                                    <span class="badge bg-success">Present</span>
                                                @elseif($attendance->status == 'Late')
                                                    <span class="badge bg-warning text-dark">Late</span>
                                                @else
                                                    <span class="badge bg-danger">Absent</span>
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($attendance->att_date)->format('h:i A') }}</td>
                                            <td class="d-flex gap-2 justify-content-center">

                            <button type="button" class="btn border-primary" title="Edit"
                            data-bs-toggle="modal" data-bs-target="#editAttModal{{ $attendance->id }}">✏️</button>

                            <div class="modal fade" id="editAttModal{{ $attendance->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <div class="d-flex flex-column">
                                        <h3 class="modal-title">Edit Attendance</h3>
                                        <h5 class="modal-title">{{ $attendance->student->fname }} {{ $attendance->student->lname }} - {{ $attendance->student->student_id }}</h5>
                                        <h5 class="modal-title">{{ $attendance->student->section->grade->roman_numeral }} - {{ $attendance->student->section->name }}</h5>
                                    </div>
                                    <div class="d-flex flex-column">


                                    </div>
                                </div>
                                <form action="{{ route('edit-att', $attendance->id) }}" method="POST">
                                <div class="modal-body">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" id="status" required>

                                            @php

                                            $att_opt = array('Present', 'Late', 'Absent');

                                            @endphp

                                            @foreach($att_opt as $opt)
                                                <option value="{{ $opt }}"
                                                {{ $attendance->status == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                            @endforeach

                                        </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Confirm</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                                </div>
                            </div>
                            </div>


                        </td>
                    </tr>
                    @endforeach
                </tbody>
                    </table>

                </div>
                

            <h4 class="mt-3">Students - {{ $students->get($section->id)->count() }}</h4>
                        <div class="table-controls">
                        <input type="text" 
                               class="table-search form-control" 
                               placeholder="🔍 Search students" 
                               data-table="students-table-{{ $section->id }}">
                        </div>

                        <div class="card-body p-0" style="overflow-x: auto;">
                 
<table class="table table-striped table-hover mb-0" id="students-table-{{ $section->id }}">

                            <thead><tr>
                                <th><button class="sort-btn" onclick="sortTable('students-table-{{ $section->id }}', 0, this)">Student Number</button></th>
                                <th><button class="sort-btn" onclick="sortTable('students-table-{{ $section->id }}', 1, this)">Name</button></th>
                                <th><button class="sort-btn" onclick="sortTable('students-table-{{ $section->id }}', 2, this)">E-mail</button></th>
                                <th>Actions</th>
                            </tr></thead>
                                <tbody>
                                    @foreach($students->get($section->id) as $student)
                                        <tr>
                                            <td>{{ $student->student_id }}</td>
                                            <td>{{ $student->lname }}, {{ $student->fname }}</td>
                                            <td>{{ $student->email }}</td>
                                            <td class="d-flex gap-2 justify-content-center">

                                                <form action="/checkattendance" method="POST">
                                                    @csrf

                                                    <input type="hidden" name="student_id" value="{{ $student->student_id }}">
                                                    @if($student->qr)
                                                        <input type="hidden" name="qr_key" value="{{ $student->qr->qr_key }}">
                                                    @endif

                                                    <button type="button" class="btn border-success" title="Check Attendance"
                                                    data-bs-toggle="modal" data-bs-target="#checkBoxModal{{ $student->id }}">✔</button>

                                                    <!-- Check Modal -->
                                                    <div class="modal fade" id="checkBoxModal{{ $student->id }}" tabindex="-1" aria-labelledby="checkModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-body">
                                                                    Check attendance of student {{ $student->lname }}, {{ $student->fname }}?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-success">Yes</button>
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </form>
                                                

                                                <button class="btn border-primary"
                                                data-bs-toggle="modal" data-bs-target="#moreModal{{ $student->id }}">More</button>

                                                <div class="modal fade" id="moreModal{{ $student->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <div class="d-flex flex-column">
                                                        <h5 class="modal-title">{{ $student->fname }} {{ $student->lname }} - {{ $student->student_id }}</h5>
                                                        <h5 class="modal-title">{{ $student->section->grade->roman_numeral }} - {{ $student->section->name }}</h5>
                                                        </div>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <p>

                                                            <strong>E-mail</strong><br>{{ $student->email }}<br><br>
                                                            <strong>Address: </strong><br>{{ $student->address }}<br><br>
                                                            <strong>Guardian </strong><br>{{ $student->guardian->name ?? ''}}<br><br>
                                                            <strong>Guardian E-mail</strong><br>{{ $student->guardian->email ?? '' }}


                                                        </p>

                                                    </div>
                                                    </div>
                                                </div>
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        
                        @if(isset($drops[$section->id]))
                        <h4 class="mt-3">Dropped Students</h4>
                            <div class="card-body p-0">
                            <table class="table table-striped table-hover mb-0">
                                <thead><tr>
                                    @php
                                        $stud_head = array('Student Number', 'Name', 'E-mail', 'Address');
                                        foreach($stud_head as $head){
                                            echo "<th>$head</th>";
                                        }
                                    @endphp
                                </tr></thead>
                                <tbody>
                            @foreach($drops[$section->id] as $drop)
                                <tr>
                                    <td>{{ $drop->arcstudent->student_id }}</td>
                                    <td>
                                        {{ $drop->arcstudent->lname }}, {{ $drop->arcstudent->fname }}
                                    </td>
                                    <td>{{ $drop->arcstudent->email }}</td>
                                    <td>{{ $drop->arcstudent->address }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                        @endif
                    </details>

        @elseif($students->has($section->id) && $students->get($section->id)->count() > 0)

        <details id="details-{{ $section->id }}" class="mt-3">
                        <summary style="display:none;"></summary>
                        @php
                            $schedule = $schedules->firstWhere('section_id', $section->id);
                        @endphp

                        <p class="mb-3">No attendance records found today.</p>

                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                            <h6 class="mb-0">📅 Schedule</h6>
                            @if(!$schedule)
                                <button type="button" class="btn btn-success"
                                    onclick="prepareAddSchedule({{ $section->id }}, '{{ $section->grade->roman_numeral }} - {{ $section->name }}')"
                                    data-bs-toggle="modal" data-bs-target="#addSchedModal">+ Add Schedule</button>
                            @else
                                <span class="badge badge-info">{{ $schedule->start_time->format('g:i A') }} - {{ $schedule->end_time->format('g:i A') }}</span>
                                <span class="badge badge-warning">{{ $schedule->schedlimit->late_time->format('g:i A') }}</span>
                                <span class="badge badge-danger">{{ $schedule->schedlimit->absent_time->format('g:i A') }}</span>
                                <button type="button" class="btn btn-secondary"
                                    data-bs-toggle="modal" data-bs-target="#editSchedModal{{ $section->id }}">Edit</button>
                                <!-- Edit Schedule Modal -->
                                <div class="modal fade" id="editSchedModal{{ $section->id }}" tabindex="-1" aria-labelledby="editSchedModalLabel{{ $section->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Schedule Settings</h5>
                                                <div class="alert alert-info mb-0 ms-2">
                                                    <strong>{{ $section->grade->roman_numeral }} - {{ $section->name }}</strong>
                                                </div>
                                            </div>
                                            <form action="{{ route('edit-sched', $schedule->id) }}" method="POST">
                                                <div class="modal-body">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="section_id" id="edit_section_id">
                                                    <input type="hidden" name="subject_id" value="{{ $inst_subs->subject_id }}">
                                                    <label for="start_time">Start Time</label>
                                                    <input type="time" name="start_time" id="edit_start_time" value="{{ $schedule->start_time->format('H:i') }}" required>
                                                    <label for="end_time">End Time</label>
                                                    <input type="time" name="end_time" id="edit_end_time" value="{{ $schedule->end_time->format('H:i') }}" required>
                                                    <div class="alert alert-info mt-3" style="font-size: 13px;">
                                                        <strong>ℹ️ Auto-calculated:</strong><br>
                                                        • Late time: 25% of class duration<br>
                                                        • Absent time: 40% of class duration
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Confirm</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <h4 class="mt-2">Students - {{ $students->get($section->id)->count() }}</h4>
                        <div class="table-controls">
                        <input type="text" 
                               class="table-search form-control" 
                               placeholder="🔍 Search students" 
                               data-table="students-table-{{ $section->id }}">
                        </div>

                        <div class="card-body p-0" style="overflow-x: auto;">
        
<table class="table table-striped table-hover mb-0" id="students-table-{{ $section->id }}">

                            <thead><tr>
                                <th><button class="sort-btn" onclick="sortTable('students-table-{{ $section->id }}', 0, this)">Student Number</button></th>
                                <th><button class="sort-btn" onclick="sortTable('students-table-{{ $section->id }}', 1, this)">Name</button></th>
                                <th><button class="sort-btn" onclick="sortTable('students-table-{{ $section->id }}', 2, this)">E-mail</button></th>
                                <th>Actions</th>
                            </tr></thead>
                                <tbody>
                                    @foreach($students->get($section->id) as $student)
                                        <tr>
                                            <td>{{ $student->student_id }}</td>
                                            <td>{{ $student->lname }}, {{ $student->fname }}</td>
                                            <td>{{ $student->email }}</td>
                                            <td class="d-flex gap-2 justify-content-center">

                                                <form action="/checkattendance" method="POST">
                                                    @csrf

                                                    <input type="hidden" name="student_id" value="{{ $student->student_id }}">
                                                    @if($student->qr)
                                                        <input type="hidden" name="qr_key" value="{{ $student->qr->qr_key }}">
                                                    @endif

                                                    <button type="button" class="btn border-success" title="Check Attendance"
                                                    data-bs-toggle="modal" data-bs-target="#checkBoxModal{{ $student->id }}">✔</button>

                                                    <!-- Check Modal -->
                                                    <div class="modal fade" id="checkBoxModal{{ $student->id }}" tabindex="-1" aria-labelledby="checkModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-body">
                                                                    Check attendance of student {{ $student->lname }}, {{ $student->fname }}?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-success">Yes</button>
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </form>

                                                <button class="btn border-primary"
                                                data-bs-toggle="modal" data-bs-target="#moreModal{{ $student->id }}">More</button>

                                                <div class="modal fade" id="moreModal{{ $student->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <div class="d-flex flex-column">
                                                        <h5 class="modal-title">{{ $student->fname }} {{ $student->lname }} - {{ $student->student_id }}</h5>
                                                        <h5 class="modal-title">{{ $student->section->grade->roman_numeral }} - {{ $student->section->name }}</h5>
                                                        </div>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <p>

                                                            <strong>E-mail</strong><br>{{ $student->email }}<br><br>
                                                            <strong>Address: </strong><br>{{ $student->address }}<br><br>
                                                            <strong>Guardian </strong><br>{{ $student->guardian->name ?? ''}}<br><br>
                                                            <strong>Guardian E-mail</strong><br>{{ $student->guardian->email ?? '' }}


                                                        </p>

                                                    </div>
                                                    </div>
                                                </div>
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        
                        @if(isset($drops[$section->id]))
                        <h4 class="mt-3">Dropped Students</h4>
                            <div class="card-body p-0">
                            <table class="table table-striped table-hover mb-0">
                                <thead><tr>
                                    @php
                                        $stud_head = array('Student Number', 'Name', 'E-mail', 'Address');
                                        foreach($stud_head as $head){
                                            echo "<th>$head</th>";
                                        }
                                    @endphp
                                </tr></thead>
                                <tbody>
                            @foreach($drops[$section->id] as $drop)
                                <tr>
                                    <td>{{ $drop->arcstudent->student_id }}</td>
                                    <td>
                                        {{ $drop->arcstudent->lname }}, {{ $drop->arcstudent->fname }}
                                    </td>
                                    <td>{{ $drop->arcstudent->email }}</td>
                                    <td>{{ $drop->arcstudent->address }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                        @endif

                    </details>
                @endif
            </div>
            @endif
        @endforeach
            </div>
        </div>
    </div>

    <!-- Account Settings Modal -->
    <div class="modal fade" id="acctSetModal" tabindex="-1" aria-labelledby="acctSetModalLabel" aria-hidden="true">
        <div class="modal-dialog dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Account Settings</h3>
                </div>
                <div class="modal-body">
                    <a href='{{ route('instructor.edit-profile', $instructors->id) }}' class="btn btn-primary w-100 mb-2">✏️ Edit Profile</a>
                    <form action='/authotp' method='POST'>
                        @csrf
                        <button class="btn btn-primary w-100">🔐 Change Password</button>
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

    <!-- Add Sched Modal -->
    <div class="modal fade" id="addSchedModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Schedule Settings</h5>
            <div class="alert alert-info">
                <strong id="section_name_display"></strong>
            </div>
        </div>
        <form action="/addsched" method="POST">
        <div class="modal-body">
                @csrf

                <input type="hidden" name="section_id" id="add_section_id">
                <input type="hidden" name="subject_id" value="{{ $inst_subs->subject_id }}">
                <label for="start_time">Start Time</label>
                <input type="time" name="start_time" id="add_start_time" required>
                <label for="end_time">End Time</label>
                <input type="time" name="end_time" id="add_end_time" required>
                <div class="alert alert-info mt-3" style="font-size: 13px;">
                    <strong>ℹ️ Auto-calculated:</strong><br>
                    • Late time: 25% of class duration<br>
                    • Absent time: 40% of class duration
                </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success">Confirm</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </form>
        </div>
    </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

        <script>

            document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    sidebarOverlay.classList.toggle('active');
                });
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                });
            }

            // Set active menu item based on current URL
            const currentPath = window.location.pathname;
            const menuItems = document.querySelectorAll('.sidebar-menu .menu-item');
            menuItems.forEach(item => {
                const link = item.closest('a');
                if (link && link.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                }
            });
        });

            window.addEventListener('load', function() {
                document.getElementById('student_id').focus();
            });

            function prepareAddSchedule(sectionId, sectionName) {
                // Set the section ID in the hidden field
                document.getElementById('add_section_id').value = sectionId;

                // Display the section name so user knows which section they're setting
                document.getElementById('section_name_display').textContent = sectionName;
            }

            // Search functionality
            function initializeSearch() {
                // Get all search inputs
                const searchInputs = document.querySelectorAll('.table-search');

                searchInputs.forEach(input => {
                    input.addEventListener('keyup', function() {
                        const searchValue = this.value.toLowerCase();
                        const tableId = this.getAttribute('data-table');
                        const table = document.getElementById(tableId);
                        const rows = table.querySelectorAll('tbody tr');

                        rows.forEach(row => {
                            const text = row.textContent.toLowerCase();
                            if (text.includes(searchValue)) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        });
                    });
                });
            }

            // Sort functionality
            function sortTable(tableId, columnIndex, button) {
                const table = document.getElementById(tableId);
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr'));

                // Get current sort direction
                const currentDir = button.getAttribute('data-sort-dir') || 'asc';
                const newDir = currentDir === 'asc' ? 'desc' : 'asc';

                // Remove sort indicators from all buttons in this table
                const allButtons = table.querySelectorAll('.sort-btn');
                allButtons.forEach(btn => {
                    btn.setAttribute('data-sort-dir', '');
                    btn.innerHTML = btn.innerHTML.replace(' ↑', '').replace(' ↓', '');
                });

                // Set new sort direction
                button.setAttribute('data-sort-dir', newDir);

                // Sort rows
                rows.sort((a, b) => {
                    const aValue = a.cells[columnIndex].textContent.trim();
                    const bValue = b.cells[columnIndex].textContent.trim();

                    // Try to parse as numbers
                    const aNum = parseFloat(aValue);
                    const bNum = parseFloat(bValue);

                    let comparison = 0;

                    if (!isNaN(aNum) && !isNaN(bNum)) {
                        // Numeric comparison
                        comparison = aNum - bNum;
                    } else {
                        // String comparison
                        comparison = aValue.localeCompare(bValue);
                    }

                    return newDir === 'asc' ? comparison : -comparison;
                });

                // Add sort indicator
                button.innerHTML = button.innerHTML + (newDir === 'asc' ? ' ↑' : ' ↓');

                // Reorder rows in DOM
                rows.forEach(row => tbody.appendChild(row));
            }

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                initializeSearch();
            });

        </script>


<script>
document.addEventListener("DOMContentLoaded", () => {
document.querySelectorAll(".search-input").forEach(input => {
input.addEventListener("keyup", function () {
const tableId = this.dataset.table;
const term = this.value.toLowerCase();
const rows = document.querySelectorAll(`#${tableId} tbody tr`);
rows.forEach(row => { row.style.display = row.innerText.toLowerCase().includes(term) ? "" : "none"; });
});
});
document.querySelectorAll(".sort-btn").forEach(btn => {
btn.addEventListener("click", function () {
const tableId = this.dataset.table;
const col = this.dataset.col;
const table = document.getElementById(tableId);
const tbody = table.querySelector("tbody");
const rows = Array.from(tbody.querySelectorAll("tr"));
const current = this.dataset.order === "asc" ? "desc" : "asc";
this.dataset.order = current;
rows.sort((a, b) => {
let A = a.children[col].innerText.trim().toLowerCase();
let B = b.children[col].innerText.trim().toLowerCase();
const numA = parseFloat(A), numB = parseFloat(B);
if (!isNaN(numA) && !isNaN(numB)) return current==="asc"? numA-numB : numB-numA;
return current==="asc"? A.localeCompare(B):B.localeCompare(A);
});
rows.forEach(r=>tbody.appendChild(r));
});
});
});
</script>

</body>
</html>

@endsection

