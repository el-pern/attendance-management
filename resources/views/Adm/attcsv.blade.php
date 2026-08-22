@extends('Layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Import/Export Attendance</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('SAS.png') }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous">
    <style>
        :root {
            --primary-dark: #232528;
            --primary-light: #2d3035;
            --border-radius: 12px;
            --spacing-sm: 10px;
            --spacing-md: 15px;
            --spacing-lg: 20px;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 30px 0;
        }
        
        .container-compact {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 var(--spacing-md);
        }
        
        .header-compact {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-light) 100%);
            color: white;
            padding: 20px 25px;
            border-radius: var(--border-radius);
            margin-bottom: var(--spacing-lg);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
            text-align: center;
        }
        
        .header-compact h3 {
            margin: 0;
            font-weight: 700;
            font-size: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .card-compact {
            background: white;
            border-radius: var(--border-radius);
            padding: var(--spacing-lg);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            margin-bottom: var(--spacing-lg);
            border: 1px solid rgba(35, 37, 40, 0.1);
        }
        
        .card-header-compact {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .card-header-compact h5 {
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0;
            font-size: 16px;
        }
        
        .form-group-compact {
            margin-bottom: 20px;
        }
        
        .form-label-compact {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 6px;
            display: block;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .input-compact {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            background: white;
            font-size: 14px;
            transition: all 0.2s ease;
            color: #232528;
            font-weight: 500;
        }
        
        .input-compact:focus {
            outline: none;
            border-color: var(--primary-dark);
            box-shadow: 0 0 0 3px rgba(35, 37, 40, 0.1);
        }
        
        .btn-compact {
            padding: 10px 18px;
            font-weight: 600;
            font-size: 13px;
            border-radius: 8px;
            border: 2px solid transparent;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 140px;
        }
        
        .btn-secondary-compact {
            background: white;
            border-color: var(--primary-dark);
            color: var(--primary-dark);
        }
        
        .btn-secondary-compact:hover {
            background: var(--primary-dark);
            color: white;
        }
        
        .btn-primary-compact {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            color: white;
        }
        
        .btn-primary-compact:hover {
            background: white;
            color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .btn-success-compact {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border-color: #28a745;
            color: white;
        }
        
        .btn-success-compact:hover {
            background: white;
            color: #28a745;
            border-color: #28a745;
        }
        
        .alert-compact {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: var(--spacing-md);
            border-left: 4px solid;
            font-size: 14px;
        }
        
        .alert-info-compact {
            background: #eef6ff;
            border-left-color: #0d6efd;
            color: #0d4ab2;
        }
        
        .alert-success-compact {
            background: #d4edda;
            border-left-color: #28a745;
            color: #155724;
        }
        
        .alert-danger-compact {
            background: #f8d7da;
            border-left-color: #dc3545;
            color: #721c24;
        }
        
        .form-text-compact {
            font-size: 12px;
            color: #666;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .icon {
            font-size: 18px;
        }
        
        .section-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-md);
        }
        
        @media (max-width: 768px) {
            .section-grid {
                grid-template-columns: 1fr;
            }
            
            .container-compact {
                padding: 0 var(--spacing-sm);
            }
            
            .header-compact {
                padding: 16px 20px;
            }
            
            .header-compact h3 {
                font-size: 20px;
            }
        }
        
        .back-button {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: var(--spacing-lg);
        }
        
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 15px;
        }
        
        .help-text {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 12px;
            margin-top: 12px;
            font-size: 13px;
            color: #555;
            border-left: 3px solid var(--primary-dark);
        }
        
        .help-text h6 {
            font-weight: 600;
            margin-bottom: 6px;
            font-size: 13px;
        }
        
        .help-text p {
            margin: 0;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container-compact">
        <div class="back-button">
            <a href="/admin" class="btn btn-secondary-compact">
                <span class="icon">←</span>
                Back
            </a>
        </div>

        <div class="header-compact">
            <h3>
                <span class="icon">📊</span>
                Import/Export Attendance
            </h3>
        </div>

        <!-- URL Input Section -->
        <div class="card-compact">
            <div class="form-group-compact">
                <label class="form-label-compact" for="sheets_url">
                    Google Sheets URL
                </label>
                <input type="url" 
                       class="input-compact"
                       id="sheets_url" 
                       placeholder="Enter your Google Sheets URL" 
                       required>
                <div class="form-text-compact">
                    <span class="icon">💡</span>
                    <span>For Import: Use published CSV link. For Export: Use web app URL.</span>
                </div>
            </div>
        </div>

        <!-- Status Messages -->
        @if(session('success'))
            <div class="alert-compact alert-success-compact">
                <strong>✓ Success!</strong> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-compact alert-danger-compact">
                <strong>✗ Error!</strong> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-compact alert-danger-compact">
                <strong>Validation Errors:</strong>
                <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Import/Export Sections Grid -->
        <div class="section-grid">
            <!-- Import Section -->
            <div class="card-compact">
                <div class="card-header-compact">
                    <span class="icon">📥</span>
                    <h5>Import from Google Sheets</h5>
                </div>
                
                <div class="help-text">
                    <h6>How to get CSV link:</h6>
                    <p>
                        1. Go to File → Share → Publish to Web<br>
                        2. Select sheet & .csv format<br>
                        3. Click 'Publish' and copy link
                    </p>
                </div>
                
                <form action="/import" method="POST" id="importForm">
                    @csrf
                    <input type="hidden" name="csv_url" id="import_url" required>
                    <div class="form-actions">
                        <button type="button" class="btn-compact btn-primary-compact" onclick="handleImport()">
                            <span class="icon">📥</span>
                            Import Data
                        </button>
                    </div>
                </form>
            </div>

            <!-- Export Section -->
            <div class="card-compact">
                <div class="card-header-compact">
                    <span class="icon">📤</span>
                    <h5>Export to Google Sheets</h5>
                </div>
                
                <div class="help-text">
                    <h6>Setup Instructions:</h6>
                    <p>
                        1. Open Google Sheet<br>
                        2. Extensions → Apps Script<br>
                        3. Paste script & deploy as Web App<br>
                        4. Copy Web App URL (not sheet URL)
                    </p>
                </div>
                
                <form action="/export" method="POST" id="exportForm">
                    @csrf
                    <input type="hidden" name="spreadsheet_url" id="export_url">
                    <div class="form-actions">
                        <button type="button" class="btn-compact btn-success-compact" onclick="handleExport()">
                            <span class="icon">📤</span>
                            Export Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function handleImport() {
            const urlInput = document.getElementById('sheets_url');
            const url = urlInput.value.trim();
            
            if (!url || !urlInput.checkValidity()) {
                urlInput.reportValidity();
                urlInput.focus();
                return;
            }
            
            document.getElementById('import_url').value = url;
            document.getElementById('importForm').submit();
        }

        function handleExport() {
            const urlInput = document.getElementById('sheets_url');
            const url = urlInput.value.trim();
            
            if (!url || !urlInput.checkValidity()) {
                urlInput.reportValidity();
                urlInput.focus();
                return;
            }
            
            document.getElementById('export_url').value = url;
            document.getElementById('exportForm').submit();
        }
        
        // Auto-select URL when focusing on input
        document.getElementById('sheets_url').addEventListener('focus', function() {
            this.select();
        });
    </script>

</body>
</html>

@endsection

