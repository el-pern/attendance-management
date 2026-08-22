<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    
    <div id="app">

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(Session::has('success'))
                Swal.fire({
                    title: 'Success!',
                    text: "{{ Session::get('success') }}",
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end'
                });
            @elseif(Session::has('error'))
                Swal.fire({
                    title: 'Error!',
                    text: "{{ Session::get('error') }}",
                    icon: 'error',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end'
            });
            @elseif(Session::has('info'))
                Swal.fire({
                    title: 'Info',
                    text: "{{ Session::get('info') }}",
                    icon: 'info',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end'
                });

            @elseif(Session::has('warning'))
                Swal.fire({
                    title: 'Warning',
                    text: "{{ Session::get('warning') }}",
                    icon: 'warning',
                    confirmButtonColor: '#ffcc00',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end'
                });
            @endif
        });
    </script>

</body>
</html>