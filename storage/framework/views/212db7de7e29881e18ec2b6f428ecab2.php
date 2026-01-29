<?php $__env->startPush('styles'); ?>
    <link href="<?php echo e(asset('assets/css/custom/pages/checksheet-table.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>
<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Checksheet Produksi</h1>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?php echo e(route('karyawan.dashboard')); ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(route('production.start')); ?>">Mulai Produksi</a></li>
                <li class="breadcrumb-item active">Checksheet</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Daftar Pemeriksaan Mesin</h5>
                    <div style="width: 200px">
                        <h6 class="mb-2">Progress Pemeriksaan</h6>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped bg-primary progress-bar-animated" 
                                 role="progressbar" 
                                 style="<?php echo \Illuminate\Support\Arr::toCssStyles("width: {$this->progress}%") ?>"
                                 aria-valuenow="<?php echo e($this->progress); ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                <?php echo e($this->progress); ?>%
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 5%">NO</th>
                                <th style="width: 20%">NAMA PEMERIKSAAN</th>
                                <th>DESKRIPSI</th>
                                <th style="width: 15%">NILAI STANDAR</th>
                                <th style="width: 20%">STATUS</th>
                                <th style="width: 20%">DOKUMENTASI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center"><?php echo e($index + 1); ?></td>
                                    <td>
                                        <div class="fw-bold"><?php echo e($task->task_name); ?></div>
                                        <span class="badge bg-<?php echo e($task->maintenance_type === 'am' ? 'info' : 'warning'); ?>">
                                            <?php echo e(strtoupper($task->maintenance_type)); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php echo e($task->description); ?>

                                        <div class="mt-2">
                                            <textarea class="form-control form-control-sm" 
                                                    wire:model.live="notes.<?php echo e($task->id); ?>"
                                                    rows="2"
                                                    placeholder="Tambahkan catatan..."></textarea>
                                        </div>
                                    </td>
                                    <td>
                                        <!--[if BLOCK]><![endif]--><?php if($task->standard_value): ?>
                                            <div class="small">
                                                <strong>Standar:</strong><br>
                                                <?php echo e($task->standard_value); ?>

                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>
                                    <td>
                                        <div class="btn-group w-100" role="group">
                                            <input type="radio" class="btn-check" 
                                                   wire:model.live="checkResults.<?php echo e($task->id); ?>" 
                                                   id="ok<?php echo e($task->id); ?>" 
                                                   value="ok">
                                            <label class="btn btn-outline-success btn-sm" for="ok<?php echo e($task->id); ?>">
                                                <i class="bi bi-check-circle"></i>
                                            </label>

                                            <input type="radio" class="btn-check" 
                                                   wire:model.live="checkResults.<?php echo e($task->id); ?>" 
                                                   id="not_ok<?php echo e($task->id); ?>" 
                                                   value="not_ok">
                                            <label class="btn btn-outline-danger btn-sm" for="not_ok<?php echo e($task->id); ?>">
                                                <i class="bi bi-x-circle"></i>
                                            </label>

                                            <input type="radio" class="btn-check" 
                                                   wire:model.live="checkResults.<?php echo e($task->id); ?>" 
                                                   id="na<?php echo e($task->id); ?>" 
                                                   value="na">
                                            <label class="btn btn-outline-secondary btn-sm" for="na<?php echo e($task->id); ?>">
                                                <i class="bi bi-dash-circle"></i>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <!--[if BLOCK]><![endif]--><?php if($task->requires_photo): ?>
                                            <div class="photo-upload-container p-2" x-data="{ 
                                                isUploading: false,
                                                handleImageUpload() {
                                                    const formData = new FormData();
                                                    const file = $refs.imageInput.files[0];
                                                    
                                                    if (!file) {
                                                        alert('Pilih file terlebih dahulu');
                                                        return;
                                                    }
                                                    
                                                    formData.append('file', file);
                                                    formData.append('folder', 'checksheet_tpm'); // Changed folder name
                                                    this.isUploading = true;
                                                    
                                                    fetch('<?php echo e(route('upload.image')); ?>', {
                                                        method: 'POST',
                                                        headers: {
                                                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                                                        },
                                                        body: formData
                                                    })
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        if (data.success) {
                                                            window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('photos.' + <?php echo e($task->id); ?>, {
                                                                url: data.url,
                                                                public_id: data.public_id
                                                            });
                                                            window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('previewUrl.' + <?php echo e($task->id); ?>, data.url);
                                                        } else {
                                                            throw new Error(data.error || 'Upload gagal');
                                                        }
                                                    })
                                                    .catch(error => {
                                                        console.error('Error:', error);
                                                        alert('Upload gagal: ' + error.message);
                                                    })
                                                    .finally(() => {
                                                        this.isUploading = false;
                                                    });
                                                }
                                            }">
                                                <!--[if BLOCK]><![endif]--><?php if(isset($previewUrl[$task->id])): ?>
                                                    <div class="image-preview-wrapper mb-2">
                                                        <img src="<?php echo e($previewUrl[$task->id]); ?>" 
                                                             class="img-preview"
                                                             alt="Preview"
                                                             onclick="window.open(this.src, '_blank')"
                                                             style="cursor: pointer;">
                                                        <small class="d-block text-muted mt-1">Klik untuk memperbesar</small>
                                                    </div>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                
                                                <div class="upload-wrapper">
                                                    <input type="file" 
                                                           class="form-control form-control-sm" 
                                                           x-ref="imageInput"
                                                           @change="handleImageUpload()"
                                                           accept="image/*">
                                                    
                                                    <div x-show="isUploading" class="upload-loading mt-2">
                                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                        <small class="ms-2">Mengupload foto...</small>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>

                <div class="text-center mt-4">
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-secondary me-2" wire:click="back">
                            <i class="bi bi-arrow-left me-1"></i>
                            Kembali
                        </button>
                    <button type="button" class="btn btn-primary px-5" 
                            wire:click="startProduction" 
                            <?php echo e(count($checkResults) !== count($tasks) ? 'disabled' : ''); ?>

                            onclick="console.log('=== BUTTON CLICK DEBUG ==='); 
                                     console.log('Check Results:', <?php echo \Illuminate\Support\Js::from($checkResults)->toHtml() ?>);
                                     console.log('Tasks Count:', <?php echo e(count($tasks)); ?>);
                                     console.log('Check Results Count:', <?php echo e(count($checkResults)); ?>);
                                     console.log('Button Enabled:', <?php echo e(count($checkResults) === count($tasks)); ?>);
                                     if (<?php echo e(count($checkResults) === count($tasks)); ?>) {
                                         console.log('Production should start...');
                                     }">
                        <span wire:loading.remove wire:target="startProduction">
                            <i class="bi bi-play-circle me-1"></i>
                            Mulai Produksi
                        </span>
                        <span wire:loading wire:target="startProduction">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Memproses...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <style>
        .photo-upload-container {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            background: #f8f9fa;
        }
        .img-preview {
            max-width: 100%;
            height: 80px;
            object-fit: contain;
        }
        .progress {
            height: 10px;
            border-radius: 5px;
        }
        .progress-bar {
            background-color: #4154f1;
            transition: width 0.3s ease;
        }
        .table > :not(caption) > * > * {
            padding: 0.75rem;
            vertical-align: middle;
        }
    </style>

        <?php
        $__scriptKey = '2686152490-0';
        ob_start();
    ?>
    <script>
        Livewire.on('photo-uploaded', ({ taskId }) => {
            Toast.fire({
                icon: 'success',
                title: 'Foto berhasil diunggah'
            });
        });
    </script>
        <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>
</div><?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/components/checksheet-table.blade.php ENDPATH**/ ?>