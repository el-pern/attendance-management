@extends('Layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classes</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('SAS.png') }}">

    <!-- Bootstrap 5 -->
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
            max-width: 1000px;
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
        .search-sort-container {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            align-items: center;
        }
        .search-box {
            flex: 1;
            position: relative;
        }
        .search-box input {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #232528;
            font-size: 14px;
        }
        .search-box input:focus {
            outline: none;
            border-color: #ffc107;
        }
        .sort-box {
            min-width: 200px;
        }
        .sort-box select {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #232528;
            font-size: 14px;
            background: white;
            cursor: pointer;
        }
        .sort-box select:focus {
            outline: none;
            border-color: #ffc107;
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
        .btn-warning {
            background: #ffc107;
            border: 2px solid #ffc107;
            color: #232528;
        }
        .btn-warning:hover {
            background: white;
            color: #232528;
        }
        .class-card {
            border: 2px solid #232528;
            margin-bottom: 20px;
            background: white;
        }
        .class-header {
            background: #232528;
            color: white;
            padding: 15px 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .class-body {
            padding: 20px;
        }
        .adviser-section {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #232528;
        }
        .students-section {
            color: #232528;
        }
        .student-item {
            margin-left: 20px;
            margin-top: 5px;
        }
        .no-results {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="content-container">
            <div class="header-actions">
                <a href='/admin'><button class="btn btn-secondary">← Back</button></a>
                <form action="/admin/suspend" method="POST">
                @csrf
                <button type="button"
                class="btn btn-warning"
                data-bs-toggle="modal"
                data-bs-target="#suspendClassModal"
                >Suspend Classes</button>
                    
                <div class="modal fade" id="suspendClassModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-body">
                        Proceed to suspend all classes?
                        <br>Caution: This will suspend all classes within the day.
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Suspend</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                    </div>
                </div>
                </div>
                </form>
            </div>

            <h3>Classes</h3>

            <!-- Search and Sort Section -->
            <div class="search-sort-container">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search by section, adviser, or student name...">
                </div>
                <div class="sort-box">
                    <select id="sortSelect">
                        <option value="default">Sort by: Default</option>
                        <option value="section-asc">Section (A-Z)</option>
                        <option value="section-desc">Section (Z-A)</option>
                        <option value="adviser-asc">Adviser (A-Z)</option>
                        <option value="adviser-desc">Adviser (Z-A)</option>
                    </select>
                </div>
            </div>

            <div id="classesContainer">
                @foreach($sections as $section)
                    <div class="class-card" 
                         data-section="{{$section->grade->roman_numeral}} - {{$section->name}}"
                         data-adviser="@foreach($instructors as $instructor)@if($instructor->section->name == $section->name){{$instructor->lname}}, {{$instructor->fname}}@endif @endforeach"
                         data-students="@foreach($students as $student)@if($student->section->name == $section->name){{$student->lname}}, {{$student->fname}} @endif @endforeach">
                        <div class="class-header">{{$section->grade->roman_numeral}} - {{$section->name}}</div>
                        <div class="class-body">
                            <div class="adviser-section">
                                <strong>Adviser:</strong>
                                @foreach($instructors as $instructor)
                                    @if($instructor->section->name == $section->name)
                                        {{$instructor->fname}} {{$instructor->lname}}
                                    @endif
                                @endforeach
                            </div>
                            <div class="students-section">
                                <strong>Students:</strong>
                                @foreach($students as $student)
                                    @if($student->section->name == $section->name)
                                        <div class="student-item">• {{$student->lname}}, {{$student->fname}}</div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div id="noResults" class="no-results" style="display: none;">
                No classes found matching your search.
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+Y6jmDXq8M7eEd0W5VZF5sA5U5fK0"
    crossorigin="anonymous"></script>

    <script>
        const searchInput = document.getElementById('searchInput');
        const sortSelect = document.getElementById('sortSelect');
        const classesContainer = document.getElementById('classesContainer');
        const noResults = document.getElementById('noResults');
        let classCards = Array.from(document.querySelectorAll('.class-card'));

        // Search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            let visibleCount = 0;

            classCards.forEach(card => {
                const section = card.dataset.section.toLowerCase();
                const adviser = card.dataset.adviser.toLowerCase();
                const students = card.dataset.students.toLowerCase();

                if (section.includes(searchTerm) || adviser.includes(searchTerm) || students.includes(searchTerm)) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            noResults.style.display = visibleCount === 0 ? 'block' : 'none';
        });

        // Sort functionality
        sortSelect.addEventListener('change', function() {
            const sortType = this.value;

            classCards.sort((a, b) => {
                let compareA, compareB;

                switch(sortType) {
                    case 'section-asc':
                        compareA = a.dataset.section.toLowerCase();
                        compareB = b.dataset.section.toLowerCase();
                        return compareA.localeCompare(compareB);
                    
                    case 'section-desc':
                        compareA = a.dataset.section.toLowerCase();
                        compareB = b.dataset.section.toLowerCase();
                        return compareB.localeCompare(compareA);
                    
                    case 'adviser-asc':
                        compareA = a.dataset.adviser.toLowerCase();
                        compareB = b.dataset.adviser.toLowerCase();
                        return compareA.localeCompare(compareB);
                    
                    case 'adviser-desc':
                        compareA = a.dataset.adviser.toLowerCase();
                        compareB = b.dataset.adviser.toLowerCase();
                        return compareB.localeCompare(compareA);
                    
                    default:
                        return 0;
                }
            });

            // Re-append sorted cards
            classCards.forEach(card => {
                classesContainer.appendChild(card);
            });
        });
    </script>

    @endsection
</body>
</html>

