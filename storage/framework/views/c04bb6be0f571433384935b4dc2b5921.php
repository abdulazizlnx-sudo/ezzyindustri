<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title">Pareto Analysis - NG Types</h5>
                <div>
                    <button wire:click="exportPdf" class="btn btn-danger">
                        <i class="bi bi-file-pdf"></i> Export PDF
                    </button>
                    <button wire:click="exportExcel" class="btn btn-success">
                        <i class="bi bi-file-excel"></i> Export Excel
                    </button>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" wire:model.live="startDate" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                    <input type="date" wire:model.live="endDate" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Machine</label>
                    <select wire:model.live="selectedMachine" class="form-select">
                        <option value="">All Machines</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $machines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($machine); ?>"><?php echo e($machine); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Product</label>
                    <select wire:model.live="selectedProduct" class="form-select">
                        <option value="">All Products</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($product); ?>"><?php echo e($product); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">Shift</label>
                    <select wire:model.live="selectedShift" class="form-select">
                        <option value="">All Shifts</option>
                        <option value="1">Shift 1</option>
                        <option value="2">Shift 2</option>
                        <option value="3">Shift 3</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <div wire:ignore>
                <div id="paretoChart"></div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            const options = {
                series: [{
                    name: 'Defect Count',
                    type: 'bar',
                    data: [10, 8]
                }, {
                    name: 'Cumulative %',
                    type: 'line',
                    data: [55.56, 100]
                }],
                chart: {
                    height: 350,
                    type: 'line',
                },
                stroke: {
                    width: [0, 4]
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: false,
                    }
                },
                dataLabels: {
                    enabled: true
                },
                xaxis: {
                    categories: ['baret', 'gores']
                },
                yaxis: [{
                    title: {
                        text: 'Defect Count'
                    }
                }, {
                    opposite: true,
                    title: {
                        text: 'Cumulative %'
                    },
                    labels: {
                        formatter: function(val) {
                            return val.toFixed(0) + '%'
                        }
                    }
                }],
                title: {
                    text: 'Pareto Chart - NG Types'
                }
            };

            const chart = new ApexCharts(document.querySelector("#paretoChart"), options);
            chart.render();

            // Listen for updates
            Livewire.on('chartDataUpdated', (data) => {
                const defects = data.map(item => item.defect);
                const counts = data.map(item => item.count);

                chart.updateOptions({
                    xaxis: {
                        categories: defects
                    }
                });

                chart.updateSeries([{
                    name: 'Defect Count',
                    data: counts
                }]);
            });

            // Simplified PDF export handler
            Livewire.on('exportPdf', () => {
                window.location.href = '<?php echo e(route("pareto.pdf")); ?>';
            });

            // Capture chart handler
            Livewire.on('captureChartForPdf', () => {
                const chartElement = document.querySelector("#paretoChart");
                
                html2canvas(chartElement).then(canvas => {
                    const chartImage = canvas.toDataURL('image/png');
                    window.Livewire.find('<?php echo e($_instance->getId()); ?>').saveChartAndExportPdf(chartImage);
                });
            });

            // Add PDF handler
            Livewire.on('openPdfInNewTab', url => {
                window.open(url, '_blank');
            });
        });
    </script>
    <?php $__env->stopPush(); ?>
</div><?php /**PATH /home/abdulazizurrohman/Downloads/11-EzzyIndustri-ftur-PDCA/resources/views/livewire/manajerial/quality/pareto-analysis.blade.php ENDPATH**/ ?>