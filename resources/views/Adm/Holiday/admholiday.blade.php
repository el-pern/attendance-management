@extends('Layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Holidays</title>

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
        .content-container {
            background: white;
            border-radius: 10px;
            padding: 40px;
            max-width: 1000px;
            margin: 0 auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        h3 {
            color: #232528;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        h4 {
            color: #232528;
            text-align: center;
            padding: 40px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header-actions {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn {
            padding: 10px 25px;
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
        .btn-danger {
            background: #232528;
            border: 2px solid #232528;
            color: white;
        }
        .btn.border-danger {
            background: white;
            border: 2px solid #232528;
            color: #232528;
            padding: 8px 15px;
        }
        .btn.border-danger:hover {
            background: #232528;
            color: white;
        }
        .btn-danger:hover {
            background: white;
            color: #232528;
            border: 2px solid #232528;
        }
        .table-container {
            overflow-x: auto;
            border: 2px solid #232528;
            border-radius: 10px;
        }
        .table {
            margin-bottom: 0;
        }
        .table thead th {
            background: #232528;
            color: white;
            font-weight: 600;
            border: 2px solid #232528;
            padding: 15px;
            text-align: center;
            vertical-align: middle;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 12px;
        }
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border: 1px solid #232528;
        }
        .table tbody tr {
            transition: all 0.3s ease;
        }
        .table tbody tr:hover {
            background-color: #f5f5f5;
        }
        .no-border {
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="content-container">
             <div class="header-actions">
                <a href='/admin'><button class="btn btn-secondary">🏠 Home</button></a>
                <a href='/admin/addholiday'><button class="btn btn-success">+ Add Holiday</button></a>
            </div>

            <h3>🗓️ Holidays</h3>
    
    @if($holidays->count() > 0)

        <div class="table-container">
            <table class="table table-bordered">
                <thead>
                    <?php
                        
                    $holiday_head = array("Name", "Date", "Actions");
                    foreach($holiday_head as $head){
                        echo "<th>$head</th>";
                    }
                    
                    ?>
                </thead>
                <tbody>
                    @foreach($holidays as $holiday)
                        <tr>
                            <td>{{ $holiday->name }}</td>
                            <td style="text-align: center;">{{ $holiday->holiday_date }}</td>
                            <td class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('admin.edit-holiday', $holiday->id)}}"><button title="Edit" class="btn border-primary">✏️</button></a>
                                <form action="{{ route('admin.del-holiday', $holiday->id) }}" method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button type="button" title="Delete" class="btn border-danger"
                                    data-bs-toggle="modal" data-bs-target="#delHolidayModal{{ $holiday->id }}">🗑️</button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="delHolidayModal{{ $holiday->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Period</h1>
                                        </div>
                                    <div class="modal-body">
                                        Proceed to delete holiday {{ $holiday->name }}?
                                    </div>
                                    <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </div>
                                        </div>
                                    </div>
                                    </div>

                                </form>
                            </td>
                            
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    @else

        <h4>No holidays created</h4>

    @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-oBqDVmMz4fnFO9gybBogGzPztE1M5rZG/8Xlqh8fATrSWJZDmmW4Ll48dWkOVbCH"
    crossorigin="anonymous"></script>

    @endsection
</body>
</html>

