@extends('Layouts.app')
@section('content')


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Friggin bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous">

    <title>User Verification</title>
    <style>
        body {
            background: #f5f5f5;
            min-height: 100vh;
            padding: 40px 0;
        }
        .content-container {
            background: white;
            border: 2px solid #232528;
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }
        h3 {
            color: #232528;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        p {
            text-align: center;
            color: #232528;
            padding: 40px;
        }
        .header-actions {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            justify-content: center;
        }
        .btn {
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 14px;
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
            background: #232528;
            border: 2px solid #232528;
            color: white;
        }
        .btn-success:hover {
            background: white;
            color: #232528;
            border: 2px solid #232528;
        }
        .btn-danger {
            background: #232528;
            border: 2px solid #232528;
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
        .modal-content {
            border: 2px solid #232528;
        }
        .modal-header {
            border-bottom: 2px solid #232528;
            padding: 20px 30px;
        }
        .modal-title {
            color: #232528;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
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
        <div class="content-container">
            <h3>Unverified Users</h3>
            @auth

                <div class="header-actions">
                    <a href='/admin'><button class="btn btn-secondary">← Back</button></a>
                </div>

                @if($unverifiedUsers->count() > 0)
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <?php
                                
                                $unverify_user_head = array("Name", "Email", "Actions");
                                foreach($unverify_user_head as $head){
                                    echo "<th>$head</th>";
                                }
                                    
                                ?>
                            </thead>
                            <tbody>
                                @foreach($unverifiedUsers as $user)

                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td class="d-flex gap-2 justify-content-center">

                                            <form action="{{ route('admin.verify.users', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#verifyModal{{$user->id}}">Verify</button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="verifyModal{{ $user->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">User Verification</h1>
                                                    </div>
                                                <div class="modal-body">
                                                    Proceed to verify user {{ $user->name }}?
                                                </div>
                                                <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success">Verify</button>
                                                    </div>
                                                    </div>
                                                </div>
                                                </div>

                                            </form>

                                            <form action="{{ route('admin.decline.users', $user->id)}}" method="POST">

                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#declineModal{{$user->id}}">Decline</button>

                                                <!-- Modal -->
                                                <div class="modal fade" id="declineModal{{ $user->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">User Verification</h1>
                                                    </div>
                                                <div class="modal-body">
                                                    Proceed to decline user {{ $user->name }}?
                                                </div>
                                                <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Decline</button>
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
                    <p>No unverified users found.</p>
                @endif

            
            @endauth
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-oBqDVmMz4fnFO9gybBogGzPztE1M5rZG/8Xlqh8fATrSWJZDmmW4Ll48dWkOVbCH"
    crossorigin="anonymous"></script>
    
    @endsection
</body>
</html>

