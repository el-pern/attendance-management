@extends('Layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Guardians</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('SAS.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .header-banner {
            background: #232528;
            color: white;
            padding: 26px 30px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
        }
        .header-banner h3 {
            margin: 0;
            font-weight: 700;
            font-size: 26px;
        }
        .header-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .card-shell {
            background: white;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }
        .search-bar {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .search-bar input {
            flex: 1;
            padding: 10px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
        }
        .search-bar input:focus {
            outline: none;
            border-color: #232528;
        }
        .table-container {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        }
        .table thead th {
            background: #232528;
            color: white;
            border: none;
            font-size: 13px;
            letter-spacing: 0.6px;
            cursor: pointer;
            user-select: none;
            position: relative;
            padding-right: 25px;
        }
        .table thead th:hover {
            background: #3a3d42;
        }
        .table thead th .sort-icon {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 10px;
            opacity: 0.5;
        }
        .table thead th.sorted .sort-icon {
            opacity: 1;
        }
        .table tbody td {
            vertical-align: middle;
        }
        .btn {
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-radius: 10px;
            padding: 10px 14px;
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
        }
        .btn-success {
            background: #28a745;
            border: 2px solid #28a745;
        }
        .no-results {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>
<body>

    <div class="page-container">
        <div class="header-banner">
            <h3>🧑‍🤝‍🧑 Parents/Guardians</h3>
            <div class="header-actions">
                <a href='/admin' class="btn btn-secondary">Home</a>
                <a href='/admin/view-students' class="btn btn-secondary">View Students</a>
            </div>
        </div>

        <div class="card-shell">
            @if($guardians->count() > 0)
                <div class="search-bar">
                    <input type="text" id="searchInput" placeholder="🔍 Search by name, email, or student..." onkeyup="searchTable()">
                </div>

                <div class="table-container">
                    <table class="table table-striped table-hover mb-0" id="guardianTable">
                        <thead>
                            <tr>
                                <th onclick="sortTable(0)">Name <span class="sort-icon">⬍</span></th>
                                <th onclick="sortTable(1)">E-mail <span class="sort-icon">⬍</span></th>
                                <th onclick="sortTable(2)">Student <span class="sort-icon">⬍</span></th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @foreach($guardians as $guardian)
                                <tr>
                                    <td>{{ $guardian->name }}</td>
                                    <td>{{ $guardian->email }}</td>
                                    <td>{{ $guardian->student->lname }}, {{ $guardian->student->fname }}</td>
                                    <td class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('admin.edit-guardian', $guardian->id) }}" class="btn btn-primary btn-sm" title="Edit">✏️</a>

                                        <form action="/sendorientemail/{{ $guardian->id }}" method="POST">
                                        @csrf
                                        <button type="button" class="btn btn-primary btn-sm" title="Send Mail"
                                        data-bs-toggle="modal"
                                        data-bs-target="#orientModal{{ $guardian->id }}">📬</button>

                                        <div class="modal fade" id="orientModal{{ $guardian->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    Send orientation e-mail to guardian {{ $guardian->name }}?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Send</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
                <div id="noResults" class="no-results" style="display: none;">
                    No guardians found matching your search.
                </div>
            @else
                <h4 class="mb-0">No guardians added</h4>
            @endif
        </div>
    </div>

    <script>
        let sortDirection = [];
        
        function searchTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('guardianTable');
            const tbody = document.getElementById('tableBody');
            const tr = tbody.getElementsByTagName('tr');
            let visibleCount = 0;

            for (let i = 0; i < tr.length; i++) {
                const tdArray = tr[i].getElementsByTagName('td');
                let found = false;
                
                for (let j = 0; j < tdArray.length - 1; j++) {
                    const td = tdArray[j];
                    if (td) {
                        const txtValue = td.textContent || td.innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                
                if (found) {
                    tr[i].style.display = '';
                    visibleCount++;
                } else {
                    tr[i].style.display = 'none';
                }
            }

            document.getElementById('noResults').style.display = visibleCount === 0 ? 'block' : 'none';
            table.style.display = visibleCount === 0 ? 'none' : 'table';
        }

        function sortTable(columnIndex) {
            const table = document.getElementById('guardianTable');
            const tbody = document.getElementById('tableBody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const headers = table.querySelectorAll('thead th');
            
            if (sortDirection[columnIndex] === undefined) {
                sortDirection[columnIndex] = true;
            } else {
                sortDirection[columnIndex] = !sortDirection[columnIndex];
            }
            
            const isAscending = sortDirection[columnIndex];
            
            rows.sort((a, b) => {
                const aValue = a.cells[columnIndex].textContent.trim().toLowerCase();
                const bValue = b.cells[columnIndex].textContent.trim().toLowerCase();
                
                if (aValue < bValue) return isAscending ? -1 : 1;
                if (aValue > bValue) return isAscending ? 1 : -1;
                return 0;
            });
            
            rows.forEach(row => tbody.appendChild(row));
            
            headers.forEach((header, index) => {
                header.classList.remove('sorted');
                const icon = header.querySelector('.sort-icon');
                if (icon) icon.textContent = '⬍';
            });
            
            headers[columnIndex].classList.add('sorted');
            const icon = headers[columnIndex].querySelector('.sort-icon');
            if (icon) icon.textContent = isAscending ? '▲' : '▼';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-oBqDVmMz4fnFO9gybBogGzPztE1M5rZG/8Xlqh8fATrSWJZDmmW4Ll48dWkOVbCH"
    crossorigin="anonymous"></script>

    @endsection
</body>
</html>

