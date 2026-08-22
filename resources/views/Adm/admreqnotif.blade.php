<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Request Notifications</title>

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
        .btn-primary, .mail {
            background: #232528;
            border: 2px solid #232528;
            color: white;
        }
        .btn-primary:hover, .mail:hover {
            background: white;
            color: #232528;
            border: 2px solid #232528;
        }
        .table-container {
            overflow-x: auto;
            border: 2px solid #232528;
            margin-bottom: 30px;
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
        .footer-actions {
            text-align: center;
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
            <h3>Request Notifications</h3>

            <div class="header-actions">
                <a href='/admin'><button class="btn btn-secondary">Home</button></a>
                <a href="https://mail.google.com/mail"><button class="mail btn btn-secondary">Inbox</button></a>
            </div>

            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="🔍 Search notifications..." onkeyup="searchTable()">
            </div>

            <div class="table-container">
                <table class="table table-bordered" id="notifTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable(0)">Notification <span class="sort-icon">⬍</span></th>
                            <th onclick="sortTable(1)">Date <span class="sort-icon">⬍</span></th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @foreach($notifs as $notif)
                            <tr>
                                <td>{{ $notif->info }}</td>
                                <td style="text-align: center;" data-timestamp="{{ $notif->notif_date->timestamp }}">{{ $notif->notif_date->format('Y-m-d g:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div id="noResults" class="no-results" style="display: none;">
                No notifications found matching your search.
            </div>
            
            <div class="footer-actions">
                @if(Request::is('admin/allreqnotifs'))
                    <a href="/admin/reqnotifs" class="btn btn-primary">See Less Notifications</a>
                @else
                    <a href="/admin/allreqnotifs" class="btn btn-primary">See Previous Notifications</a>
                @endif
            </div>
        </div>
    </div>

    <script>
        let sortDirection = [];
        
        function searchTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('notifTable');
            const tbody = document.getElementById('tableBody');
            const tr = tbody.getElementsByTagName('tr');
            let visibleCount = 0;

            for (let i = 0; i < tr.length; i++) {
                const tdArray = tr[i].getElementsByTagName('td');
                let found = false;
                
                for (let j = 0; j < tdArray.length; j++) {
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
            const table = document.getElementById('notifTable');
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
                let aValue, bValue;
                
                // Special handling for date column (column 1)
                if (columnIndex === 1) {
                    // Use the timestamp data attribute for accurate date sorting
                    aValue = parseInt(a.cells[columnIndex].getAttribute('data-timestamp')) || 0;
                    bValue = parseInt(b.cells[columnIndex].getAttribute('data-timestamp')) || 0;
                } else {
                    aValue = a.cells[columnIndex].textContent.trim().toLowerCase();
                    bValue = b.cells[columnIndex].textContent.trim().toLowerCase();
                }
                
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

</body>
</html>

