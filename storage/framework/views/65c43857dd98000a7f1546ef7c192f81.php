<div>
    <div class="pagetitle">
        <h1>Pemeriksaan Kualitas</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('karyawan.dashboard')); ?>">Beranda</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(route('production.status')); ?>">Status Produksi</a></li>
                <li class="breadcrumb-item active">Pemeriksaan Kualitas</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">IN PROCESS INSPECTION REPORT</h5>
                
                <!-- Header Info -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold">Machine</div>
                                    <div class="col-8">: <?php echo e($production->machine); ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold">Product</div>
                                    <div class="col-8">: <?php echo e($production->product); ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-4 fw-bold">Date/Shift</div>
                                    <div class="col-8">: <?php echo e(now()->format('d-m-Y')); ?> / <?php echo e($production->shift->name ?? '-'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold">SOP Number</div>
                                    <div class="col-8">: <?php echo e($sop ? $sop->no_sop : 'No SOP Available'); ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-4 fw-bold">Check Time</div>
                                    <div class="col-8">: <?php echo e(now()->format('H:i')); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--[if BLOCK]><![endif]--><?php if(!$sop): ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        SOP Quality Check belum tersedia untuk produk ini. Silahkan hubungi supervisor.
                    </div>
                <?php else: ?>
                    <form wire:submit="validateMeasurements">
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th style="width: 5%">No.</th>
                                        <th style="width: 15%">Parameter</th>
                                        <th style="width: 20%">Description</th>
                                        <th style="width: 15%">Standard</th>
                                        <th style="width: 15%">Tolerance</th>
                                        <th style="width: 15%">Measured</th>
                                        <th style="width: 15%">Reference</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $sop->steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td class="text-center"><?php echo e($step->urutan); ?></td>
                                            <td><?php echo e($step->judul); ?></td>
                                            <td><?php echo e($step->deskripsi); ?></td>
                                            <td class="text-center">
                                                <div class="d-flex flex-column align-items-center">
                                                    <span><?php echo e($step->nilai_standar); ?></span>
                                                    <small class="text-muted"><?php echo e($step->measurement_unit); ?></small>
                                                    <!--[if BLOCK]><![endif]--><?php if($step->measurement_type): ?>
                                                        <small class="badge bg-light text-dark">
                                                            <!--[if BLOCK]><![endif]--><?php switch($step->measurement_type):
                                                                case ('diameter'): ?>
                                                                    <i class="bi bi-record-circle"></i> Diameter
                                                                    <?php break; ?>
                                                                <?php case ('length'): ?>
                                                                    <i class="bi bi-arrows-expand"></i> Length
                                                                    <?php break; ?>
                                                                <?php case ('weight'): ?>
                                                                    <i class="bi bi-weight"></i> Weight
                                                                    <?php break; ?>
                                                                <?php case ('temperature'): ?>
                                                                    <i class="bi bi-thermometer-half"></i> Temperature
                                                                    <?php break; ?>
                                                            <?php endswitch; ?><!--[if ENDBLOCK]><![endif]-->
                                                        </small>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                    $max = floatval(str_replace(',', '.', $step->toleransi_max));
                                                    $min = floatval(str_replace(',', '.', $step->toleransi_min));
                                                    $standard = floatval(str_replace(',', '.', $step->nilai_standar));
                                                    
                                                    $tolerance = ($max - $min) / 2;
                                                    
                                                    $formatted_tolerance = $tolerance < 0.01 
                                                        ? number_format($tolerance, 4, '.', '') 
                                                        : number_format($tolerance, 1, '.', '');
                                                ?>
                                                ± <?php echo e($formatted_tolerance); ?>

                                            </td>
                                            <td>
                                                <!-- Untuk input nilai pengukuran, tambahkan placeholder untuk menunjukkan format yang diharapkan -->
                                                <input type="text" class="form-control" 
                                                    wire:model="measurements.<?php echo e($step->id); ?>" 
                                                    placeholder="Contoh: 0,0072" 
                                                    wire:change="checkMeasurement(<?php echo e($step->id); ?>)" 
                                                    required>
                                                <!-- Ubah instruksi untuk konsistensi -->
                                                <small class="text-muted">Gunakan koma (,) sebagai pemisah desimal</small>
                                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ["measurements.{$step->id}"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <small class="text-danger"><?php echo e($message); ?></small>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                            </td>
                                            <td class="text-center">
                                                <!--[if BLOCK]><![endif]--><?php if($step->cloudinary_url): ?>
                                                    <img src="<?php echo e($step->cloudinary_url); ?>"
                                                         alt="Reference"
                                                         class="img-thumbnail"
                                                         style="height: 50px; cursor: pointer"
                                                         onclick="window.open(this.src, '_blank')">
                                                <?php elseif($step->cloudinary_id): ?>
                                                    <img src="https://res.cloudinary.com/dncabigef/image/upload/<?php echo e($step->cloudinary_id); ?>"
                                                         alt="Reference"
                                                         class="img-thumbnail"
                                                         style="height: 50px; cursor: pointer"
                                                         onclick="window.open(this.src, '_blank')">
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No parameters found</td>
                                        </tr>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </tbody>
                            </table>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes & Action Taken</label>
                            <textarea 
                                class="form-control" 
                                wire:model="notes"
                                rows="2"
                                placeholder="Enter any notes or actions taken..."
                            ></textarea>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                                <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <div class="text-end">
                            <a href="<?php echo e(route('production.status')); ?>" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary ms-2">
                                <i class="bi bi-save me-1"></i> Save
                            </button>
                        </div>
                    </form>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </section>
        <!-- Modal NG -->
        <!--[if BLOCK]><![endif]--><?php if($showNGModal): ?>
        <div wire:ignore.self class="modal fade" id="ngModal" tabindex="-1" aria-labelledby="ngModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ngModalLabel">Form NG</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit="saveNGData">
                            <div class="mb-3">
                                <label class="form-label">Jumlah NG</label>
                                <input type="number" class="form-control" wire:model="ngData.count" min="1">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['ngData.count'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis NG</label>
                                <input type="text" class="form-control" wire:model="ngData.type">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['ngData.type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea class="form-control" wire:model="ngData.notes"></textarea>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['ngData.notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="cancelNG">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            window.addEventListener('show-ng-modal', event => {
                var myModal = new bootstrap.Modal(document.getElementById('ngModal'));
                myModal.show();
            });
        </script>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Di bagian bawah file, sebelum tag penutup </div> -->
    
    <!-- Include NG Form Modal -->
    <?php echo $__env->make('livewire.karyawan.quality-check.partials.ng-form-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script>
        document.addEventListener('livewire:initialized', function () {
            // Listener untuk menampilkan konfirmasi sebelum menampilkan modal NG
            Livewire.on('show-ng-modal', () => {
                Swal.fire({
                    title: 'Perhatian!',
                    text: 'Terdapat pengukuran yang tidak sesuai standar (NG). Apakah Anda yakin ingin menyimpan data?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika user konfirmasi, tampilkan modal NG Form
                        var ngFormModal = new bootstrap.Modal(document.getElementById('ngFormModal'));
                        ngFormModal.show();
                    }
                });
            });

            // Listener untuk menampilkan alert biasa
            Livewire.on('show-alert', (data) => {
                Swal.fire({
                    title: data.title || 'Informasi',
                    text: data.message || 'Proses berhasil',
                    icon: data.type || 'info',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            });
        });
    </script>
</div>
<?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/karyawan/quality-check/quality-check-form.blade.php ENDPATH**/ ?>