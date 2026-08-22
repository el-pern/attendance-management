@extends('Layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sections</title>

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
        .search-bar {
            margin-bottom: 25px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .search-bar input {
            flex: 1;
            padding: 12px 18px;
            border: 2px solid #232528;
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .search-bar input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(35, 37, 40, 0.1);
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
        .no-results {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>

</head>
<body>
    <div class="container">
        <div class="content-container">
            <div class="header-actions">
                <a href='/admin'><button class="btn btn-secondary">← Back</button></a>
                <a href='/admin/add-section'><button class="btn btn-success">Add Section</button></a>
            </div>

            <h3>Sections</h3>

            @if($sections->count() > 0)
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="🔍 Search by grade level or section name..." onkeyup="searchTable()">
            </div>

            <div class="table-container">
                <table class="table" id="sectionTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)">Grade Level <span class="sort-icon">⬍</span></th>
                            <th onclick="sortTable(1)">Section Name <span class="sort-icon">⬍</span></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @foreach($sections as $section)

                            <tr>
                                <td style="text-align: center;">{{ $section->grade->roman_numeral }}</td>
                                <td>{{ $section->name }}</td>
                                <td class="d-flex gap-2 justify-content-center">

                                    <a href="{{ route('admin.edit-section', $section->id) }}"><button class="btn border-primary" title="Edit">✏️</button></a>

                                    <form action="/admin/delete-section/{{ $section->id }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn border-danger" type="button" title="Delete" data-bs-toggle="modal" data-bs-target="#delSectModal{{ $section->id }}">🗑️</button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="delSectModal{{ $section->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Section</h1>
                                            </div>
                                        <div class="modal-body">
                                            Proceed to delete section {{ $section->name }}?
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
            <div id="noResults" class="no-results" style="display: none;">
                No sections found matching your search.
            </div>
            @else

                <h4>No sections created</h4>
                
            @endif
        </div>
    </div>

    <script>
        let sortDirection = [];
        
        function searchTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('sectionTable');
            const tbody = document.getElementById('tableBody');
            const tr = tbody.getElementsByTagName('tr');
            let visibleCount = 0;

            for (let i = 0; i < tr.length; i++) {
                const tdArray = tr[i].getElementsByTagName('td');
                let found = false;
                
                // Search only the first two columns (grade level and section name)
                for (let j = 0; j < 2; j++) {
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
            const table = document.getElementById('sectionTable');
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

