<div>
    <div class="pagetitle">
        <h1>Master SOP</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('manajerial.dashboard')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Master SOP</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo e($isEditing ? 'Edit SOP' : 'Tambah SOP Baru'); ?></h5>
                        <form wire:submit.prevent="<?php echo e($isEditing ? 'update' : 'store'); ?>">
                            <div class="mb-3">
                                <label class="form-label">No. SOP</label>
                                <input type="text" class="form-control" wire:model="no_sop" placeholder="Contoh: SOP-001">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['no_sop'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama SOP</label>
                                <input type="text" class="form-control" wire:model="nama">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select class="form-select" wire:model.live="kategori">
                                    <option value="">Pilih Kategori</option>
                                    <option value="produksi">Produksi</option>
                                    <option value="quality">Quality Control</option>
                                    <option value="safety">Safety</option>
                                </select>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['kategori'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <!--[if BLOCK]><![endif]--><?php if($kategori == 'quality'): ?>
                            <div class="mb-3">
                                <label class="form-label">Produk</label>
                                <select class="form-select" wire:model="product_id">
                                    <option value="">Pilih Produk</option>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($product->id); ?>"><?php echo e($product->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </select>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                            <!--[if BLOCK]><![endif]--><?php if($kategori == 'produksi' || $kategori == 'safety'): ?>
                            <div class="mb-3">
                                <label class="form-label">Mesin</label>
                                <select class="form-select" wire:model="machine_id">
                                    <option value="">Pilih Mesin</option>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $machines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($machine->id); ?>"><?php echo e($machine->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </select>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['machine_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" wire:model="deskripsi" rows="3"></textarea>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Versi</label>
                                <input type="text" class="form-control" wire:model="versi" value="1.0" placeholder="Contoh: 1.0, 1.1, 2.0">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['versi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <?php echo e($isEditing ? 'Update' : 'Simpan'); ?>

                            </button>
                            <!--[if BLOCK]><![endif]--><?php if($isEditing): ?>
                                <button type="button" class="btn btn-secondary" wire:click="$set('isEditing', false)">
                                    Batal
                                </button>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar SOP</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No. SOP</th>
                                        <th>Nama SOP</th>
                                        <th>Kategori</th>
                                        <th>Dibuat Pada</th>
                                        <th>Dibuat Oleh</th>
                                        <th>Status</th>
                                        <th>Disetujui Oleh</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $sops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($sop->no_sop); ?></td>
                                        <td><?php echo e($sop->nama); ?></td>
                                        <td><?php echo e(ucfirst($sop->kategori)); ?></td>
                                        <td><?php echo e($sop->created_date ? $sop->created_date->format('d/m/Y H:i') : '-'); ?></td>
                                        <td><?php echo e($sop->creator->name ?? '-'); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($sop->approval_status === 'approved' ? 'success' : 
                                                ($sop->approval_status === 'pending' ? 'warning' : 
                                                ($sop->approval_status === 'rejected' ? 'danger' : 'secondary'))); ?>">
                                                <?php echo e(ucfirst($sop->approval_status)); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($sop->approver->name ?? '-'); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?php echo e(route('manajerial.sop.detail', $sop->id)); ?>" 
                                                   class="btn btn-sm btn-info" wire:navigate>
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <!--[if BLOCK]><![endif]--><?php if($sop->approval_status === 'draft'): ?>
                                                <button class="btn btn-sm btn-warning" wire:click="edit(<?php echo e($sop->id); ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" 
                                                        wire:click="confirmDelete(<?php echo e($sop->id); ?>)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Belum ada data SOP</td>
                                        </tr>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div><?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/manajerial/sop/sop-index.blade.php ENDPATH**/ ?>