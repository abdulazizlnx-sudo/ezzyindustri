<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>EzzyIndustri - TPM System</title>

    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">


    <!-- Vendor CSS Files -->
    <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="/assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>



       <!-- Template Main CSS File -->
       <link href="/assets/css/style.css" rel="stylesheet">
    <!-- Custom Theme CSS -->
    <link href="/assets/css/custom/theme.css" rel="stylesheet">
    <!-- Custom Pages CSS -->
    <link href="<?php echo e(asset('assets/css/custom/pages/dashboard.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/css/custom/pages/problem-approval.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/css/custom/pages/start-production.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/css/custom/pages/machine-sop-viewer.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/css/custom/pages/checksheet-table.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/css/custom/pages/quality-check.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/css/custom/pages/oee-detail.css')); ?>" rel="stylesheet">
    
    <!-- Di bagian head, sebelum closing </head> -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>

</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <a href="/" class="logo d-flex align-items-center">
                <i class="bi bi-building-gear fs-4 me-2"></i>
                <span class="d-none d-lg-block">EzzyIndustri</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div>

        <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
        <li class="nav-item user-nav">
            <div class="user-info">
                <span class="user-name"><?php echo e(auth()->user()->name); ?></span>
                <small class="user-role"><?php echo e(ucfirst(auth()->user()->getRoleNames()->first())); ?></small>
            </div>
            <form method="POST" action="<?php echo e(route('logout')); ?>" class="logout-form">
                <?php echo csrf_field(); ?>
                <button type="submit" class="logout-button">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>
        </li>
    </ul>
</nav>
    </header>

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">
            <?php if(auth()->user()->hasRole('manajerial')): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('manajerial.dashboard') ? '' : 'collapsed'); ?>" 
                       href="<?php echo e(route('manajerial.dashboard')); ?>" wire:navigate>
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('manajerial.production.problems') ? '' : 'collapsed'); ?>" 
                       href="<?php echo e(route('manajerial.production.problems')); ?>" wire:navigate>
                        <i class="bi bi-exclamation-triangle"></i>
                        <span>Problem Approval</span>
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('components.problem-count', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1340483505-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('manajerial.sop') ? '' : 'collapsed'); ?>" 
                       href="<?php echo e(route('manajerial.sop')); ?>" wire:navigate>
                        <i class="bi bi-journal-text"></i>
                        <span>Master SOP</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('manajerial.karyawan-report') ? '' : 'collapsed'); ?>" 
                       href="<?php echo e(route('manajerial.karyawan-report')); ?>" wire:navigate>
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Laporan Karyawan</span>
                    </a>
                </li>
                
                <!-- Tambahkan menu Laporan Produksi -->
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('manajerial.production.report') ? '' : 'collapsed'); ?>" 
                       href="<?php echo e(route('manajerial.production.report')); ?>" wire:navigate>
                        <i class="bi bi-graph-up"></i>
                        <span>Laporan Produksi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <!-- If there's a sidebar link to OEE Dashboard, update it -->
                    <a class="nav-link <?php echo e(request()->routeIs('manajerial.oee.dashboard') ? '' : 'collapsed'); ?>" 
                       href="<?php echo e(route('manajerial.oee.dashboard')); ?>" wire:navigate>
                        <i class="bi bi-speedometer2"></i>
                        <span>OEE Dashboard</span>
                    </a>
                </li>

                <!-- Di dalam sidebar menu manajerial, tambahkan setelah OEE Dashboard -->
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('manajerial.quality.*') ? '' : 'collapsed'); ?>" 
                       data-bs-target="#qualitySubmenu" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-clipboard-data"></i>
                        <span>Quality Analysis</span>
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <!-- Di dalam sidebar, pada bagian Quality Analysis submenu -->
                    <ul id="qualitySubmenu" class="nav-content collapse <?php echo e(request()->routeIs('manajerial.quality.*') ? 'show' : ''); ?>">
                        <li>
                            <a href="<?php echo e(route('manajerial.quality.analysis')); ?>" wire:navigate 
                               class="<?php echo e(request()->routeIs('manajerial.quality.analysis') ? 'active' : ''); ?>">
                                <i class="bi bi-graph-up"></i><span>Dashboard</span>
                            </a>
                        </li>
                        <!-- Tambahkan menu NG Report di sini -->
                        <li>
                            <a href="<?php echo e(route('manajerial.quality.ng-report')); ?>" wire:navigate
                               class="<?php echo e(request()->routeIs('manajerial.quality.ng-report') ? 'active' : ''); ?>">
                                <i class="bi bi-clipboard-x"></i><span>NG Report</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('manajerial.quality.pareto')); ?>" wire:navigate
                               class="<?php echo e(request()->routeIs('manajerial.quality.pareto') ? 'active' : ''); ?>">
                                <i class="bi bi-bar-chart"></i><span>Pareto Analysis</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('manajerial.quality.fishbone')); ?>" wire:navigate
                               class="<?php echo e(request()->routeIs('manajerial.quality.fishbone') ? 'active' : ''); ?>">
                                <i class="bi bi-diagram-3"></i><span>Fishbone Analysis</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('manajerial.quality.pdca')); ?>" wire:navigate
                               class="<?php echo e(request()->routeIs('manajerial.quality.pdca') ? 'active' : ''); ?>">
                                <i class="bi bi-arrow-repeat"></i><span>PDCA Management</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('manajerial.sop.approval') ? '' : 'collapsed'); ?>" 
                    href="<?php echo e(route('manajerial.sop.approval')); ?>" wire:navigate>
                        <i class="bi bi-clipboard-check"></i>
                        <span>SOP Approval</span>
                        <?php if($pendingCount ?? 0 > 0): ?>
                            <span class="badge bg-danger rounded-pill ms-2"><?php echo e($pendingCount); ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('manajerial.machines.*', 'manajerial.shifts.*', 'manajerial.tasks.*') ? '' : 'collapsed'); ?>" 
                       data-bs-target="#manajemenSubmenu" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-gear"></i>
                        <span>Manajemen</span>
                        <i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="manajemenSubmenu" class="nav-content collapse <?php echo e(request()->routeIs('manajerial.machines.*', 'manajerial.shifts.*', 'manajerial.tasks.*', 'manajerial.maintenance-tasks') ? 'show' : ''); ?>">
                        <li>
                            <a href="<?php echo e(route('manajerial.machines')); ?>" wire:navigate 
                               class="<?php echo e(request()->routeIs('manajerial.machines.*') ? 'active' : ''); ?>">
                                <i class="bi bi-tools"></i><span>Mesin</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('manajerial.products')); ?>" wire:navigate
                               class="<?php echo e(request()->routeIs('manajerial.products') ? 'active' : ''); ?>">
                                <i class="bi bi-box"></i><span>Produk</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('manajerial.shifts')); ?>" wire:navigate
                               class="<?php echo e(request()->routeIs('manajerial.shifts.*') ? 'active' : ''); ?>">
                                <i class="bi bi-clock"></i><span>Shift</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('manajerial.maintenance-tasks')); ?>" wire:navigate
                               class="<?php echo e(request()->routeIs('manajerial.maintenance-tasks') ? 'active' : ''); ?>">
                                <i class="bi bi-list-task"></i><span>Maintenance Tasks</span>
                            </a>
                        </li>
                        </li>
                        <li>
                            <a href="<?php echo e(route('manajerial.users')); ?>" wire:navigate
                               class="<?php echo e(request()->routeIs('manajerial.users') ? 'active' : ''); ?>">
                                <i class="bi bi-people"></i><span>Users</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
            <?php endif; ?>

            <?php if(auth()->user()->hasRole('karyawan')): ?>
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('karyawan.dashboard') ? '' : 'collapsed'); ?>" 
           href="<?php echo e(route('karyawan.dashboard')); ?>" wire:navigate>
            <i class="bi bi-grid"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('production.*') ? '' : 'collapsed'); ?>" 
           href="<?php echo e(route('production.start')); ?>" wire:navigate>
            <i class="bi bi-clipboard-check"></i>
            <span>Produksi</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('karyawan.history') ? '' : 'collapsed'); ?>" 
           href="<?php echo e(route('karyawan.history')); ?>" wire:navigate>
            <i class="bi bi-clock-history"></i>
            <span>History Produksi</span>
        </a>
    </li>
    <?php endif; ?>

    </ul>
    </aside>

    <main id="main" class="main">
        <?php echo e($slot); ?>

    </main>

    <!-- Keep the head section until footer -->

    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; <?php echo e(date('Y')); ?> <strong><span>EzzyIndustri</span></strong>. All Rights Reserved
        </div>
    </footer>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Consolidated Scripts Section -->
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    
    <!-- Essential Libraries -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    
    <!-- Toast Configuration -->
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    </script>

    <!-- Event Listeners -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            // SweetAlert Confirmation
            Livewire.on('swal-confirm', (data) => {
                Swal.fire({
                    title: data.title || 'Konfirmasi',
                    text: data.text || 'Apakah Anda yakin?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: data.confirmText || 'Ya, lanjutkan',
                    cancelButtonText: data.cancelText || 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('showNGForm');
                    }
                });
            });

            // Problem Modal Handlers
            Livewire.on('show-problem-modal', () => {
                const modal = document.getElementById('reportProblemModal');
                const bootstrapModal = new bootstrap.Modal(modal);
                bootstrapModal.show();
            });

            Livewire.on('hide-problem-modal', () => {
                const modal = document.getElementById('reportProblemModal');
                const bootstrapModal = bootstrap.Modal.getInstance(modal);
                if (bootstrapModal) {
                    bootstrapModal.hide();
                }
            });
        });
    </script>

    <!-- Vendor Libraries -->
    <script src="/assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="/assets/vendor/chart.js/chart.umd.js"></script>
    <script src="/assets/vendor/echarts/echarts.min.js"></script>
    <script src="/assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="/assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <!-- Custom Scripts -->
    <script src="/assets/js/main.js"></script>
    <script src="/assets/js/sweet-alert-handlers.js"></script>

    <!-- Report Problem Component -->
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('karyawan.production.report-problem', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1340483505-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/components/layouts/app.blade.php ENDPATH**/ ?>