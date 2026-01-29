<div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">PDCA Management</h5>

            <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('message')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <?php if(session()->has('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Form PDCA -->
            <form wire:submit.prevent="save">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Fishbone Analysis Reference</label>
                        <select wire:model="selectedFishbone" class="form-select">
                            <option value="">Select Fishbone Analysis</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $fishboneAnalyses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $analysis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($analysis->id); ?>"><?php echo e($analysis->title); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Title</label>
                        <input type="text" wire:model="title" class="form-control" placeholder="PDCA Title">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select wire:model="status" class="form-select">
                            <option value="draft">Draft</option>
                            <option value="planning">Planning</option>
                            <option value="implementation">Implementation</option>
                            <option value="evaluation">Evaluation</option>
                            <option value="completed">Completed</option>
                            <option value="on_hold">On Hold</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea wire:model="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Start Date</label>
                        <input type="date" wire:model="startDate" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Target Date</label>
                        <input type="date" wire:model="targetDate" class="form-control">
                    </div>
                </div>

                <!-- PDCA Actions -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h6>PDCA Actions</h6>
                        
                        <!-- Plan Actions -->
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <span>Plan</span>
                                <button type="button" class="btn btn-light btn-sm" wire:click="addAction('plan')">Add Action</button>
                            </div>
                            <div class="card-body">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $actions['plan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <input type="text" wire:model="actions.plan.<?php echo e($index); ?>.action" class="form-control" placeholder="Action">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" wire:model="actions.plan.<?php echo e($index); ?>.detail" class="form-control" placeholder="Detail">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" wire:model="actions.plan.<?php echo e($index); ?>.pic" class="form-control" placeholder="PIC">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" wire:model="actions.plan.<?php echo e($index); ?>.target_date" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm" wire:click="removeAction('plan', <?php echo e($index); ?>)">Remove</button>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>

                        <!-- Do Actions -->
                        <div class="card mb-3">
                            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                <span>Do</span>
                                <button type="button" class="btn btn-light btn-sm" wire:click="addAction('do')">Add Action</button>
                            </div>
                            <div class="card-body">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $actions['do']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <input type="text" wire:model="actions.do.<?php echo e($index); ?>.action" class="form-control" placeholder="Action">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" wire:model="actions.do.<?php echo e($index); ?>.detail" class="form-control" placeholder="Detail">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" wire:model="actions.do.<?php echo e($index); ?>.pic" class="form-control" placeholder="PIC">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" wire:model="actions.do.<?php echo e($index); ?>.target_date" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm" wire:click="removeAction('do', <?php echo e($index); ?>)">Remove</button>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>

                        <!-- Check Actions -->
                        <div class="card mb-3">
                            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                <span>Check</span>
                                <button type="button" class="btn btn-light btn-sm" wire:click="addAction('check')">Add Action</button>
                            </div>
                            <div class="card-body">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $actions['check']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <input type="text" wire:model="actions.check.<?php echo e($index); ?>.action" class="form-control" placeholder="Action">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" wire:model="actions.check.<?php echo e($index); ?>.detail" class="form-control" placeholder="Detail">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" wire:model="actions.check.<?php echo e($index); ?>.pic" class="form-control" placeholder="PIC">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" wire:model="actions.check.<?php echo e($index); ?>.target_date" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm" wire:click="removeAction('check', <?php echo e($index); ?>)">Remove</button>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>

                        <!-- Act Actions -->
                        <div class="card mb-3">
                            <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                                <span>Act</span>
                                <button type="button" class="btn btn-light btn-sm" wire:click="addAction('act')">Add Action</button>
                            </div>
                            <div class="card-body">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $actions['act']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <input type="text" wire:model="actions.act.<?php echo e($index); ?>.action" class="form-control" placeholder="Action">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" wire:model="actions.act.<?php echo e($index); ?>.detail" class="form-control" placeholder="Detail">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" wire:model="actions.act.<?php echo e($index); ?>.pic" class="form-control" placeholder="PIC">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" wire:model="actions.act.<?php echo e($index); ?>.target_date" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm" wire:click="removeAction('act', <?php echo e($index); ?>)">Remove</button>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Save PDCA Project</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- PDCA Projects List -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title">PDCA Projects</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>Target Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $pdcaProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($project->title); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($project->status === 'completed' ? 'success' : 
                                        ($project->status === 'on_hold' ? 'warning' : 'primary')); ?>">
                                        <?php echo e(ucfirst($project->status)); ?>

                                    </span>
                                </td>
                                <td><?php echo e($project->start_date->format('Y-m-d')); ?></td>
                                <td><?php echo e($project->target_date->format('Y-m-d')); ?></td>
                                <td>
                                    <button wire:click="viewProject(<?php echo e($project->id); ?>)" class="btn btn-sm btn-info">View</button>
                                    <a href="<?php echo e(route('pdca.edit', ['id' => $project->id])); ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <button wire:click="deleteProject(<?php echo e($project->id); ?>)" 
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this project?')">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/manajerial/quality/pdca-management.blade.php ENDPATH**/ ?>