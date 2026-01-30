<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title">Pareto Analysis - NG Types</h5>
                <div>
                    <button wire:click="refreshData" class="btn btn-primary">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </button>
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
                        @foreach($machines as $machine)
                            <option value="{{ $machine }}">{{ $machine }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Product</label>
                    <select wire:model.live="selectedProduct" class="form-select">
                        <option value="">All Products</option>
                        @foreach($products as $product)
                            <option value="{{ $product }}">{{ $product }}</option>
                        @endforeach
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

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        let chartInstance = null;
        
        function initializeChart(data) {
            if (!data || data.length === 0) {
                data = [{ defect: 'No Data', count: 0 }];
            }
            
            const defects = data.map(item => item.defect);
            const counts = data.map(item => item.count);
            
            // Calculate cumulative percentages
            const totalCount = counts.reduce((sum, count) => sum + count, 0);
            let cumulativeCount = 0;
            const cumulativePercentages = counts.map(count => {
                cumulativeCount += count;
                return totalCount > 0 ? (cumulativeCount / totalCount) * 100 : 0;
            });
            
            const options = {
                series: [{
                    name: 'Defect Count',
                    type: 'bar',
                    data: counts
                }, {
                    name: 'Cumulative %',
                    type: 'line',
                    data: cumulativePercentages
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
                    categories: defects
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

            const chartElement = document.querySelector("#paretoChart");
            
            if (chartInstance) {
                chartInstance.destroy();
            }
            
            chartInstance = new ApexCharts(chartElement, options);
            chartInstance.render();
        }
        
        document.addEventListener('livewire:initialized', () => {
            // Initialize chart with current data
            @this.call('getChartData').then(result => {
                initializeChart(result);
            });

            // Listen for updates
            Livewire.on('chartDataUpdated', (data) => {
                initializeChart(data);
            });

            // Add URL update handler
            Livewire.on('urlUpdated', (queryString) => {
                const newUrl = window.location.pathname + queryString;
                window.history.replaceState({}, '', newUrl);
            });

            // PDF export handler
            Livewire.on('exportPdf', () => {
                const params = new URLSearchParams({
                    start_date: @this.startDate,
                    end_date: @this.endDate,
                    machine: @this.selectedMachine || '',
                    product: @this.selectedProduct || '',
                    shift: @this.selectedShift || ''
                });
                window.open(`{{ route('pareto.pdf') }}?${params.toString()}`, '_blank');
            });

            // Capture chart handler
            Livewire.on('captureChartForPdf', () => {
                const chartElement = document.querySelector("#paretoChart");
                
                html2canvas(chartElement).then(canvas => {
                    const chartImage = canvas.toDataURL('image/png');
                    @this.saveChartAndExportPdf(chartImage);
                });
            });

            // Add PDF handler
            Livewire.on('openPdfInNewTab', url => {
                window.open(url, '_blank');
            });
        });
    </script>
    @endpush
</div>