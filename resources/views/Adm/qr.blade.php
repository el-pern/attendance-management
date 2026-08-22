@extends('Layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Generate Student QR</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('SAS.png') }}">

    <!-- Friggin bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous">

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
            max-width: 700px;
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
        .header-actions {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            justify-content: center;
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
        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #232528;
            background: white;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus {
            outline: none;
            background: #f5f5f5;
        }
        .btn {
            padding: 12px 30px;
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
        .btn-primary {
            background: #232528;
            border: 2px solid #232528;
            color: white;
            width: 100%;
        }
        .btn-primary:hover {
            background: white;
            color: #232528;
            border: 2px solid #232528;
        }
        .text-danger {
            font-size: 14px;
            margin-top: 5px;
            color: #232528;
        }
        .qr-display {
            text-align: center;
            margin-top: 30px;
            padding: 30px;
            border: 2px solid #232528;
            background: white;
        }
        .qr {
            display: inline-block;
        }
        .printbtn {
            width: 100%;
            margin-top: 20px;
        }

        /*print preview should only show qr*/
        @media print {
        body *{
            visibility: hidden;
        }

        .qr *{
            visibility: visible;
        }}
    </style>

</head>
<body>
    <div class="container">
        <div class="content-container">
            <h3>Generate Student QR Code</h3>
        
            <div class="header-actions">
                <a href='/admin'><button class="btn btn-secondary">← Back</button></a>
            </div>
        
            <form action="/admin/qr-code" method="POST">
            
                @csrf
                <div class="form-group">
                    <label for="studno">Student Number</label>
                    <input type="text" name="studno" id="studno">
                    @error('studno')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn btn-primary" type="submit">Generate QR Code</button>
            
            </form>


            @if(isset($qrcode))
                <div class="qr-display">
                    <div class="qr">{!! $qrcode !!}</div>
                    <button class="printbtn btn btn-secondary" onclick="window.print()">Print QR Code</button>
                </div>
            @endif
        </div>
    </div>

    @endsection

</body>
</html>

