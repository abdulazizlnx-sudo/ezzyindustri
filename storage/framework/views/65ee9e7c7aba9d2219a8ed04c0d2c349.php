<div wire:poll.<?php echo e($refreshInterval); ?>ms>
    <div class="pagetitle">
        <h1>Detail OEE <?php echo e($machine->name); ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('manajerial.dashboard')); ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(route('manajerial.oee.dashboard')); ?>">OEE Dashboard</a></li>
                <li class="breadcrumb-item active">Detail OEE</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-8">
                    <a href="<?php echo e(route('manajerial.oee.detail.pdf', ['machineId' => $machine->id])); ?>" 
                       class="btn btn-danger mt-2" 
                       target="_blank">
                        <i class="bi bi-file-pdf"></i> Download PDF
                    </a>
                </div>
                <div class="col-md-4 text-end">
                    <div class="alert alert-info p-2 mt-2">
                        <small><i class="bi bi-clock"></i> Terakhir diperbarui: <?php echo e($lastUpdated); ?></small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Availability Rate</h5>
                            <div class="metric-value <?php echo e($averageAvailability < 90 ? 'text-danger' : 'text-success'); ?>">
                                <?php echo e(number_format($averageAvailability, 2)); ?>%
                            </div>
                            <small class="text-muted">Target: 90%</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Performance Rate</h5>
                            <div class="metric-value <?php echo e($averagePerformance < 95 ? 'text-danger' : 'text-success'); ?>">
                                <?php echo e(number_format($averagePerformance, 2)); ?>%
                            </div>
                            <small class="text-muted">Target: 95%</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Quality Rate</h5>
                            <div class="metric-value <?php echo e($averageQuality < 99.9 ? 'text-danger' : 'text-success'); ?>">
                                <?php echo e(number_format($averageQuality, 2)); ?>%
                            </div>
                            <small class="text-muted">Target: 99.9%</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">OEE Score</h5>
                            <div class="metric-value <?php echo e($oeeScore < 85 ? 'text-danger' : 'text-success'); ?>">
                                <?php echo e(number_format($oeeScore, 2)); ?>%
                            </div>
                            <small class="text-muted">Target: 85%</small>
                        </div>
                    </div>
                </div>
            </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">OEE Trend</h5>
                                <div id="oeeChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        window.initialChartData = <?php echo \Illuminate\Support\Js::from($chartData)->toHtml() ?>;
    </script>
    <script src="<?php echo e(asset('assets/js/oee-chart.js')); ?>"></script>
    <?php $__env->stopPush(); ?>
</div>
<?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/manajerial/oee-detail.blade.php ENDPATH**/ ?>