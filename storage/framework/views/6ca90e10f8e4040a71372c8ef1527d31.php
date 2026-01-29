<?php $__env->startPush('styles'); ?>
    <link href="<?php echo e(asset('assets/css/custom/pages/start-production.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>
<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Mulai Produksi</h1>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?php echo e(route('karyawan.dashboard')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Mulai Produksi</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <!--[if BLOCK]><![endif]--><?php if(!$showChecksheet): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Form Mulai Produksi</h5>
                            
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <form wire:submit="startProduction">
                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label">Mesin</label>
                                            <div class="col-sm-9">
                                                <select class="form-select <?php $__errorArgs = ['selectedMachine'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                    wire:model.live="selectedMachine">
                                                    <option value="">-- Pilih Mesin --</option>
                                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $machines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($machine->id); ?>"><?php echo e($machine->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                </select>
                                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['selectedMachine'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label">Shift</label>
                                            <div class="col-sm-9">
                                                <select class="form-select <?php $__errorArgs = ['selectedShift'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                    wire:model.live="selectedShift">
                                                    <option value="">-- Pilih Shift --</option>
                                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($shift->id); ?>"><?php echo e($shift->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                </select>
                                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['selectedShift'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label">Produk</label>
                                            <div class="col-sm-9">
                                                <select class="form-select <?php $__errorArgs = ['product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                    wire:model.live="product_id">
                                                    <option value="">-- Pilih Produk --</option>
                                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($product->id); ?>"><?php echo e($product->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                </select>
                                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                            </div>
                                        </div>

                                        <div class="text-center">
                                        <button type="button" 
                                            class="btn btn-primary px-5" 
                                            wire:click="startProduction"
                                            wire:loading.attr="disabled">
                                            <i class="bi bi-arrow-right-circle me-1"></i>
                                            <span wire:loading.remove wire:target="startProduction">
                                                LANJUT KE CHECKSHEET
                                            </span>
                                        </button>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--[if BLOCK]><![endif]--><?php if($selectedMachine): ?>
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('components.machine-sop-viewer', ['machineId' => $selectedMachine]);

$__html = app('livewire')->mount($__name, $__params, 'sop-'.e($selectedMachine).'', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php else: ?>
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('components.checksheet-table', ['machineId' => $selectedMachine,'shiftId' => $selectedShift]);

$__html = app('livewire')->mount($__name, $__params, 'lw-1544427031-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </section>

        <?php
        $__scriptKey = '1544427031-0';
        ob_start();
    ?>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-error', (event) => {
                Toast.fire({
                    icon: 'error',
                    title: event.message
                });
            });
        });
    </script>
        <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>
</div><?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/karyawan/production/start-production.blade.php ENDPATH**/ ?>