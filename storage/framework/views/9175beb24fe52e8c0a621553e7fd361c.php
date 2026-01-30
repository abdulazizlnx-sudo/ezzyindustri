<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th style="width: 5%">No.</th>
                <th style="width: 15%">Parameter</th>
                <th style="width: 15%">Description</th>
                <th style="width: 10%">Standard</th>
                <th style="width: 10%">Tolerance</th>
                <th style="width: 10%">Unit</th>
                <th style="width: 15%">Check Interval</th>
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
                    <td class="text-center"><?php echo e($step->nilai_standar); ?></td>
                    <td class="text-center"><?php echo e($step->toleransi_min); ?> - <?php echo e($step->toleransi_max); ?></td>
                    <td class="text-center">
                        <?php echo e($step->measurement_unit); ?>

                        <div class="small text-muted"><?php echo e($step->measurement_type); ?></div>
                    </td>
                    <td class="text-center">
                        Every <?php echo e($step->interval_value); ?> <?php echo e($step->interval_unit); ?>

                    </td>
                    <td class="text-center">
                        <!--[if BLOCK]><![endif]--><?php if($step->cloudinary_url): ?>
                            <img src="<?php echo e($step->cloudinary_url); ?>" 
                                 alt="Quality Parameter Image" 
                                 class="img-thumbnail" 
                                 style="max-height: 50px; cursor: pointer;"
                                 onclick="window.open(this.src, '_blank')"
                            >
                        <?php elseif($step->cloudinary_id): ?>
                            <img src="https://res.cloudinary.com/dlmm3yrkz/image/upload/v1769779119/<?php echo e($step->cloudinary_id); ?>" 
                                 alt="Quality Parameter Image" 
                                 class="img-thumbnail" 
                                 style="max-height: 50px; cursor: pointer;"
                                 onclick="window.open(this.src, '_blank')"
                            >
                        <?php else: ?>
                            <span class="text-muted">-</span>
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
                    <td colspan="9" class="text-center">Belum ada parameter yang ditambahkan</td>
                </tr>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </tbody>
    </table>
</div><?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/manajerial/sop/partials/_quality-parameters.blade.php ENDPATH**/ ?>