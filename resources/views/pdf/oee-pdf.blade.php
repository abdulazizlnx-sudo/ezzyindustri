<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>OEE Report</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
        }
        th { 
            background-color: #f2f2f2; 
        }
        .header { 
            margin-bottom: 20px; 
        }
        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            color: white;
            font-weight: bold;
        }
        .bg-danger { 
            background-color: #dc3545; 
            color: white;
        }
        .bg-warning { 
            background-color: #ffc107; 
            color: black;
        }
        .bg-success { 
            background-color: #28a745; 
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Overall Equipment Effectiveness (OEE)</h2>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
        @if($selectedShift)
            | Shift: {{ App\Models\Shift::find($selectedShift)->name ?? 'All Shifts' }}
        @endif
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Mesin</th>
                <th>Availability</th>
                <th>Performance</th>
                <th>Quality</th>
                <th>OEE Score</th>
            </tr>
        </thead>
        <tbody>
            @foreach($machines as $machine)
            <tr>
                <td>{{ $machine->name }}</td>
                @if($machine->oeeRecords->isNotEmpty())
                    @php
                        $latestRecord = $machine->oeeRecords->last();
                    @endphp
                    <td class="{{ $latestRecord->availability_rate < 60 ? 'bg-danger' : ($latestRecord->availability_rate < 85 ? 'bg-warning' : 'bg-success') }}">
                        {{ number_format($latestRecord->availability_rate, 2) }}%
                    </td>
                    <td class="{{ $latestRecord->performance_rate < 60 ? 'bg-danger' : ($latestRecord->performance_rate < 85 ? 'bg-warning' : 'bg-success') }}">
                        {{ number_format($latestRecord->performance_rate, 2) }}%
                    </td>
                    <td class="{{ $latestRecord->quality_rate < 60 ? 'bg-danger' : ($latestRecord->quality_rate < 85 ? 'bg-warning' : 'bg-success') }}">
                        {{ number_format($latestRecord->quality_rate, 2) }}%
                    </td>
                    <td class="{{ $latestRecord->oee_score < 60 ? 'bg-danger' : ($latestRecord->oee_score < 85 ? 'bg-warning' : 'bg-success') }}">
                        {{ number_format($latestRecord->oee_score, 2) }}%
                    </td>
                @else
                    <td colspan="4" style="text-align: center; color: #6c757d;">No OEE Data Available</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>