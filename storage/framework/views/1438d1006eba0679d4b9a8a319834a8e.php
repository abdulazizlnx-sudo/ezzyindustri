<div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Production Report Preview</h5>
            
            <div class="text-end mb-3">
                <button wire:click="downloadPdf" class="btn btn-primary">
                    <i class="bi bi-download"></i> Download PDF
                </button>
            </div>

            <div class="preview-content">
                <!-- Production Details -->
                <div class="mb-4">
                    <h6>Production Details</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th width="200">Machine</th>
                                <td>
                                    <!--[if BLOCK]><![endif]--><?php if(is_string($production->machine)): ?>
                                        <?php echo e($production->machine); ?>

                                    <?php else: ?>
                                        <?php echo e($production->machine->name ?? 'N/A'); ?>

                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>
                            </tr>
                            <tr>
                                <th>Product</th>
                                <td><?php echo e($production->product); ?></td> <!-- Changed from $production->product->name -->
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
                </div>

                <!-- Pre-Production Checksheet -->
                <div class="mb-4">
                    <h6>Pre-Production Checksheet</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Task Name</th>
                                    <th>Type</th>
                                    <th>Result</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $production->checksheetEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($entry->task->task_name); ?></td>
                                    <td><?php echo e(strtoupper($entry->task->maintenance_type)); ?></td>
                                    <td>
                                        <!--[if BLOCK]><![endif]--><?php if($entry->result == 'ok'): ?>
                                            <span class="text-success">OK</span>
                                        <?php elseif($entry->result == 'not_ok'): ?>
                                            <span class="text-danger">NOT OK</span>
                                        <?php else: ?>
                                            <span class="text-secondary">N/A</span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>
                                    <td><?php echo e($entry->notes ?? '-'); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data checksheet</td>
                                    </tr>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Production Problems -->
                <div class="mb-4">
                    <h6>Production Problems</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
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
                                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $production->problems ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $problem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e(ucfirst($problem->problem_type)); ?></td>
                                    <td><?php echo e(ucfirst($problem->status)); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($problem->reported_at)->format('Y-m-d H:i:s')); ?></td>
                                    <td><?php echo e($problem->resolved_at ? \Carbon\Carbon::parse($problem->resolved_at)->format('Y-m-d H:i:s') : '-'); ?></td>
                                    <td>
                                        <!--[if BLOCK]><![endif]--><?php if($problem->resolved_at && $problem->reported_at): ?>
                                            <?php echo e(\Carbon\Carbon::parse($problem->reported_at)->diffInMinutes(\Carbon\Carbon::parse($problem->resolved_at))); ?> minutes
                                        <?php elseif($problem->reported_at && $problem->status != 'resolved'): ?>
                                            <?php echo e(\Carbon\Carbon::parse($problem->reported_at)->diffInMinutes(now())); ?> minutes (Ongoing)
                                        <?php else: ?>
                                            -
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>
                                    <td><?php echo e($problem->notes); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada masalah produksi</td>
                                    </tr>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Production Downtimes -->
                <div class="mb-4">
                    <h6>Production Downtimes</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Reason</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Duration (minutes)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $production->productionDowntimes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $downtime): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($downtime->reason); ?></td>
                                    <td><?php echo e($downtime->start_time ? $downtime->start_time->format('Y-m-d H:i:s') : 'N/A'); ?></td>
                                    <td><?php echo e($downtime->end_time ? $downtime->end_time->format('Y-m-d H:i:s') : 'N/A'); ?></td>
                                    <td><?php echo e($downtime->duration_minutes ?? 'N/A'); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data downtime</td>
                                    </tr>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quality Checks -->
                <div class="mb-4">
                    <h6>Quality Check Results</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Parameter</th>
                                    <th>Measured Value</th>
                                    <th>Standard</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $production->qualityChecks ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $check): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $check->details ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($detail->parameter); ?></td>
                                        <td><?php echo e($detail->measured_value); ?></td>
                                        <td><?php echo e($detail->standard_value); ?> (<?php echo e($detail->tolerance_min); ?> - <?php echo e($detail->tolerance_max); ?>)</td>
                                        <td><?php echo e($detail->status); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data quality check</td>
                                    </tr>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/production/production-report.blade.php ENDPATH**/ ?>