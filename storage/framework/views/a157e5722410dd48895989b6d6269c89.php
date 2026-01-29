<div wire:poll.5s>
    <div class="pagetitle">
        <h1>Problem Approval</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('manajerial.dashboard')); ?>">Home</a></li>
                <li class="breadcrumb-item active">Problem Approval</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Daftar Problem Produksi</h5>
                <div class="table-responsive">
                        <!-- ... kode sebelumnya ... -->
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Mesin</th>
                <th>Tipe Problem</th>
                <th>Catatan</th>
                <th>Foto</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $problems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $problem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($problem->reported_at->setTimezone('Asia/Jakarta')->format('d M Y H:i')); ?></td>
                    <td><?php echo e($problem->production->machine_name); ?></td>
                    <td><?php echo e(ucfirst($problem->problem_type)); ?></td>
                    <td><?php echo e($problem->notes); ?></td>
                    <td class="text-center">
                        <!--[if BLOCK]><![endif]--><?php if($problem->cloudinary_url): ?>
                            <img src="<?php echo e($problem->cloudinary_url); ?>" 
                                 alt="Problem Documentation" 
                                 class="img-thumbnail"
                                 style="max-height: 50px; cursor: pointer;"
                                 onclick="window.open(this.src, '_blank')"
                                 onerror="console.log('Image failed to load:', this.src)">
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </td>
                    <td>
                        <span class="badge bg-<?php echo e($problem->status === 'pending' ? 'warning' : 
                            ($problem->status === 'approved' ? 'success' : 'danger')); ?>">
                            <?php echo e(ucfirst($problem->status)); ?>

                        </span>
                    </td>
                    <td>
                        <!--[if BLOCK]><![endif]--><?php if($problem->status === 'pending'): ?>
                            <button class="btn btn-sm btn-success" wire:click="approve(<?php echo e($problem->id); ?>)">
                                <i class="bi bi-check-circle"></i> Approve
                            </button>
                            <button class="btn btn-sm btn-danger" wire:click="reject(<?php echo e($problem->id); ?>)">
                                <i class="bi bi-x-circle"></i> Reject
                            </button>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada problem yang perlu diapprove</td>
                </tr>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </tbody>
    </table>
    <!-- ... kode selanjutnya ... -->
                </div>
            </div>
        </div>
    </section>
</div>

<?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/manajerial/production/problem-approval.blade.php ENDPATH**/ ?>