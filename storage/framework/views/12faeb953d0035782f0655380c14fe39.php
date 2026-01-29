<div>
    <div class="pagetitle">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Detail SOP: <?php echo e($sop->nama); ?></h1>
            <div>
                <!--[if BLOCK]><![endif]--><?php if($sop->approval_status === 'approved'): ?>
                    <button type="button" class="btn btn-<?php echo e($sop->is_active ? 'danger' : 'success'); ?> me-2" 
                            wire:click="toggleActive"
                            wire:confirm="<?php echo e($sop->is_active ? 'Nonaktifkan SOP ini?' : 'Aktifkan kembali SOP ini?'); ?>">
                        <i class="bi bi-<?php echo e($sop->is_active ? 'toggle-off' : 'toggle-on'); ?>"></i>
                        <?php echo e($sop->is_active ? 'Nonaktifkan SOP' : 'Aktifkan SOP'); ?>

                    </button>
                    <a href="<?php echo e(route('manajerial.sop.pdf', $sop->id)); ?>" class="btn btn-info me-2" target="_blank">
                        <i class="bi bi-file-pdf"></i> Cetak PDF
                    </a>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <a href="<?php echo e(route('manajerial.sop')); ?>" class="btn btn-secondary" wire:navigate>
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('manajerial.dashboard')); ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(route('manajerial.sop')); ?>">Master SOP</a></li>
                <li class="breadcrumb-item active">Detail SOP</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <!-- Info Panel -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">NO. SOP</th>
                                        <td><?php echo e($sop->no_sop ?: '-'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>NAMA SOP</th>
                                        <td><?php echo e($sop->nama); ?></td>
                                    </tr>
                                    <tr>
                                        <th>KATEGORI</th>
                                        <td><?php echo e(ucfirst($sop->kategori)); ?></td>
                                    </tr>
                                    <tr>
                                        <th>VERSI</th>
                                        <td><?php echo e($sop->versi); ?></td>
                                    </tr>
                                    <!--[if BLOCK]><![endif]--><?php if($sop->kategori === 'produksi' || $sop->kategori === 'safety'): ?>
                                    <tr>
                                        <th>MESIN</th>
                                        <td><?php echo e($sop->machine->name ?? '-'); ?></td>
                                    </tr>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    <!--[if BLOCK]><![endif]--><?php if($sop->kategori === 'quality'): ?>
                                    <tr>
                                        <th>PRODUK</th>
                                        <td><?php echo e($sop->product->name ?? '-'); ?></td>
                                    </tr>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <th width="40%">STATUS</th>
                                        <td>
                                            <span class="badge bg-<?php echo e($sop->approval_status === 'approved' ? 'success' : 
                                                ($sop->approval_status === 'pending' ? 'warning' : 
                                                ($sop->approval_status === 'rejected' ? 'danger' : 'secondary'))); ?>">
                                                <?php echo e(ucfirst($sop->approval_status)); ?>

                                            </span>
                                            <!--[if BLOCK]><![endif]--><?php if($sop->approval_status === 'approved'): ?>
                                                <span class="badge bg-<?php echo e($sop->is_active ? 'success' : 'danger'); ?> ms-2">
                                                    <?php echo e($sop->is_active ? 'Active' : 'Inactive'); ?>

                                                </span>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>DIBUAT OLEH</th>
                                        <td><?php echo e($sop->creator->name ?? '-'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>DIBUAT PADA</th>
                                        <td><?php echo e($sop->created_date ? $sop->created_date->format('d/m/Y H:i') : '-'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>DISETUJUI OLEH</th>
                                        <td><?php echo e($sop->approver->name ?? '-'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>DISETUJUI PADA</th>
                                        <td><?php echo e($sop->approved_at ? $sop->approved_at->format('d/m/Y H:i') : '-'); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!--[if BLOCK]><![endif]--><?php if($sop->approval_status === 'draft'): ?>
                            <div class="text-end mt-3">
                                <button type="button" class="btn btn-primary" wire:click="submitForApproval">
                                    <i class="bi bi-send"></i> Submit untuk Persetujuan
                                </button>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            </div>
                        <!-- Parameter/Steps Table -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title mb-0">
                                            <!--[if BLOCK]><![endif]--><?php if($sop->kategori === 'quality'): ?>
                                                Quality Check Parameters
                                            <?php elseif($sop->kategori === 'produksi'): ?>
                                                Production Steps
                                            <?php else: ?>
                                                Safety Check Steps
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </h5>
                                        <button type="button" class="btn btn-primary" wire:click="openModal">
                                            <i class="bi bi-plus"></i> Add <?php echo e($sop->kategori === 'quality' ? 'Parameter' : 'Step'); ?>

                                        </button>
                                    </div>
                                    
                                    <!--[if BLOCK]><![endif]--><?php if($sop->kategori === 'quality'): ?>
                                        <?php echo $__env->make('livewire.manajerial.sop.partials._quality-parameters', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    <?php else: ?>
                                        <?php echo $__env->make('livewire.manajerial.sop.partials._production-steps', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                        </div>
        </div>
    </section>
        <!-- Modal Form -->
        <!--[if BLOCK]><![endif]--><?php if($showModal): ?>
    </section>
        <!-- Modal Form -->
        <!--[if BLOCK]><![endif]--><?php if($showModal): ?>
        <div class="modal fade show" style="display: block; background: rgba(0, 0, 0, 0.5);" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <?php echo e($isEditing ? 'Edit' : 'Add New'); ?> 
                            <?php echo e($sop->kategori === 'quality' ? 'Parameter' : 'Step'); ?>

                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <form wire:submit.prevent="<?php echo e($isEditing ? 'update' : 'store'); ?>">
                        <div class="modal-body">
                            <!--[if BLOCK]><![endif]--><?php if($sop->kategori === 'quality'): ?>
                                <?php echo $__env->make('livewire.manajerial.sop.partials._quality-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php else: ?>
                                <?php echo $__env->make('livewire.manajerial.sop.partials._production-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    
</div><?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/manajerial/sop/sop-detail.blade.php ENDPATH**/ ?>