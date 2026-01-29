<div>
    <div class="pagetitle">
        <h1>Manajemen Mesin</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('manajerial.dashboard')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Mesin</li>
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

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Daftar Mesin</h5>
                            <button class="btn btn-primary" wire:click="createMachine">
                                <i class="bi bi-plus-lg"></i> Tambah Mesin
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Tipe</th>
                                        <th>Lokasi</th>
                                        <th>Target OEE</th>
                                        <th>Alert</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $machines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($machine->code); ?></td>
                                            <td><?php echo e($machine->name); ?></td>
                                            <td><?php echo e($machine->type); ?></td>
                                            <td><?php echo e($machine->location); ?></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?php echo e(number_format($machine->oee_target, 2)); ?>%
                                                </span>
                                            </td>
                                            <td>
                                                <!--[if BLOCK]><![endif]--><?php if($machine->alert_enabled): ?>
                                                    <span class="badge bg-success" 
                                                          title="Email: <?php echo e($machine->alert_email); ?><?php echo e($machine->alert_phone ? ', WA: '.$machine->alert_phone : ''); ?>">
                                                        <i class="bi bi-bell-fill"></i> Active
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-bell-slash"></i> Disabled
                                                    </span>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo e($machine->status === 'active' ? 'success' : 'secondary'); ?>">
                                                    <?php echo e($machine->status === 'active' ? 'Aktif' : 'Non-aktif'); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning" wire:click="editMachine(<?php echo e($machine->id); ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" wire:click="confirmDelete(<?php echo e($machine->id); ?>)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Belum ada data mesin</td>
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

    <!--[if BLOCK]><![endif]--><?php if($showModal): ?>
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo e($editMode ? 'Edit Mesin' : 'Tambah Mesin Baru'); ?></h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <form wire:submit.prevent="saveMachine">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Kode Mesin</label>
                            <input type="text" class="form-control" wire:model="form.code" required>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Mesin</label>
                            <input type="text" class="form-control" wire:model="form.name" required>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipe</label>
                            <select class="form-select" wire:model="form.type" required>
                                <option value="">Pilih Tipe...</option>
                                <option value="Mesin">Mesin</option>
                                <option value="Aset Pendukung">Aset Pendukung</option>
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lokasi</label>
                            <input type="text" class="form-control" wire:model="form.location">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" wire:model="form.description" rows="3"></textarea>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" wire:model="form.status">
                                <option value="active">Aktif</option>
                                <option value="inactive">Non-aktif</option>
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <!-- Di dalam form modal -->
                        <div class="mb-3">
                            <label class="form-label">Target OEE (%)</label>
                            <input type="number" 
                                   class="form-control" 
                                   wire:model="form.oee_target" 
                                   step="0.01" 
                                   min="0" 
                                   max="100">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.oee_target'];
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
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       wire:model="form.alert_enabled" 
                                       id="alertEnabled">
                                <label class="form-check-label" for="alertEnabled">
                                    Aktifkan Alert OEE
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3" x-show="$wire.form.alert_enabled">
                            <label class="form-label">Email Alert</label>
                            <input type="email" 
                                   class="form-control" 
                                   wire:model="form.alert_email" 
                                   placeholder="supervisor@example.com">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.alert_email'];
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
                        
                        <!-- Tambahkan field WhatsApp Alert -->
                        <div class="mb-3" x-show="$wire.form.alert_enabled">
                            <label class="form-label">WhatsApp Alert</label>
                            <input type="text" 
                                   class="form-control" 
                                   wire:model="form.alert_phone" 
                                   placeholder="628123456789">
                            <div class="form-text">Format: 628xxxxxxxxxx (tanpa tanda + atau spasi)</div>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['form.alert_phone'];
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
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Delete Modal -->
    <!--[if BLOCK]><![endif]--><?php if($showDeleteModal): ?>
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" wire:click="cancelDelete"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus mesin ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cancelDelete">Batal</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteMachine">Hapus</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/manajerial/manajemen/machine-management.blade.php ENDPATH**/ ?>