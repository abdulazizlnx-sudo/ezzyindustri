<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Produksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">PT. EZZY INDUSTRI</div>
        <div class="report-title">LAPORAN PRODUKSI</div>
    </div>

    <div class="info-section">
        <table>
            <tr>
                <td width="15%"><strong>Periode</strong></td>
                <td width="35%">: {{ date('d/m/Y', strtotime($dateFrom)) }} - {{ date('d/m/Y', strtotime($dateTo)) }}</td>
                <td width="15%"><strong>Dicetak pada</strong></td>
                <td width="35%">: {{ now()->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td><strong>Dicetak oleh</strong></td>
                <td colspan="3">: {{ $printedBy }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Shift</th>
                <th>Operator</th>
                <th>Mesin</th>
                <th>Produk</th>
                <th>Target</th>
                <th>Hasil</th>
                <th>Defect</th>
                <th>OEE Score</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productions as $production)
            <tr>
                <td class="text-center">{{ $production->start_time->format('d/m/Y') }}</td>
                <td class="text-center">{{ $production->shift->name ?? 'N/A' }}</td>
                <td>{{ $production->user->name ?? 'N/A' }}</td>
                <td>{{ $production->machine }}</td>
                <td>{{ $production->product }}</td>
                <td class="text-center">{{ $production->target_per_shift }}</td>
                <td class="text-center">{{ $production->total_production }}</td>
                <td class="text-center">{{ $production->defect_count }}</td>
                <td class="text-center">{{ $production->oeeRecord->oee_score ?? 'N/A' }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak dari Sistem EzzyIndustri pada {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>