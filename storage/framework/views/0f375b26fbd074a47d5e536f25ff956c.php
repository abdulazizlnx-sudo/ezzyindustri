<div>
    <div class="pagetitle">
        <h1>Manajemen Task Maintenance</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('manajerial.dashboard')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Task Maintenance</li>
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
            <!-- Form Column -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo e($isEditing ? 'Edit Task' : 'Tambah Task Baru'); ?></h5>
                        <form wire:submit.prevent="<?php echo e($isEditing ? 'update' : 'create'); ?>">
                            <div class="mb-3">
                                <label class="form-label">Mesin</label>
                                <select class="form-select" wire:model="selectedMachine" required>
                                    <option value="">Pilih Mesin...</option>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $machines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($machine->id); ?>"><?php echo e($machine->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </select>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['selectedMachine'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tipe Maintenance</label>
                                <select class="form-select" wire:model="maintenanceType" required>
                                    <option value="am">Autonomous Maintenance</option>
                                    <option value="pm">Preventive Maintenance</option>
                                </select>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['maintenanceType'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Task</label>
                                <input type="text" class="form-control" wire:model="taskName" required>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['taskName'];
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
                                <textarea class="form-control" wire:model="description" rows="3"></textarea>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nilai Standar</label>
                                <input type="text" class="form-control" wire:model="standardValue">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['standardValue'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="requiresPhoto" id="requiresPhoto">
                                    <label class="form-check-label" for="requiresPhoto">
                                        Membutuhkan Foto
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Frekuensi</label>
                                <select class="form-select" wire:model="frequency" required>
                                    <option value="daily">Harian</option>
                                    <option value="weekly">Mingguan</option>
                                    <option value="monthly">Bulanan</option>
                                </select>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['frequency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Shift</label>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               wire:model="shiftIds" 
                                               value="<?php echo e($shift->id); ?>" 
                                               id="shift<?php echo e($shift->id); ?>">
                                        <label class="form-check-label" for="shift<?php echo e($shift->id); ?>">
                                            <?php echo e($shift->name); ?>

                                        </label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['shiftIds'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Waktu Yang Direkomendasikan</label>
                                <input type="time" class="form-control" wire:model="preferredTime">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['preferredTime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="isActive" id="isActive">
                                    <label class="form-check-label" for="isActive">
                                        Task Aktif
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo e($isEditing ? 'Update Task' : 'Tambah Task'); ?>

                                </button>
                                <!--[if BLOCK]><![endif]--><?php if($isEditing): ?>
                                    <button type="button" class="btn btn-secondary mt-2" wire:click="resetForm">
                                        Batal Edit
                                    </button>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Table Column -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Task Maintenance</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mesin</th>
                                        <th>Tipe</th>
                                        <th>Task</th>
                                        <th>Frekuensi</th>
                                        <th>Shift</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($task->machine->name); ?></td>
                                            <td><?php echo e(strtoupper($task->maintenance_type)); ?></td>
                                            <td>
                                                <?php echo e($task->task_name); ?>

                                                <!--[if BLOCK]><![endif]--><?php if($task->description): ?>
                                                    <small class="d-block text-muted"><?php echo e($task->description); ?></small>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </td>
                                            <td><?php echo e(ucfirst($task->frequency)); ?></td>
                                            <td>
                                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <!--[if BLOCK]><![endif]--><?php if(in_array($shift->id, json_decode($task->shift_ids) ?? [])): ?>
                                                        <span class="badge bg-info"><?php echo e($shift->name); ?></span>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                            </td>
                                            <td>
                                                <span class="badge <?php echo e($task->is_active ? 'bg-success' : 'bg-secondary'); ?>">
                                                    <?php echo e($task->is_active ? 'Aktif' : 'Non-aktif'); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" wire:click="edit(<?php echo e($task->id); ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" 
                                                        wire:click="delete(<?php echo e($task->id); ?>)"
                                                        onclick="return confirm('Yakin ingin menghapus task ini?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Belum ada task maintenance</td>
                                        </tr>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </tbody>
                            </table>
                        </div>
                        
                        <!--[if BLOCK]><![endif]--><?php if($tasks->hasPages()): ?>
                            <div class="d-flex justify-content-center mt-4">
                                <?php echo e($tasks->links()); ?>

                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div><?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/manajerial/manajemen/maintenance-task-management.blade.php ENDPATH**/ ?>