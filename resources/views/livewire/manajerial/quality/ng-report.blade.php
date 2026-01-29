<div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">NG Report Analysis</h5>
            
            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6>Total NG</h6>
                            <h3>{{ number_format($summary['total_ng']) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6>Average NG Percentage</h6>
                            <h3>{{ number_format($summary['avg_ng_percentage'], 2) }}%</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6>Most Common NG Type</h6>
                            <h3>{{ $summary['most_common_ng']->ng_type ?? 'N/A' }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-2">
                    <input type="date" wire:model.live="startDate" class="form-control" placeholder="Start Date">
                </div>
                <div class="col-md-2">
                    <input type="date" wire:model.live="endDate" class="form-control" placeholder="End Date">
                </div>
                <div class="col-md-2">
                    <select wire:model.live="selectedMachine" class="form-select">
                        <option value="">All Machines</option>
                        @foreach($machines as $machine)
                            <option value="{{ $machine }}">{{ $machine }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="selectedShift" class="form-select">
                        <option value="">All Shifts</option>
                        <option value="1">Shift 1</option>
                        <option value="2">Shift 2</option>
                        <option value="3">Shift 3</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="selectedStatus" class="form-select">
                        <option value="">All Status</option>
                        <option value="diperbaiki">Diperbaiki</option>
                        <option value="scrap">Scrap</option>
                        <option value="rework">Rework</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" wire:model.live="search" class="form-control" placeholder="Search...">
                </div>
            </div>

            <!-- Export Buttons -->
            <div class="d-flex justify-content-end mb-3">
                <button wire:click="exportExcel" class="btn btn-success me-2">
                    <i class="bi bi-file-excel"></i> Export Excel
                </button>
                <button wire:click="exportPdf" class="btn btn-danger" target="_blank">
                    <i class="bi bi-file-pdf"></i> Export PDF
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Machine</th>
                            <th>Product</th>
                            <th>Operator</th>
                            <th>NG Type</th>
                            <th>Total NG</th>
                            <th>NG %</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td>{{ $report->date }}</td>
                                <td>{{ $report->machine_name }}</td>
                                <td>{{ $report->product_name }}</td>
                                <td>{{ $report->operator_name }}</td>
                                <td>{{ $report->ng_type }}</td>
                                <td>{{ $report->total_ng }}</td>
                                <td>{{ $report->ng_percentage }}%</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $report->status === 'diperbaiki' ? 'success' : 
                                        ($report->status === 'pending' ? 'warning' : 
                                        ($report->status === 'scrap' ? 'danger' : 'info')) 
                                    }}">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                                <td>
                                    <button wire:click="viewDetail({{ $report->id }})" class="btn btn-sm btn-info" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($showDetailModal)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">NG Report Detail</h5>
                    <button wire:click="closeModal" type="button" class="btn-close btn-close-white"></button>
                </div>

                <div class="modal-body">
                    @if($selectedReport)
                    <section class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3 fw-semibold text-primary">📦 Production Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <dl class="row">
                                    <dt class="col-sm-5">Date</dt>
                                    <dd class="col-sm-7">{{ $selectedReport->date }}</dd>

                                    <dt class="col-sm-5">Batch Number</dt>
                                    <dd class="col-sm-7">{{ $selectedReport->batch_number }}</dd>

                                    <dt class="col-sm-5">Machine</dt>
                                    <dd class="col-sm-7">{{ $selectedReport->machine_name }}</dd>

                                    <dt class="col-sm-5">Product</dt>
                                    <dd class="col-sm-7">{{ $selectedReport->product_name }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="row">
                                    <dt class="col-sm-5">Operator</dt>
                                    <dd class="col-sm-7">{{ $selectedReport->operator_name }} ({{ $selectedReport->employee_id }})</dd>

                                    <dt class="col-sm-5">Shift</dt>
                                    <dd class="col-sm-7">Shift {{ $selectedReport->shift }}</dd>

                                    <dt class="col-sm-5">Total Production</dt>
                                    <dd class="col-sm-7">{{ number_format($selectedReport->total_production) }} units</dd>
                                </dl>
                            </div>
                        </div>
                    </section>

                    <section class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3 fw-semibold text-danger">❌ NG Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <dl class="row">
                                    <dt class="col-sm-5">NG Type</dt>
                                    <dd class="col-sm-7">{{ $selectedReport->ng_type }}</dd>

                                    @if($selectedReport->ng_type_other)
                                    <dt class="col-sm-5">Other NG Type</dt>
                                    <dd class="col-sm-7">{{ $selectedReport->ng_type_other }}</dd>
                                    @endif

                                    <dt class="col-sm-5">Total NG</dt>
                                    <dd class="col-sm-7">{{ number_format($selectedReport->total_ng) }} units</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="row">
                                    <dt class="col-sm-5">NG Percentage</dt>
                                    <dd class="col-sm-7">{{ $selectedReport->ng_percentage }}%</dd>

                                    <dt class="col-sm-5">Status</dt>
                                    <dd class="col-sm-7">
                                        <span class="badge bg-{{ 
                                            $selectedReport->status === 'diperbaiki' ? 'success' : 
                                            ($selectedReport->status === 'pending' ? 'warning' : 
                                            ($selectedReport->status === 'scrap' ? 'danger' : 'info')) 
                                        }}">
                                            {{ ucfirst($selectedReport->status) }}
                                        </span>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </section>

                    <section class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3 fw-semibold text-secondary">🔍 5W1H Analysis</h6>
                        <dl class="row">
                            <dt class="col-sm-2">What</dt>
                            <dd class="col-sm-10">{{ $selectedReport->what }}</dd>

                            <dt class="col-sm-2">Why</dt>
                            <dd class="col-sm-10">{{ $selectedReport->why }}</dd>

                            <dt class="col-sm-2">Where</dt>
                            <dd class="col-sm-10">{{ $selectedReport->where }}</dd>

                            <dt class="col-sm-2">When</dt>
                            <dd class="col-sm-10">{{ $selectedReport->when }}</dd>

                            <dt class="col-sm-2">Who</dt>
                            <dd class="col-sm-10">{{ $selectedReport->who }}</dd>

                            <dt class="col-sm-2">How</dt>
                            <dd class="col-sm-10">{{ $selectedReport->how }}</dd>
                        </dl>
                    </section>

                    <section class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3 fw-semibold text-success">🛠️ Action Taken</h6>
                        <dl class="row">
                            <dt class="col-sm-3">Countermeasure</dt>
                            <dd class="col-sm-9">{{ $selectedReport->countermeasure }}</dd>

                            <dt class="col-sm-3">Preventive Action</dt>
                            <dd class="col-sm-9">{{ $selectedReport->preventive_action }}</dd>

                            <dt class="col-sm-3">PIC</dt>
                            <dd class="col-sm-9">{{ $selectedReport->pic }}</dd>

                            @if($selectedReport->verified_by)
                            <dt class="col-sm-3">Verified By</dt>
                            <dd class="col-sm-9">{{ $selectedReport->verified_by }}</dd>
                            @endif
                        </dl>
                    </section>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('openPdfInNewTab', ({url}) => {
            window.open(url, '_blank');
        });
    });
</script>
@endpush