<div>
    <div class="pagetitle">
        <h1>SOP Approval</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('manajerial.dashboard')); ?>">Home</a></li>
                <li class="breadcrumb-item active">SOP Approval</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Pending SOP Approvals</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No. SOP</th>
                                <th>Nama SOP</th>
                                <th>Kategori</th>
                                <th>Diajukan Oleh</th>
                                <th>Diajukan Pada</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $sops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($sop->no_sop); ?></td>
                                <td><?php echo e($sop->nama); ?></td>
                                <td><?php echo e(ucfirst($sop->kategori)); ?></td>
                                <td><?php echo e($sop->creator->name); ?></td>
                                <td><?php echo e($sop->submitted_at ? $sop->submitted_at->format('d/m/Y H:i') : '-'); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?php echo e(route('manajerial.sop.detail', $sop->id)); ?>" 
                                           class="btn btn-sm btn-info" wire:navigate>
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-success" 
                                                wire:click="approve(<?php echo e($sop->id); ?>)"
                                                wire:confirm="Are you sure you want to approve this SOP?">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" 
                                                wire:click="showRejectModal(<?php echo e($sop->id); ?>)">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada SOP yang menunggu persetujuan</td>
                            </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Rejection Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showModal): ?>
    <div class="modal fade show" style="display: block; background: rgba(0, 0, 0, 0.5);" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject SOP: <?php echo e($sop->nama); ?></h5>
                    <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                </div>
                <form wire:submit.prevent="reject">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Alasan Penolakan</label>
                            <textarea class="form-control" wire:model="rejection_reason" 
                                      rows="3" placeholder="Berikan alasan penolakan..."></textarea>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['rejection_reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                                <span class="text-danger"><?php echo e($message); ?></span> 
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" 
                                wire:click="$set('showModal', false)">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/manajerial/sop/sop-approval.blade.php ENDPATH**/ ?>