<div>
    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm hover-shadow" style="background: linear-gradient(135deg, #0d6efd, #0099ff);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="card-title text-white text-uppercase fw-bold mb-3">Total NG Reports</h6>
                            <h3 class="text-white fw-bold mb-0">{{ $summary['total_reports'] }}</h3>
                            <div class="text-white-50 mt-2 small">Last 30 days</div>
                        </div>
                        <div class="icon-shape bg-white bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-file-text fs-1 text-white opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm hover-shadow" style="background: linear-gradient(135deg, #198754, #00b074);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="card-title text-white text-uppercase fw-bold mb-3">Average NG Rate</h6>
                            <h3 class="text-white fw-bold mb-0">{{ number_format($summary['avg_ng_rate'], 2) }}%</h3>
                            <div class="text-white-50 mt-2 small">Overall average</div>
                        </div>
                        <div class="icon-shape bg-white bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-percent fs-1 text-white opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm hover-shadow" style="background: linear-gradient(135deg, #ffc107, #ffb142);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="card-title text-dark text-uppercase fw-bold mb-3">Most Common NG</h6>
                            <h3 class="text-dark fw-bold mb-0">{{ $summary['most_common_ng'] }}</h3>
                            <div class="text-dark-50 mt-2 small">Highest occurrence</div>
                        </div>
                        <div class="icon-shape bg-dark bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-exclamation-triangle fs-1 text-dark opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm hover-shadow" style="background: linear-gradient(135deg, #0dcaf0, #00e1ff);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="card-title text-dark text-uppercase fw-bold mb-3">Total Analyses</h6>
                            <h3 class="text-dark fw-bold mb-0">{{ $summary['total_analyses'] }}</h3>
                            <div class="text-dark-50 mt-2 small">Fishbone + Pareto</div>
                        </div>
                        <div class="icon-shape bg-dark bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-graph-up fs-1 text-dark opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .hover-shadow:hover {
        transform: translateY(-3px);
        transition: all 0.3s ease;
    }
    .icon-shape {
        transition: all 0.3s ease;
    }
    .card:hover .icon-shape {
        transform: scale(1.1);
    }
    </style>

    <!-- Add this before Charts Row -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Date Range</label>
                            <select class="form-select" wire:model.live="dateRange">
                                <option value="7">Last 7 Days</option>
                                <option value="30">Last 30 Days</option>
                                <option value="90">Last 90 Days</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        @if($dateRange === 'custom')
                        <div class="col-md-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" wire:model.live="startDate">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" wire:model.live="endDate">
                        </div>
                        @endif
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary" wire:click="refreshData">
                                <i class="bi bi-arrow-clockwise"></i> Update Charts
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">NG Rate Trend</h5>
                </div>
                <div class="card-body">
                    <div wire:ignore>
                        <div id="ngTrendChart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">NG Type Distribution</h5>
                </div>
                <div class="card-body">
                    <div wire:ignore>
                        <div id="ngDistributionChart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row g-3">
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="bi bi-clipboard-data me-2 text-primary"></i>Recent NG Reports
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 px-4">Date</th>
                                    <th class="py-3">Machine</th>
                                    <th class="py-3">NG Type</th>
                                    <th class="py-3 pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentReports as $report)
                                <tr>
                                    <td class="py-3 px-4">{{ $report->date }}</td>
                                    <td class="py-3">{{ $report->machine_name }}</td>
                                    <td class="py-3">{{ $report->ng_type }}</td>
                                    <td class="py-3 pe-4">
                                        @php
                                                $statusClass = match($report->status_color) {
                                                    'success' => 'bg-success',
                                                    'danger' => 'bg-danger',
                                                    'warning' => 'bg-warning text-dark',
                                                    'info' => 'bg-info text-dark',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge rounded-pill {{ $statusClass }}">
                                                {{ $report->status }}
                                            </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="bi bi-graph-up me-2 text-success"></i>Recent Analyses
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 px-4">Date</th>
                                    <th class="py-3">Type</th>
                                    <th class="py-3">Title</th>
                                    <th class="py-3 pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAnalyses as $analysis)
                                <tr>
                                    <td class="py-3 px-4">{{ date('Y-m-d', strtotime($analysis['date'])) }}</td>
                                    <td class="py-3">{{ $analysis['type'] }}</td>
                                    <td class="py-3">{{ $analysis['title'] }}</td>
                                    <td class="py-3 pe-4">
                                        <a href="{{ $analysis['url'] }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ngDetailModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">NG Type Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div wire:loading.remove wire:target="loadNGDetails">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Machine</th>
                                        <th>NG Count</th>
                                        <th>NG Rate</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($ngDetails) > 0)
                                        @foreach($ngDetails as $detail)
                                        <tr>
                                            <td>{{ $detail->date }}</td>
                                            <td>{{ $detail->machine_name }}</td>
                                            <td>{{ $detail->ng_count }}</td>
                                            <td>{{ number_format($detail->ng_percentage, 2) }}%</td>
                                            <td>
                                                <a href="{{ route('manajerial.quality.ng-report') }}?id={{ $detail->id }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">No records found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            console.log('Livewire initialized');
            
            Livewire.on('chartDataUpdated', (eventData) => {
                console.log('Event received with data:', eventData);
                
                // Data comes as array, get first element
                const data = eventData[0];
                console.log('Processed data:', data);
    
                try {
                    initNGTrendChart(data);
                } catch (error) {
                    console.error('Error in trend chart:', error);
                }
    
                try {
                    initNGDistributionChart(data);
                } catch (error) {
                    console.error('Error in distribution chart:', error);
                }
            });
        });
    
        function initNGTrendChart(data) {
            console.log('Starting trend chart init with:', data.trend);
            
            if (window.trendChart) {
                window.trendChart.destroy();
            }
    
            const trendOptions = {
                series: [{
                    name: 'NG Rate (%)',
                    data: data.trend.map(item => parseFloat(item.avg_ng_rate))
                }],
                chart: {
                    type: 'line',
                    height: 300,
                    toolbar: {
                        show: true
                    }
                },
                xaxis: {
                    categories: data.trend.map(item => item.date)
                },
                yaxis: {
                    title: {
                        text: 'NG Rate (%)'
                    }
                }
            };
    
            window.trendChart = new ApexCharts(document.querySelector("#ngTrendChart"), trendOptions);
            window.trendChart.render();
        }
    
        function initNGDistributionChart(data) {
            console.log('Starting distribution chart init with:', data.distribution);
            
            if (window.distributionChart) {
                window.distributionChart.destroy();
            }
    
            const distributionOptions = {
                series: data.distribution.map(item => item.count),
                chart: {
                type: 'pie',
                height: 300,
                events: {
                    dataPointSelection: function(event, chartContext, config) {
                        const selectedData = data.distribution[config.dataPointIndex];
                        console.log('Data yang dipilih:', selectedData);
                        
                        // Ubah cara dispatch event
                        @this.loadNGDetails({
                            ngType: selectedData.ng_type
                        });
                        
                        console.log('Event telah dikirim dengan data:', {
                            ngType: selectedData.ng_type
                        });
                        
                        setTimeout(() => {
                            const modalElement = document.getElementById('ngDetailModal');
                            const modal = new bootstrap.Modal(modalElement);
                            modal.show();
                        }, 100);
                    }
                }
                },
                labels: data.distribution.map(item => item.ng_type),
                legend: {
                    position: 'bottom'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%'
                        }
                    }
                }
            };
    
            window.distributionChart = new ApexCharts(document.querySelector("#ngDistributionChart"), distributionOptions);
            window.distributionChart.render();
        }
    </script>
    @endpush
</div>
