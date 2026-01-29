<div class="row g-3" x-data="{ 
    isUploading: false,
    handleImageUpload() {
        const formData = new FormData();
        const file = $refs.imageInput.files[0];
        
        if (!file) {
            alert('Pilih file terlebih dahulu');
            return;
        }

        // Remove headers that might interfere with file upload
        formData.append('file', file);
        
        this.isUploading = true;
        
        console.log('Mencoba upload gambar...', file);

        fetch('<?php echo e(route('upload.image')); ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: formData
        })
        .then(async response => {
            console.log('Response status:', response.status);
            const data = await response.json();
            console.log('Raw response:', data);
            return data;
        })
        .then(data => {
            if (data.success) {
                window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('cloudinary_url', data.url);
                window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('cloudinary_id', data.public_id);
            } else {
                throw new Error(data.error || 'Terjadi kesalahan saat upload');
            }
        })
        .catch(error => {
            console.error('Error detail:', error);
            alert('Upload gagal: ' + error.message);
        })
        .finally(() => {
            this.isUploading = false;
        });
    }
}">
    <div class="col-md-6">
        <label class="form-label">Step Name</label>
        <input type="text" class="form-control" wire:model="judul">
        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <div class="col-md-6">
        <label class="form-label">Step Order</label>
        <input type="number" class="form-control" wire:model="urutan">
        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['urutan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea class="form-control" wire:model="deskripsi" rows="3"></textarea>
        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <div class="col-12">
        <label class="form-label">Image (Optional)</label>
        <input type="file" 
               class="form-control" 
               x-ref="imageInput"
               @change="handleImageUpload()"
               accept="image/*">
        
        <div x-show="isUploading">
            <div class="spinner-border spinner-border-sm text-primary mt-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <small class="text-muted ms-2">Uploading image...</small>
        </div>

        <!--[if BLOCK]><![endif]--><?php if($cloudinary_url): ?>
            <div class="mt-2 position-relative">
                <img src="<?php echo e($cloudinary_url); ?>" 
                     class="img-thumbnail" 
                     style="max-height: 200px">
                <button type="button" 
                        class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                        wire:click="removeImage">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <div class="col-12">
        <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-secondary" 
                    wire:click="closeModal">
                Cancel
            </button>
            <button type="button" class="btn btn-primary" 
                    wire:click="<?php echo e($isEditing ? 'update' : 'store'); ?>">
                <?php echo e($isEditing ? 'Update' : 'Save'); ?>

            </button>
        </div>
    </div>
</div><?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/manajerial/sop/partials/_production-form.blade.php ENDPATH**/ ?>