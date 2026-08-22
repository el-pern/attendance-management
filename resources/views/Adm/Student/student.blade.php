@extends('Layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Students</title>

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
            max-width: 1400px;
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
            text-align: center;
        }
        .header-banner h3 {
            margin: 0;
            font-weight: 700;
            font-size: 26px;
            letter-spacing: 0.5px;
        }
        .content-container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
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
            gap: 12px;
            margin-bottom: 24px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .search-bar {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .search-bar input {
            flex: 1;
            padding: 12px 18px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
        }
        .search-bar input:focus {
            outline: none;
            border-color: #232528;
        }
        .btn {
            padding: 12px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-size: 13px;
            border-radius: 8px;
            border: 2px solid transparent;
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
            border-color: #28a745;
            color: white;
        }
        .btn-success:hover {
            background: white;
            color: #28a745;
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
            font-size: 12px;
        }
        .btn.border-primary:hover {
            background: #232528;
            color: white;
        }
        .btn.border-warning {
            background: white;
            border: 2px solid #ffc107;
            color: #232528;
            padding: 8px 15px;
            font-size: 12px;
        }
        .btn.border-warning:hover {
            background: #ffc107;
            color: #232528;
        }
        .btn-warning {
            background: #ffc107;
            border-color: #ffc107;
            color: #232528;
        }
        .btn-warning:hover {
            background: white;
            color: #232528;
        }
        .table-container {
            overflow-x: auto;
            border-radius: 8px;
            overflow: hidden;
        }
        .table {
            margin-bottom: 0;
            border-radius: 8px;
        }
        .table thead th {
            background: #232528;
            color: white;
            font-weight: 600;
            border: none;
            padding: 15px;
            text-align: center;
            vertical-align: middle;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            font-size: 13px;
            cursor: pointer;
            user-select: none;
            position: relative;
            padding-right: 30px;
        }
        .table thead th:hover {
            background: #3a3d42;
        }
        .table thead th:last-child {
            cursor: default;
        }
        .table thead th:last-child:hover {
            background: #232528;
        }
        .table thead th .sort-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 10px;
            opacity: 0.5;
        }
        .table thead th.sorted .sort-icon {
            opacity: 1;
        }
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #e0e0e0;
        }
        .table tbody tr {
            transition: all 0.3s ease;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .modal-content {
            border: 2px solid #232528;
            border-radius: 12px;
        }
        .modal-header {
            background: #232528;
            color: white;
            border-bottom: none;
            border-radius: 12px 12px 0 0;
            padding: 20px 30px;
        }
        .modal-title {
            color: white;
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
            border-top: 2px solid #e0e0e0;
            padding: 20px 30px;
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
            <h3>👥 Students</h3>
        </div>
        
        <div class="content-container">
            <div class="header-actions">
                <a href='/admin'><button class="btn btn-secondary">🏠 Home</button></a>
                <a href='/admin/add-student'><button class="btn btn-success">📥
Import Students</button></a>
                <a href='/admin/arc-students'><button class="btn btn-primary">📦 View Archived Students</button></a>
                <a href='/admin/guardians'><button class="btn btn-primary">👨‍👩‍👧 View Guardians</button></a>
            </div>
            
            @if($students->count() > 0)
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="🔍 Search by name, email, address, student no, grade, or section..." onkeyup="searchTable()">
            </div>

            <div class="table-container">
                <table class="table" id="studentTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)">Name <span class="sort-icon">⬍</span></th>
                            <th onclick="sortTable(1)">E-mail <span class="sort-icon">⬍</span></th>
                            <th onclick="sortTable(2)">Address <span class="sort-icon">⬍</span></th>
                            <th onclick="sortTable(3)">Student No. <span class="sort-icon">⬍</span></th>
                            <th onclick="sortTable(4)">Grade Level <span class="sort-icon">⬍</span></th>
                            <th onclick="sortTable(5)">Section <span class="sort-icon">⬍</span></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @foreach($students as $student)
                            <tr>
                                <td>{{ $student->lname }}, {{ $student->fname }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->address }}</td>
                                <td>{{ $student->student_id }}</td>
                                <td style="text-align: center;">{{ $student->section->grade->roman_numeral }}</td>
                                <td>{{ $student->section->name }}</td>
                                <td class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('admin.edit-student', $student->id) }}"><button class="btn border-primary" title="Edit">✏️</button></a>

                                    <form action="/admin/delete-student/{{ $student->id }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn border-warning" type="button" title="Archive" data-bs-toggle="modal"
                                        data-bs-target="#delStudModal{{ $student->id }}">🗃️</button>

                                        <div class="modal fade" id="delStudModal{{ $student->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Archive Student</h1>
                                                </div>
                                                <div class="modal-body">
                                                    Proceed to archive student {{ $student->lname }}, {{ $student->fname }}?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-warning">Archive</button>
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
                No students found matching your search.
            </div>
            @else
                <h4>No students added</h4>
            @endif
        </div>
    </div>

    <script>
        let sortDirection = [];
        
        function searchTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('studentTable');
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
            const table = document.getElementById('studentTable');
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

