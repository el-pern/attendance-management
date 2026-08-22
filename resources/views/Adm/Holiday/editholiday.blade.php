@extends('Layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Holiday</title>

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
        .header-banner {
            background: #232528;
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header-banner h3 {
            margin: 0;
            font-weight: 700;
            font-size: 28px;
            letter-spacing: 1px;
        }
        .form-container {
            background: white;
            border-radius: 10px;
            padding: 40px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        .form-group {
            margin-bottom: 25px;
        }
        label {
            font-weight: 600;
            color: #232528;
            margin-bottom: 8px;
            display: block;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }
        input[type="text"], select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            background: white;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus, select:focus {
            outline: none;
            border-color: #232528;
            box-shadow: 0 0 0 3px rgba(35, 37, 40, 0.1);
        }
        .date-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .btn-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        .btn {
            padding: 12px 30px;
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
            flex: 1;
        }
        .btn-primary:hover {
            background: white;
            color: #232528;
            border: 2px solid #232528;
        }
        .btn-success {
            background: #28a745;
            border: 2px solid #28a745;
            color: white;
        }
        .btn-success:hover {
            background: white;
            color: #28a745;
            border: 2px solid #28a745;
        }
        .back-btn-container {
            text-align: center;
            margin-bottom: 20px;
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
    
    <div class="container">
        <div class="header-banner">
            <h3>✏️ Edit Holiday</h3>
        </div>

        <div class="back-btn-container">
            <a href='/admin/holidays'><button class="btn btn-secondary">← Back</button></a>
        </div>

        <div class="form-container">

            <form action="{{ route('admin.edit-holiday', $holiday->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Holiday Name</label>
                    <input type="text" name="name" id="name" value="{{ $holiday->name }}" placeholder="Enter holiday name" required>
                </div>

                @php

                    $existing_month = (int) substr($holiday->holiday_date, 0, 2);
                    $existing_day = (int) substr($holiday->holiday_date, 3, 2);

                    $month_arr = ['January', 'February', 'March',
                    'April', 'May', 'June', 'July', 'August',
                    'September', 'October', 'November', 'December'];

                @endphp

                <div class="date-group">
                    <div class="form-group">
                        <label for="month">Month</label>
                        <select name="hol_month" id="month" required>
                            
                            @php

                            $month_num = 1;
                            foreach($month_arr as $month){
                                $selected = $month_num === $existing_month ? 'selected' : '';
                                echo "<option value='{$month_num}'{$selected}>{$month}</option>";
                                $month_num++;
                            }

                            @endphp

                        </select>
                    </div>

                    <div class="form-group">
                        <label for="day">Day</label>
                        <select name="hol_day" id="day" required>

                            <option value="">-- Select Day --</option>
                    

                        </select>
                    </div>
                </div>

                <div class="btn-container">
                    <button type="button" class="btn btn-primary"
                    data-bs-toggle="modal" data-bs-target="#holidayModal">Save Changes</button>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="holidayModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-body">
                        Save changes for this holiday?
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

    <script>
        const existingDay = {{ $existing_day }};

        function populateDays(month, selectedDay = null) {
        const daySelect = document.getElementById('day');

        // Clear existing options
        daySelect.innerHTML = '<option value="">-- Select Day --</option>';

        if (!month) return;

        // Determine days in month
        let daysInMonth;
        if (month === 2) {
        daysInMonth = 29; // February (including leap year)
        } else if ([4, 6, 9, 11].includes(month)) {
        daysInMonth = 30; // April, June, September, November
        } else {
        daysInMonth = 31; // All other months
        }

        // Populate day options
        for (let day = 1; day <= daysInMonth; day++) {
        const option = document.createElement('option');
        option.value = day;
        option.textContent = day;
        if (selectedDay && day === selectedDay) {
        option.selected = true;
        }
        daySelect.appendChild(option);
        }
        }

        // Populate days on page load with existing month and day
        document.addEventListener('DOMContentLoaded', function() {
        const monthSelect = document.getElementById('month');
        const selectedMonth = parseInt(monthSelect.value);

        if (selectedMonth) {
        populateDays(selectedMonth, existingDay);
        }
        });

        document.getElementById('month').addEventListener('change', function() {
        const month = parseInt(this.value);
        populateDays(month);
        });
    </script>

    @endsection
</body>
</html>

