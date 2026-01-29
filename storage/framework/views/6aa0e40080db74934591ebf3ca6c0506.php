<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/custom/pages/machine-sop-viewer.css')); ?>">
<?php $__env->stopPush(); ?>
<div>
    <!--[if BLOCK]><![endif]--><?php if($sop): ?>
        <div class="mt-4">
            <div class="sop-header d-flex justify-content-between align-items-center">
                <h5>Standard Operating Procedure (SOP)</h5>
                <div class="sop-badges">
                    <span class="badge bg-success">APPROVED</span>
                    <span class="badge bg-primary">ACTIVE</span>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="sop-info-table">
                        <table class="table table-sm">
                            <tr>
                                <td width="30%">NO. SOP</td>
                                <td>: <?php echo e($sop->no_sop); ?></td>
                            </tr>
                            <tr>
                                <td>NAMA SOP</td>
                                <td>: <?php echo e($sop->nama); ?></td>
                            </tr>
                            <tr>
                                <td>KATEGORI</td>
                                <td>: <?php echo e($sop->kategori); ?></td>
                            </tr>
                            <tr>
                                <td>VERSI</td>
                                <td>: <?php echo e($sop->versi); ?></td>
                            </tr>
                            <tr>
                                <td>MESIN</td>
                                <td>: <?php echo e($sop->machine->name); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="sop-info-table">
                        <table class="table table-sm">
                            <tr>
                                <td width="40%">DIBUAT OLEH</td>
                                <td>: <?php echo e($sop->creator->name ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td>DIBUAT PADA</td>
                                <td>: <?php echo e($sop->created_at->format('d/m/Y H:i')); ?></td>
                            </tr>
                            <tr>
                                <td>DISETUJUI OLEH</td>
                                <td>: <?php echo e($sop->approver->name ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td>DISETUJUI PADA</td>
                                <td>: <?php echo e($sop->approved_at?->format('d/m/Y H:i')); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="steps-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="5%">NO.</th>
                            <th width="20%">STEP NAME</th>
                            <th>DESCRIPTION</th>
                            <th width="15%">IMAGE</th>
                            <th width="15%">CHECKPOINT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $sop->steps()->orderBy('urutan')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($step->urutan); ?></td>
                                <td><?php echo e($step->judul); ?></td>
                                <td><?php echo e($step->deskripsi); ?></td>
                                <td class="text-center">
                                    <!--[if BLOCK]><![endif]--><?php if($step->cloudinary_url): ?>
                                        <div class="step-image">
                                            <img src="<?php echo e($step->cloudinary_url); ?>" 
                                                 alt="Step Image" 
                                                 class="img-fluid"
                                                 style="max-height: 80px; cursor: pointer;"
                                                 onclick="window.open(this.src, '_blank')">
                                        </div>
                                    <?php elseif($step->cloudinary_id): ?>
                                        <div class="step-image">
                                            <img src="https://res.cloudinary.com/dncabigef/image/upload/v1/<?php echo e($step->cloudinary_id); ?>" 
                                                 alt="Step Image" 
                                                 class="img-fluid"
                                                 style="max-height: 80px; cursor: pointer;"
                                                 onclick="window.open(this.src, '_blank')">
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>
                                <td>
                                    <!--[if BLOCK]><![endif]--><?php if($step->is_checkpoint): ?>
                                        <div class="checkpoint-info">
                                            <strong>Nilai Standar:</strong> <?php echo e($step->nilai_standar); ?><br>
                                            <strong>Toleransi:</strong><br>
                                            <?php echo e($step->toleransi_min); ?> - <?php echo e($step->toleransi_max); ?>

                                        </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/components/machine-sop-viewer.blade.php ENDPATH**/ ?>