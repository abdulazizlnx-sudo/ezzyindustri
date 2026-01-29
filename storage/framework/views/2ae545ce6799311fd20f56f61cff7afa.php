<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th style="width: 5%">No.</th>
                <th style="width: 30%">Step Name</th>
                <th style="width: 45%">Description</th>
                <th style="width: 10%">Image</th>
                <th style="width: 10%">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $sop->steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-center"><?php echo e($step->urutan); ?></td>
                    <td><?php echo e($step->judul); ?></td>
                    <td><?php echo e($step->deskripsi); ?></td>
                    <td class="text-center">
                        <!--[if BLOCK]><![endif]--><?php if($step->gambar_path): ?>
                            <img src="<?php echo e($step->gambar_path); ?>" 
                                 alt="Step Image" 
                                 class="img-thumbnail" 
                                 style="max-height: 50px;">
                        <?php else: ?>
                            -
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-warning" wire:click="edit(<?php echo e($step->id); ?>)">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button wire:click="confirmDelete(<?php echo e($step->id); ?>)" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="text-center">Belum ada langkah yang ditambahkan</td>
                </tr>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </tbody>
    </table>
</div><?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/manajerial/sop/partials/_production-steps.blade.php ENDPATH**/ ?>