<!DOCTYPE html>
<html>
<head>
    <title>Fishbone Analysis Report</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0;
            padding: 0;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
        }
        .problem-statement { 
            background: #f5f5f5; 
            padding: 10px; 
            margin: 10px 0; 
        }
        .causes-section { margin: 15px 0; }
        .category { 
            background: #e9ecef;
            padding: 5px;
            margin: 8px 0;
        }
        .diagram-page {
            page-break-before: always;
            height: 100%;
            width: 100%;
            position: relative;
        }
        .diagram-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            text-align: center;
        }
        .diagram-image {
            max-width: 90%;
            max-height: 90vh;
            margin: 0 auto;
            display: block;
            object-fit: contain;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <!-- First page content -->
    <div class="header">
        <h2>{{ $analysis->title }}</h2>
        <p>Date: {{ $analysis->analysis_date }}</p>
    </div>

    <div class="problem-statement">
        <h3>Problem Statement:</h3>
        <p>{{ $analysis->problem_statement }}</p>
    </div>

    <div class="causes-section">
        @foreach($causes as $category => $categoryCauses)
            <div class="category">
                <h4>{{ $category }}</h4>
                <ul>
                    @foreach($categoryCauses as $cause)
                        <li>
                            <strong>{{ $cause->cause }}</strong><br>
                            Description: {{ $cause->description }}<br>
                            PIC: {{ $cause->pic }}<br>
                            Target Date: {{ $cause->target_date }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>

    <!-- Diagram page -->
    @if(isset($base64Image))
        <div class="diagram-page">
            <div class="diagram-container">
                <img src="data:image/png;base64,{{ $base64Image }}" 
                     class="diagram-image"
                     alt="Fishbone Diagram">
            </div>
        </div>
    @endif

    <!-- Footer page -->
    <div style="page-break-before: always;">
        <div class="footer">
            <p>Created by: {{ $analysis->created_by }}</p>
            @if($analysis->approved_by)
                <p>Approved by: {{ $analysis->approved_by }}</p>
            @endif
            <p>Status: {{ ucfirst($analysis->status) }}</p>
        </div>
    </div>
</body>
</html>