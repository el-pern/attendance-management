@extends('Layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Instructor Request</title>

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
        }
        .sidebar {
            width: 260px;
            background: white;
            border-radius: 10px;
            padding: 16px 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            height: fit-content;
            position: sticky;
            top: 20px;
        }
        .sidebar-header {
            padding: 16px 20px;
            border-bottom: 2px solid #e0e0e0;
            margin-bottom: 10px;
        }
        .sidebar-header h3 {
            color: #232528;
            font-weight: 700;
            font-size: 18px;
            margin: 0;
            text-align: center;
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
            padding: 14px 18px;
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
            font-size: 13px;
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
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 10px;
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
        .content-container {
            background: white;
            border-radius: 10px;
            padding: 40px;
            max-width: 900px;
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
        select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #232528;
            border-radius: 6px;
            background: white;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        select:focus {
            outline: none;
            background: #f5f5f5;
            box-shadow: 0 0 0 3px rgba(35, 37, 40, 0.1);
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
            margin-bottom: 30px;
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
        @media (max-width: 768px) {
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
                    <div class="menu-item" data-bs-toggle="modal" data-bs-target="#logoutModal">
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
                    <h3>📝 Instructor Request to Admin</h3>
                </div>

                <div class="content-container">
                    <form action="/instreq" method="POST">
                        @csrf

                        <input type="hidden" name="admin" id="admin">

                        <div class="form-group">
                            <label for="inst_request">Request Type</label>
                            <select name="inst_request" id="inst_request" required>

                                <option value="">-- Select Request --</option>
                                <option value="Archive Student">Archive Student</option>
                                <option value="Restore Student">Restore Student</option>

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="student_id">Student</label>
                            <select name="student_id" id="student" required>
                                <option value="">-- Select Student --</option>
                            </select>
                        </div>

                        <div class="form-group" id="reasonGroup">
                            <label for="reason">Reason</label>
                            <select name="reason" id="reason" required>
                            
                                <option value="">-- Select Reason --</option>
                                <option value="Lost/Stolen ID">Lost/Stolen ID</option>
                                <option value="Dropping">Dropping</option>
                                <option value="Transferring">Transferring</option>
                                <option value="Expulsion">Expulsion</option>
                                <option value="Graduation">Graduation</option>
                            </select>
                        </div>

                        <button class="btn btn-primary" type="submit">Submit Request</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-oBqDVmMz4fnFO9gybBogGzPztE1M5rZG/8Xlqh8fATrSWJZDmmW4Ll48dWkOVbCH"
    crossorigin="anonymous"></script>
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
    </script>

    <!-- Account Settings Modal -->
    <div class="modal fade" id="acctSetModal" tabindex="-1" aria-labelledby="acctSetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Account Settings</h3>
                </div>
                <div class="modal-body">
                    <a href='{{ route('instructor.edit-profile', auth()->id()) }}'>
                        <button class="btn btn-primary w-100 mb-3">✏️ Edit Profile</button>
                    </a>
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
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
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

    <script>
        const allStudents = @json($students);
        const allArchivedStudents = @json($arcstuds);

        const instRequestSelect = document.getElementById('inst_request');
        const studentSelect = document.getElementById('student');
        const reasonGroup = document.getElementById('reasonGroup');
        const reasonSelect = document.getElementById('reason');

        // Function to populate students organized by section
        function populateStudents(studentList) {
            studentSelect.innerHTML = '<option value="">-- Select Student --</option>';

            if(studentList.length === 0) {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = '-- No students available --';
                studentSelect.appendChild(option);
                return;
            }

            // Group students by section
            const studentsBySection = {};
            
            studentList.forEach(student => {
                if(student.section) {
                    const sectionName = student.section.name;
                    if(!studentsBySection[sectionName]) {
                        studentsBySection[sectionName] = [];
                    }
                    studentsBySection[sectionName].push(student);
                }
            });

            // Sort section names alphabetically
            const sortedSections = Object.keys(studentsBySection).sort();

            // Create optgroups for each section
            sortedSections.forEach(sectionName => {
                const optgroup = document.createElement('optgroup');
                optgroup.label = sectionName;

                // Sort students by last name, then first name within each section
                studentsBySection[sectionName].sort((a, b) => {
                    const lastNameCompare = a.lname.localeCompare(b.lname);
                    if(lastNameCompare !== 0) return lastNameCompare;
                    return a.fname.localeCompare(b.fname);
                });

                // Add students to this section's optgroup
                studentsBySection[sectionName].forEach(student => {
                    const option = document.createElement('option');
                    option.value = student.id;
                    option.textContent = `${student.lname}, ${student.fname}`;
                    optgroup.appendChild(option);
                });

                studentSelect.appendChild(optgroup);
            });
        }

        // Handle request type change
        instRequestSelect.addEventListener('change', function() {
            const requestType = this.value;
            
            if (requestType === 'Restore Student') {
                // Show archived students and hide reason field
                populateStudents(allArchivedStudents);
                reasonGroup.style.display = 'none';
                reasonSelect.removeAttribute('required');
                reasonSelect.value = '';
            } else if (requestType === 'Archive Student') {
                // Show active students and show reason field
                populateStudents(allStudents);
                reasonGroup.style.display = 'block';
                reasonSelect.setAttribute('required', 'required');
            } else {
                // Reset student dropdown
                studentSelect.innerHTML = '<option value="">-- Select Request Type First --</option>';
                reasonGroup.style.display = 'block';
                reasonSelect.setAttribute('required', 'required');
            }
        });
    </script>

    @endsection
</body>
</html>

