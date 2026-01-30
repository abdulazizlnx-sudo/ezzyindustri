<?php $__env->startPush('styles'); ?>
<link href="<?php echo e(asset('assets/css/custom/pages/dashboard.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<div class="dashboard">
    <!-- Dashboard Header with Period Filter -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold">Dashboard Karyawan</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item active">Overview</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="btn-group" role="group">
                    <button wire:click="setPeriod('today')" class="btn btn-<?php echo e($selectedPeriod === 'today' ? 'primary' : 'outline-primary'); ?>">
                        Hari Ini
                    </button>
                    <button wire:click="setPeriod('week')" class="btn btn-<?php echo e($selectedPeriod === 'week' ? 'primary' : 'outline-primary'); ?>">
                        Minggu Ini
                    </button>
                    <button wire:click="setPeriod('month')" class="btn btn-<?php echo e($selectedPeriod === 'month' ? 'primary' : 'outline-primary'); ?>">
                        Bulan Ini
                    </button>
                </div>
                <button wire:click="refreshDashboard" class="btn btn-info">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </button>
                <div class="date-display">
                    <i class="bi bi-calendar3"></i>
                    <span><?php echo e($startDate->format('d M Y')); ?> - <?php echo e($endDate->format('d M Y')); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Production Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Total Produksi</h6>
                    <h2 class="card-title mb-2"><?php echo e($todayProduction); ?></h2>
                    <p class="card-text text-muted">pcs</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Total Downtime</h6>
                    <h2 class="card-title mb-2"><?php echo e($totalDowntime); ?></h2>
                    <p class="card-text text-muted">menit</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Defect</h6>
                    <h2 class="card-title mb-2"><?php echo e($todayDefects); ?></h2>
                    <p class="card-text text-muted">pcs</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Quality Rate</h6>
                    <h2 class="card-title mb-2"><?php echo e(number_format($qualityRate, 1)); ?>%</h2>
                    <p class="card-text text-muted">tingkat kualitas</p>
                    <!-- Debug info -->
                    <small class="text-muted">Debug: <?php echo e($todayProduction); ?> prod, <?php echo e($todayDefects); ?> defects</small>
                </div>
            </div>
        </div>
    </div>

    <!-- OEE & Target Section -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">OEE Performance</h5>
                    <div class="mt-4">
                        <!-- Debug info -->
                        <small class="text-muted">Debug OEE: <?php echo e($oeeData ? 'Data exists' : 'No data'); ?></small>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Availability Rate</span>
                            <span class="fw-bold"><?php echo e(number_format($oeeData?->availability_rate ?? 0, 1)); ?>%</span>
                        </div>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-primary" style="width: <?php echo e($oeeData?->availability_rate ?? 0); ?>%"></div>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Performance Rate</span>
                            <span class="fw-bold"><?php echo e(number_format($oeeData?->performance_rate ?? 0, 1)); ?>%</span>
                        </div>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-success" style="width: <?php echo e($oeeData?->performance_rate ?? 0); ?>%"></div>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Quality Rate</span>
                            <span class="fw-bold"><?php echo e(number_format($oeeData?->quality_rate ?? 0, 1)); ?>%</span>
                        </div>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-info" style="width: <?php echo e($oeeData?->quality_rate ?? 0); ?>%"></div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Overall OEE</span>
                            <span class="fw-bold"><?php echo e(number_format($oeeData?->oee_score ?? 0, 1)); ?>%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Target vs Realisasi</h5>
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h3 class="mb-0"><?php echo e($productionRealization); ?></h3>
                                <small class="text-muted">dari target <?php echo e($productionTarget); ?></small>
                            </div>
                            <div class="progress" style="width: 70%; height: 20px;">
                                <?php
                                    $percentage = $productionTarget > 0 ? ($productionRealization / $productionTarget) * 100 : 0;
                                ?>
                                <div class="progress-bar <?php echo e($percentage >= 100 ? 'bg-success' : 'bg-warning'); ?>" 
                                     style="width: <?php echo e(min($percentage, 100)); ?>%">
                                    <?php echo e(number_format($percentage, 1)); ?>%
                                </div>
                            </div>
                        </div>
                        <!-- Tambahan informasi -->
                        <div class="text-end">
                            <!--[if BLOCK]><![endif]--><?php if($percentage == 100): ?>
                                <span class="badge bg-success">Target Tercapai</span>
                            <?php elseif($percentage > 100): ?>
                                <span class="badge bg-info">Melebihi Target</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Belum Mencapai Target</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Chart -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Trend Performa</h5>
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Existing Recent Activity Section -->
    <div class="row g-4">
        <!-- ... -->
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('livewire:initialized', () => {
    const performanceData = <?php echo json_encode($performanceData, 15, 512) ?>;
    
    const ctx = document.getElementById('performanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: performanceData.map(item => item.date),
            datasets: [
                {
                    label: 'OEE Score',
                    data: performanceData.map(item => item.oee_score),
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                },
                {
                    label: 'Availability',
                    data: performanceData.map(item => item.availability_rate),
                    borderColor: 'rgb(54, 162, 235)',
                    tension: 0.1
                },
                {
                    label: 'Performance',
                    data: performanceData.map(item => item.performance_rate),
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                },
                {
                    label: 'Quality',
                    data: performanceData.map(item => item.quality_rate),
                    borderColor: 'rgb(255, 205, 86)',
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
});
</script>
<?php $__env->stopPush(); ?><?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/karyawan/dashboard.blade.php ENDPATH**/ ?>