<div>
    <div class="pagetitle">
        <h1>Selesaikan Produksi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('karyawan.dashboard')); ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(route('production.status')); ?>">Status Produksi</a></li>
                <li class="breadcrumb-item active">Selesaikan Produksi</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('message')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <?php if(session()->has('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form Penyelesaian Produksi</h5>
                        
                        <form wire:submit.prevent="finish">
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Total Produksi</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" wire:model.live="totalProduction" min="1" required>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['totalProduction'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Total Reject</label>
                                <div class="col-sm-9">
                                    <input type="number" 
                                           class="form-control" 
                                           wire:model="totalReject"
                                           min="<?php echo e($totalQualityNG); ?>"
                                           required>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['totalReject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                                        <span class="text-danger"><?php echo e($message); ?></span> 
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    <small class="text-muted">
                                        Minimal total reject: <?php echo e($totalQualityNG); ?> (dari Quality Check)
                                    </small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Catatan</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" wire:model="notes" rows="3"></textarea>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-9 offset-sm-3">
                                    <div class="text-end mt-3">
                                        <button type="submit" class="btn btn-primary" wire:click="$dispatch('openFinishModal')">
                                            Selesaikan Produksi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Modal Konfirmasi Download -->
    <div class="modal fade" id="finishModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Produksi Selesai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Produksi telah berhasil diselesaikan.</p>
                    <p>Apakah Anda ingin mengunduh laporan produksi?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="<?php echo e(route('production.report', ['productionId' => $production->id])); ?>" 
                       class="btn btn-primary" target="_blank">
                        Download Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>


    <!-- Tambahkan ini sebelum penutup div terakhir -->
    <?php echo $__env->make('livewire.karyawan.production.partials.ng-report-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script>
        let finishModal;
        let ngReportModal;
        
        document.addEventListener('DOMContentLoaded', () => {
            finishModal = new bootstrap.Modal(document.getElementById('finishModal'));
            ngReportModal = new bootstrap.Modal(document.getElementById('ngReportModal'));
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-ng-report-modal', () => {
                ngReportModal.show();
            });

            Livewire.on('hide-ng-report-modal', () => {
                ngReportModal.hide();
            });

            Livewire.on('finish-success', () => {
                if (finishModal) {
                    finishModal.show();
                }
            });
        });
    </script>
</div><?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/karyawan/production/finish-production.blade.php ENDPATH**/ ?>