<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">PDCA Project Detail: {{ $project->title }}</h5>
            <button wire:click="back" class="btn btn-secondary">Back</button>
        </div>
        <div class="card-body">
            <!-- Project Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6>Project Information</h6>
                    <table class="table table-sm">
                        <tr>
                            <th width="30%">Status</th>
                            <td>
                                <span class="badge bg-{{ $project->status === 'completed' ? 'success' : 
                                    ($project->status === 'on_hold' ? 'warning' : 'primary') }}">
                                    {{ ucfirst($project->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Start Date</th>
                            <td>{{ $project->start_date->format('Y-m-d') }}</td>
                        </tr>
                        <tr>
                            <th>Target Date</th>
                            <td>{{ $project->target_date->format('Y-m-d') }}</td>
                        </tr>
                        <tr>
                            <th>Created By</th>
                            <td>{{ $project->created_by }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Description</h6>
                    <p>{{ $project->description }}</p>
                </div>
            </div>

            <!-- Progress Overview -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <h6>Progress Overview</h6>
                    <div class="progress" style="height: 25px;">
                        @php
                            $total = $project->actions->count();
                            $completed = $project->actions->where('status', 'completed')->count();
                            $progress = $total > 0 ? ($completed / $total) * 100 : 0;
                        @endphp
                        <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%">
                            {{ number_format($progress, 1) }}%
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions by Phase -->
            @foreach(['plan', 'do', 'check', 'act'] as $phase)
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">{{ ucfirst($phase) }} Phase</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Detail</th>
                                        <th>PIC</th>
                                        <th>Target Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($project->actions->where('phase', $phase) as $action)
                                        <tr>
                                            <td>{{ $action->action }}</td>
                                            <td>{{ $action->detail }}</td>
                                            <td>{{ $action->pic }}</td>
                                            <td>{{ $action->target_date }}</td>
                                            <td>
                                                <span class="badge bg-{{ $action->status === 'completed' ? 'success' : 
                                                    ($action->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($action->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <button wire:click="updateActionStatus({{ $action->id }})" 
                                                        class="btn btn-sm btn-primary">
                                                    Update Status
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>