<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h4>Laporan Produksi</h4>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" wire:model.live="dateFrom">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" wire:model.live="dateTo">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Mesin</label>
                    <select class="form-select" wire:model.live="selectedMachine">
                        <option value="">Semua Mesin</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $machines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($machine->id); ?>"><?php echo e($machine->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Operator</label>
                    <select class="form-select" wire:model.live="selectedOperator">
                        <option value="">Semua Operator</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $operators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $operator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($operator->id); ?>"><?php echo e($operator->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Shift</label>
                    <select class="form-select" wire:model.live="selectedShift">
                        <option value="">Semua Shift</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($shift->id); ?>"><?php echo e($shift->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button wire:click="refreshData" class="btn btn-primary d-block">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Produksi vs Target</h5>
                    <div id="productionVsTargetChart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">OEE Score</h5>
                    <div id="oeeChart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card">
        <div class="card-body">
            <!-- Add Export Buttons -->
            <div class="mb-3">
                <button class="btn btn-primary me-2" wire:click="exportPDF">
                    <i class="bi bi-file-pdf"></i> Export PDF
                </button>
                <button class="btn btn-success" wire:click="exportExcel">
                    <i class="bi bi-file-excel"></i> Export Excel
                </button>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered">
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
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $productions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $production): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($production->start_time->format('d/m/Y')); ?></td>
                                <td><?php echo e($production->shift->name ?? 'N/A'); ?></td>
                                <td><?php echo e($production->user->name ?? 'N/A'); ?></td>
                                <td><?php echo e($production->machine); ?></td>
                                <td><?php echo e($production->product); ?></td>
                                <td><?php echo e($production->target_per_shift); ?></td>
                                <td><?php echo e($production->total_production); ?></td>
                                <td><?php echo e($production->defect_count); ?></td>
                                <td><?php echo e($production->oeeRecord->oee_score ?? 'N/A'); ?>%</td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
  
    <script>
        document.addEventListener('livewire:initialized', () => {
            const productionChart = new ApexCharts(document.querySelector("#productionVsTargetChart"), {
                series: <?php echo json_encode($chartData['production'], 15, 512) ?>,
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: <?php echo json_encode($chartData['dates'], 15, 512) ?>,
                },
                yaxis: {
                    title: {
                        text: 'Jumlah Produksi'
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " unit"
                        }
                    }
                }
            });
            productionChart.render();

            const oeeChart = new ApexCharts(document.querySelector("#oeeChart"), {
                series: <?php echo json_encode($chartData['oee'], 15, 512) ?>,
                chart: {
                    type: 'line',
                    height: 350
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    categories: <?php echo json_encode($chartData['dates'], 15, 512) ?>
                },
                yaxis: {
                    title: {
                        text: 'OEE Score (%)'
                    },
                    min: 0,
                    max: 100
                }
            });
            oeeChart.render();
        });

        // Update charts when data changes
        Livewire.on('updateCharts', (chartData) => {
            productionChart.updateOptions({
                series: chartData.production,
                xaxis: {
                    categories: chartData.dates
                }
            });
            oeeChart.updateOptions({
                series: chartData.oee,
                xaxis: {
                    categories: chartData.dates
                }
            });
        });
    </script>
     <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('openNewTab', ({ url }) => {
                window.open(url, '_blank');
            });
        });
    </script>
    <?php $__env->stopPush(); ?>
</div><?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/manajerial/production/production-report.blade.php ENDPATH**/ ?>