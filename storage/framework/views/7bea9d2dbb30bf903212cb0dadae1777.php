<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Kinerja Karyawan</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1.5cm;
        }
        body { 
            font-family: sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header-section {
            text-align: center;
            border-bottom: 2px solid #000;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
        }
        th {
            background-color: #f0f0f0;
        }
        .achievement {
            color: #059669;
            font-weight: bold;
        }
        .warning {
            color: #d97706;
            font-weight: bold;
        }
        .failure {
            color: #dc2626;
            font-weight: bold;
        }
        .problem-reject {
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header-section">
        <div class="company-name">PT. EZZY INDUSTRI</div>
        <small>Kantor Cabang Ampenan</small>
        <h2>Laporan Kinerja Karyawan</h2>
        <p>Periode: <?php echo e(\Carbon\Carbon::parse($dateFrom)->format('d M Y')); ?> - <?php echo e(\Carbon\Carbon::parse($dateTo)->format('d M Y')); ?></p>
    </div>

    <!-- Sisanya tetap sama -->
    <?php
        $metrics = [
            'qc' => $karyawan->getQualityMetrics($dateFrom, $dateTo),
            'maintenance' => $karyawan->getMaintenanceMetrics($dateFrom, $dateTo),
            'production' => $karyawan->getProductionMetrics($dateFrom, $dateTo)
        ];
        
        $performanceRate = round(($metrics['qc']['compliance_rate'] + 
                               $metrics['maintenance']['compliance_rate'] + 
                               $metrics['production']['achievement_rate']) / 3, 1);

        // Add this daily records calculation
        $dailyRecords = $karyawan->productions()
            ->whereBetween('start_time', [
                $dateFrom . ' 00:00:00',
                $dateTo . ' 23:59:59'
            ])
            ->get()
            ->groupBy(function($production) {
                return $production->start_time->format('Y-m-d');
            });
    ?>

    <table class="summary-table">
        <tr>
            <th>Nama</th>
            <td><?php echo e($karyawan->name); ?></td>
            <th>Departemen</th>
            <td><?php echo e($karyawan->department->name); ?></td>
            <th>Performance Rate</th>
            <td class="<?php echo e($performanceRate >= 90 ? 'achievement' : ($performanceRate >= 60 ? 'warning' : 'failure')); ?>">
                <?php echo e($performanceRate); ?>%
            </td>
        </tr>
    </table>

    <!-- Daily Performance Table -->
    <table class="daily-records">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Produksi</th>
                <th>Quality Check</th>
                <th>Maintenance</th>
                <th width="40%">Masalah & Reject</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $dailyRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $productions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e(\Carbon\Carbon::parse($date)->format('d M Y')); ?></td>
                <td>
                    <?php
                        $productionMetrics = $karyawan->getProductionMetrics($date, $date);
                    ?>
                    <span class="<?php echo e($productionMetrics['achievement_rate'] == 100 ? 'achievement' : 'failure'); ?>">
                        <?php echo e($productionMetrics['achievement_rate']); ?>%
                        <?php if($productionMetrics['achievement_rate'] != 100): ?>
                            <br>Target: <?php echo e($productions->sum('target_per_shift')); ?>

                            <br>Actual: <?php echo e($productions->sum('total_production')); ?>

                        <?php endif; ?>
                    </span>
                </td>
                <td>
                    <?php
                        $qcMetrics = $karyawan->getQualityMetrics($date, $date);
                    ?>
                    <span class="<?php echo e($qcMetrics['compliance_rate'] == 100 ? 'achievement' : 'failure'); ?>">
                        <?php echo e($qcMetrics['completed']); ?>/<?php echo e($qcMetrics['required']); ?>

                        (<?php echo e($qcMetrics['compliance_rate']); ?>%)
                    </span>
                </td>
                <td>
                    <?php
                        $maintenanceMetrics = $karyawan->getMaintenanceMetrics($date, $date);
                    ?>
                    <span class="<?php echo e($maintenanceMetrics['compliance_rate'] == 100 ? 'achievement' : 'failure'); ?>">
                        AM: <?php echo e($maintenanceMetrics['completed_am']); ?>

                        PM: <?php echo e($maintenanceMetrics['completed_pm']); ?>

                        (<?php echo e($maintenanceMetrics['compliance_rate']); ?>%)
                    </span>
                </td>
                <td class="problem-reject">
                    <?php
                        $problems = $productions->flatMap->problems;
                        $rejects = $karyawan->getRejects($date, $date);
                    ?>
                    <?php $__currentLoopData = $problems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $problem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>
                            <strong>PROBLEM [<?php echo e($problem->created_at->format('H:i')); ?>]:</strong>
                            Status: <?php echo e(ucfirst($problem->status)); ?> | 
                            Durasi: <?php echo e($problem->resolved_at ? $problem->created_at->diffForHumans($problem->resolved_at, true) : 'Ongoing'); ?><br>
                            <?php echo e($problem->description); ?>

                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $rejects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>
                            <strong>REJECT [<?php echo e($reject->check_time->format('H:i')); ?>]:</strong>
                            <?php echo e($reject->defect_count); ?> <?php echo e($reject->defect_type); ?> | <?php echo e($reject->defect_notes); ?>

                            <?php $__currentLoopData = $reject->details()->where('status', 'ng')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <br>• <?php echo e($detail->parameter); ?>: <?php echo e($detail->measured_value); ?> 
                                (<?php echo e($detail->tolerance_min); ?> - <?php echo e($detail->tolerance_max); ?>)
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html><?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/pdf/karyawan-detail.blade.php ENDPATH**/ ?>