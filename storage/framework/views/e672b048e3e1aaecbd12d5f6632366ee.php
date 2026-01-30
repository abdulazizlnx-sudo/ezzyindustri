<div>
    <div class="card">
        <div class="card-header">

            <!-- Di bagian atas tabel atau di tempat yang sesuai -->
            <!--[if BLOCK]><![endif]--><?php if(session()->has('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Di bagian atas tabel -->   
            <?php if(session()->has('message')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('message')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <!-- Main Form -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">NG Report Reference</label>
                    <select wire:model="selectedNGReport" class="form-select">
                        <option value="">Select NG Report</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $ngReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($report->id); ?>"><?php echo e($report->date); ?> - <?php echo e($report->ng_type); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Quality Check Reference</label>
                    <select wire:model="selectedQualityCheck" class="form-select">
                        <option value="">Select Quality Check</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $qualityChecks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $check): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($check->id); ?>"><?php echo e($check->check_time); ?> - <?php echo e($check->status); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Analysis Title</label>
                    <input type="text" wire:model="title" class="form-control" placeholder="Enter analysis title">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Problem Statement</label>
                    <textarea wire:model="problemStatement" rows="3" class="form-control" placeholder="Describe the problem in detail"></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Analysis Date</label>
                    <input type="date" wire:model="analysisDate" class="form-control">
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Root Cause Analysis Form -->
            <div class="mb-4">
                <h5 class="card-title">Root Cause Analysis</h5>
                
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $causes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $categoryCauses): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><?php echo e($category); ?></h6>
                                <button wire:click="addCause('<?php echo e($category); ?>')" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus"></i> Add Cause
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categoryCauses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $cause): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="row mb-3 align-items-center">
                                    <div class="col-md-3">
                                        <input type="text" wire:model="causes.<?php echo e($category); ?>.<?php echo e($index); ?>.cause" 
                                               class="form-control" placeholder="Cause">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" wire:model="causes.<?php echo e($category); ?>.<?php echo e($index); ?>.description" 
                                               class="form-control" placeholder="Description">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" wire:model="causes.<?php echo e($category); ?>.<?php echo e($index); ?>.pic" 
                                               class="form-control" placeholder="PIC">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" wire:model="causes.<?php echo e($category); ?>.<?php echo e($index); ?>.target_date" 
                                               class="form-control">
                                    </div>
                                    <div class="col-md-1">
                                        <button wire:click="removeCause('<?php echo e($category); ?>', <?php echo e($index); ?>)" 
                                                class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Generate Diagram Button -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-4">
                <button wire:click="generateDiagram" class="btn btn-primary me-2">
                    <i class="bi bi-diagram-3"></i> Generate Fishbone Diagram
                </button>
                <button wire:click="save" class="btn btn-success" <?php echo e(!$showDiagram ? 'disabled' : ''); ?>>
                    <i class="bi bi-save"></i> <?php echo e($editMode ? 'Update' : 'Save'); ?> Analysis
                </button>
            </div>

            <?php $__env->startPush('scripts'); ?>
            <!-- Tambahkan setelah script yang ada -->
            <script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                let isUploading = false;

                // Mermaid configuration
                mermaid.initialize({
                    startOnLoad: true,
                    theme: 'default',
                    securityLevel: 'loose',
                    flowchart: {
                        curve: 'basis',
                        padding: 20,
                        nodeSpacing: 50,
                        rankSpacing: 100,
                        useMaxWidth: false,
                        htmlLabels: true
                    }
                });

                // Diagram upload handler
                Livewire.on('saveDiagram', () => {
                    if (isUploading) {
                        console.log('🚫 Upload already in progress...');
                        return;
                    }

                    // Show loading state
                    Swal.fire({
                        title: 'Saving Diagram',
                        html: 'Please wait while we process your diagram...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    isUploading = true;
                    console.log('🚀 Starting diagram upload process...');
                    
                    const element = document.getElementById('fishboneDiagram');
                    const svg = element?.querySelector('svg');
                    
                    if (!element || !svg) {
                        isUploading = false;
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Diagram elements not found'
                        });
                        return;
                    }

                    const canvas = document.createElement('canvas');
                    canvas.width = 1280;
                    canvas.height = 720;
                    const ctx = canvas.getContext('2d');
                    
                    const img = new Image();
                    img.onload = function() {
                        ctx.fillStyle = 'white';
                        ctx.fillRect(0, 0, canvas.width, canvas.height);
                        
                        const scale = Math.min(canvas.width/img.width, canvas.height/img.height);
                        const x = (canvas.width - img.width * scale) / 2;
                        const y = (canvas.height - img.height * scale) / 2;
                        
                        ctx.drawImage(img, x, y, img.width * scale, img.height * scale);
                        
                        canvas.toBlob(function(blob) {
                            const formData = new FormData();
                            formData.append('file', blob, 'diagram.png');
                            formData.append('upload_preset', 'fishbone_diagrams');
                            
                            fetch('https://api.cloudinary.com/v1_1/dlmm3yrkz/image/upload', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(result => {
                                console.log('✅ Upload complete:', result);
                                // Pass editMode status to backend
                                const editMode = document.querySelector('[wire\\:model="editMode"]')?.value === 'true';
                                const editId = document.querySelector('[wire\\:model="editId"]')?.value;
                                
                                Livewire.dispatch('uploadDiagram', [
                                    result.secure_url, 
                                    result.public_id,
                                    editMode,
                                    editId
                                ]);
                            })
                            .catch(error => {
                                console.error('❌ Upload failed:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Upload Failed',
                                    text: 'Failed to upload diagram to cloud storage'
                                });
                            })
                            .finally(() => {
                                isUploading = false;
                            });
                        }, 'image/png', 1.0);
                    };
                    
                    img.onerror = function() {
                        console.error('❌ Failed to load SVG');
                        isUploading = false;
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to process diagram'
                        });
                    };
                    
                    const svgData = new XMLSerializer().serializeToString(svg);
                    img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
                });
            
                // Upload complete handler
                Livewire.on('uploadComplete', (event) => {
                    Swal.close();
                    console.log('📝 Upload complete response:', event);
                    const data = Array.isArray(event) ? event[0] : event;
                    
                    if (data?.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data?.message || 'Failed to update diagram'
                        });
                    }
                });
            
                // Cleanup on disconnect
                Livewire.on('$disconnect', () => {
                    isUploading = false;
                });
            </script>
            <?php $__env->stopPush(); ?>

            <!-- Fishbone Diagram section -->
            <!--[if BLOCK]><![endif]--><?php if($showDiagram): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Fishbone Diagram</h5>
                    </div>
                    <div class="card-body">
                        <div id="fishboneDiagram" class="mermaid text-center">
                            <?php echo e($diagramCode); ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>

    <!-- Previous Analyses Table -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title">Previous Analyses</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <!-- Di bagian tabel Previous Analyses -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $analyses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $analysis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($analysis->title); ?></td>
                                <td><?php echo e($analysis->analysis_date); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($analysis->status === 'draft' ? 'warning' : 
                                        ($analysis->status === 'in_progress' ? 'info' : 'success')); ?>">
                                        <?php echo e(ucfirst($analysis->status)); ?>

                                    </span>
                                </td>
                                <td><?php echo e($analysis->created_by); ?></td>
                                <!-- Di bagian tabel Previous Analyses -->
                                <td class="text-nowrap">
                                    <div class="btn-group" role="group">
                                        <button wire:click="viewDiagram(<?php echo e($analysis->id); ?>)" 
                                                class="btn btn-sm btn-info" 
                                                title="View Diagram">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                
                                        <button wire:click="viewDetail(<?php echo e($analysis->id); ?>)" 
                                                class="btn btn-sm btn-primary" 
                                                title="View Details">
                                            <i class="bi bi-info-circle"></i>
                                        </button>
                                
                                        <button wire:click="editAnalysis(<?php echo e($analysis->id); ?>)" 
                                                class="btn btn-sm btn-warning" 
                                                title="Edit Analysis">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                
                                        <a href="<?php echo e(route('fishbone.pdf', $analysis->id)); ?>" 
                                           target="_blank" 
                                           class="btn btn-sm btn-danger" 
                                           title="Export PDF">
                                            <i class="bi bi-file-pdf"></i>
                                        </a>
                                
                                        <!--[if BLOCK]><![endif]--><?php if($analysis->status === 'draft'): ?>
                                            <button wire:click="updateStatus(<?php echo e($analysis->id); ?>, 'in_progress')" 
                                                    class="btn btn-sm btn-success" 
                                                    title="Submit Analysis">
                                                <i class="bi bi-check2-circle"></i>
                                            </button>
                                            
                                            <!-- Ganti button delete menjadi seperti ini -->
                                            <button onclick="confirmDelete(<?php echo e($analysis->id); ?>)" 
                                                    type="button"
                                                    class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
   
     <!-- Detail Modal -->
     <div class="modal fade" id="detailModal" tabindex="-1">
         <div class="modal-dialog modal-xl">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title">Analysis Detail</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                 </div>
                 <div class="modal-body">
                     <!--[if BLOCK]><![endif]--><?php if($selectedAnalysis): ?>
                         <!-- Diagram Section -->
                         <div class="mb-4">
                             <h6>Fishbone Diagram</h6>
                             <!--[if BLOCK]><![endif]--><?php if($selectedAnalysis->cloudinary_url): ?>
                                 <div class="text-center">
                                     <img src="<?php echo e($selectedAnalysis->cloudinary_url); ?>" 
                                          class="img-fluid rounded shadow" 
                                          alt="Fishbone Diagram"
                                          style="max-width: 100%; height: auto;"
                                          onerror="console.error('Failed to load image:', this.src)">
                                 </div>
                             <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                         </div>
     
                         <!-- Problem Statement -->
                         <div class="mb-3">
                             <h6>Problem Statement</h6>
                             <p><?php echo e($selectedAnalysis->problem_statement); ?></p>
                         </div>
     
                         <!-- Root Causes -->
                         <div class="mb-3">
                             <h6>Root Causes</h6>
                             <div class="row">
                                 <!--[if BLOCK]><![endif]--><?php $__currentLoopData = ['Man', 'Machine', 'Method', 'Material', 'Measurement', 'Environment']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                     <div class="col-md-6 mb-3">
                                         <div class="card">
                                             <div class="card-header"><?php echo e($category); ?></div>
                                             <div class="card-body">
                                                 <ul class="list-unstyled">
                                                     <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $selectedAnalysis->causes->where('category', $category); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cause): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                         <li class="mb-2">
                                                             <strong><?php echo e($cause->cause); ?></strong>
                                                             <!--[if BLOCK]><![endif]--><?php if($cause->description): ?>
                                                                 <br>
                                                                 <small class="text-muted">Description: <?php echo e($cause->description); ?></small>
                                                             <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                             <!--[if BLOCK]><![endif]--><?php if($cause->pic): ?>
                                                                 <br>
                                                                 <small class="text-muted">PIC: <?php echo e($cause->pic); ?></small>
                                                             <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                             <!--[if BLOCK]><![endif]--><?php if($cause->target_date): ?>
                                                                 <br>
                                                                 <small class="text-muted">Target: <?php echo e($cause->target_date); ?></small>
                                                             <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                         </li>
                                                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                         <li class="text-muted">No causes found</li>
                                                     <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                 </ul>
                                             </div>
                                         </div>
                                     </div>
                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                             </div>
                         </div>
                     <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                 </div>
             </div>
         </div>
     </div>
         <!-- Setelah Detail Modal, tambahkan Diagram Modal -->
    <div class="modal fade" id="diagramModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Fishbone Diagram</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <!--[if BLOCK]><![endif]--><?php if($selectedAnalysis && $selectedAnalysis->cloudinary_url): ?>
                        <img src="<?php echo e($selectedAnalysis->cloudinary_url); ?>" 
                             class="img-fluid rounded shadow" 
                             alt="Fishbone Diagram"
                             style="max-width: 100%; height: auto;">
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>
    </div>
    <!-- Status Update Modal -->
        <div class="modal fade" id="statusModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea wire:model="statusNotes" class="form-control" rows="3" 
                                      placeholder="Add any relevant notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" wire:click="confirmStatusUpdate">Update</button>
                    </div>
                </div>
            </div>
        </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>
<script src="https://upload-widget.cloudinary.com/global/all.js"></script>
<script>
    // Konfigurasi mermaid
    mermaid.initialize({
        startOnLoad: true,
        theme: 'default',
        securityLevel: 'loose',
        flowchart: {
            curve: 'basis',
            padding: 20,
            nodeSpacing: 50,
            rankSpacing: 100,
            useMaxWidth: false,
            htmlLabels: true
        }
    });

    // Fungsi untuk render diagram
    function renderMermaidDiagram() {
        const element = document.getElementById('fishboneDiagram');
        if (!element) return;
        
        element.removeAttribute('data-processed');
        mermaid.init(undefined, element);
    }

    // Event listener untuk diagram generation
    window.addEventListener('diagramGenerated', event => {
        setTimeout(() => {
            renderMermaidDiagram();
            // Tambah delay untuk memastikan diagram ter-render
            setTimeout(adjustDiagramSize, 500);
        }, 100);
    });

    // Fungsi untuk menyesuaikan ukuran diagram
    function adjustDiagramSize() {
        const svg = document.querySelector('#fishboneDiagram svg');
        if (svg) {
            svg.style.width = '1280px';  // Lebih kecil dari sebelumnya (1920px)
            svg.style.height = '720px';   // Lebih kecil dari sebelumnya (1080px)
            svg.setAttribute('width', '1280');
            svg.setAttribute('height', '720');
            svg.setAttribute('viewBox', '0 0 1280 720');
        }
    }

    // Modal event listeners
    window.addEventListener('showDiagramModal', event => {
        const modal = new bootstrap.Modal(document.getElementById('diagramModal'));
        modal.show();
    });

    window.addEventListener('showDetailModal', event => {
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        modal.show();
    });

    window.confirmDelete = function(id) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data analisis fishbone akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Call the Livewire method
                window.Livewire.find('<?php echo e($_instance->getId()); ?>').deleteAnalysis(id);
            }
        });
    }

    // Update the delete complete listener
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('deleteComplete', (response) => {
            const data = Array.isArray(response) ? response[0] : response;
            
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data berhasil dihapus',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message || 'Gagal menghapus data'
                });
            }
        });
    });
</script>


<style>
    #fishboneDiagram {
        width: 100%;
        min-height: 400px; // Lebih kecil dari sebelumnya
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .mermaid svg {
        max-width: none !important;
    }
</style>
<?php $__env->stopPush(); ?>



   <?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/manajerial/quality/fishbone-analysis.blade.php ENDPATH**/ ?>