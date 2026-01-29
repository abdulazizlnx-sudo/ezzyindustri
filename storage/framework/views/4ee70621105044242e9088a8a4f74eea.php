<!DOCTYPE html>
<html>
<head>
    <title>Production Report</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header { 
            text-align: center;
            margin-bottom: 20px;
        }
        table { 
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td { 
            border: 1px solid #ddd;
            padding: 5px;
        }
        th { 
            background-color: #f4f4f4;
        }
        .section { 
            margin-bottom: 20px;
        }
        h3 {
            color: #333;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .text-success { color: green; }
        .text-danger { color: red; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Production Report</h2>
        <p>Date: <?php echo e(now()->format('d/m/Y')); ?></p>
    </div>

    <!-- Production Details -->
    <div class="section">
        <h3>Production Details</h3>
        <table>
            <tr>
                <th width="30%">Machine</th>
                <td><?php echo e($production->machine); ?></td>
            </tr>
            <tr>
                <th>Product</th>
                <td><?php echo e($production->product); ?></td>
            </tr>
            <tr>
                <th>Start Time</th>
                <td><?php echo e($production->start_time->format('Y-m-d H:i:s')); ?></td>
            </tr>
            <tr>
                <th>End Time</th>
                <td><?php echo e($production->end_time ? $production->end_time->format('Y-m-d H:i:s') : 'N/A'); ?></td>
            </tr>
            <tr>
                <th>Total Production</th>
                <td><?php echo e($production->total_production ?? 0); ?></td>
            </tr>
            <tr>
                <th>Defect Count</th>
                <td><?php echo e($production->defect_count ?? 0); ?></td>
            </tr>
        </table>
    </div>

    <!-- Pre-Production Checksheet -->
    <div class="section">
        <h3>Pre-Production Checksheet</h3>
        <table>
            <thead>
                <tr>
                    <th>Task Name</th>
                    <th>Type</th>
                    <th>Result</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $production->checksheetEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($entry->task->task_name); ?></td>
                    <td><?php echo e(strtoupper($entry->task->maintenance_type)); ?></td>
                    <td>
                        <?php if($entry->result == 'ok'): ?>
                            <span class="text-success">OK</span>
                        <?php elseif($entry->result == 'not_ok'): ?>
                            <span class="text-danger">NOT OK</span>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($entry->notes ?? '-'); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" style="text-align: center">Tidak ada data checksheet</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Production Problems -->
    <div class="section">
        <h3>Production Problems</h3>
        <table>
            <thead>
                <tr>
                    <th>Problem Type</th>
                    <th>Status</th>
                    <th>Reported At</th>
                    <th>Resolved At</th>
                    <th>Duration</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $production->problems ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $problem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e(ucfirst($problem->problem_type)); ?></td>
                    <td><?php echo e(ucfirst($problem->status)); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($problem->reported_at)->format('Y-m-d H:i:s')); ?></td>
                    <td><?php echo e($problem->resolved_at ? \Carbon\Carbon::parse($problem->resolved_at)->format('Y-m-d H:i:s') : '-'); ?></td>
                    <td>
                        <?php if($problem->resolved_at && $problem->reported_at): ?>
                            <?php
                                $start = \Carbon\Carbon::parse($problem->reported_at);
                                $end = \Carbon\Carbon::parse($problem->resolved_at);
                                $duration = number_format($start->diffInSeconds($end), 3);
                                $parts = explode('.', $duration);
                                $seconds = $parts[0];
                                $milliseconds = isset($parts[1]) ? $parts[1] : '000';
                            ?>
                            <?php echo e($seconds); ?> detik <?php echo e($milliseconds); ?> ms
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($problem->notes); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" style="text-align: center">Tidak ada masalah produksi</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Production Downtimes -->
    <div class="section">
        <h3>Production Downtimes</h3>
        <table>
            <thead>
                <tr>
                    <th>Reason</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $production->productionDowntimes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productionDowntime): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($productionDowntime->reason); ?></td>
                    <td><?php echo e($productionDowntime->start_time ? $productionDowntime->start_time->format('Y-m-d H:i:s') : 'N/A'); ?></td>
                    <td><?php echo e($productionDowntime->end_time ? $productionDowntime->end_time->format('Y-m-d H:i:s') : 'N/A'); ?></td>
                    <td>
                        <?php if($productionDowntime->duration_minutes): ?>
                            <?php
                                $duration = number_format($productionDowntime->duration_minutes, 3);
                                $parts = explode('.', $duration);
                                $seconds = $parts[0];
                                $milliseconds = isset($parts[1]) ? $parts[1] : '000';
                            ?>
                            <?php echo e($seconds); ?> detik <?php echo e($milliseconds); ?> ms
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" style="text-align: center">Tidak ada data downtime</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

        <!-- Quality Check Results -->
        <div class="section">
            <h3>Quality Check Results</h3>
            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Sample Size</th>
                        <th>Parameter</th>
                        <th>Value</th>
                        <th>Tolerance</th>
                        <th>Status</th>
                        <th>Operator</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $production->qualityChecks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $check): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $__currentLoopData = $check->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <?php if($loop->first): ?>
                                    <td rowspan="<?php echo e($check->details->count()); ?>">
                                        <?php echo e($check->check_time->format('Y-m-d H:i:s')); ?>

                                    </td>
                                    <td rowspan="<?php echo e($check->details->count()); ?>">
                                        <?php echo e($check->sample_size); ?>

                                    </td>
                                <?php endif; ?>
                                <td><?php echo e($detail->parameter); ?></td>
                                <td><?php echo e($detail->measured_value); ?></td>
                                <td>(<?php echo e($detail->tolerance_min); ?> - <?php echo e($detail->tolerance_max); ?>)</td>
                                <td class="<?php echo e($detail->status === 'ok' ? 'text-success' : 'text-danger'); ?>">
                                    <?php echo e(strtoupper($detail->status)); ?>

                                </td>
                                <?php if($loop->first): ?>
                                    <td rowspan="<?php echo e($check->details->count()); ?>">
                                        <?php echo e($check->user->name); ?>

                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" style="text-align: center">Tidak ada data quality check</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html><?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/production/production-report-pdf.blade.php ENDPATH**/ ?>