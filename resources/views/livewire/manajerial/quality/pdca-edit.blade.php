<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">Edit PDCA Project</h5>
            <a href="{{ route('pdca.detail', ['id' => $projectId]) }}" class="btn btn-secondary">Back</a>
        </div>
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form wire:submit.prevent="update">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" wire:model="title">
                        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" wire:model="status">
                            <option value="draft">Draft</option>
                            <option value="planning">Planning</option>
                            <option value="implementation">Implementation</option>
                            <option value="evaluation">Evaluation</option>
                            <option value="completed">Completed</option>
                            <option value="on_hold">On Hold</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" wire:model="description" rows="3"></textarea>
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" wire:model="startDate">
                        @error('startDate') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Target Date</label>
                        <input type="date" class="form-control" wire:model="targetDate">
                        @error('targetDate') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- PDCA Actions -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5>PDCA Actions</h5>

                        <!-- Plan Actions -->
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <span>Plan</span>
                                <button type="button" class="btn btn-light btn-sm" wire:click="addAction('plan')">Add Action</button>
                            </div>
                            <div class="card-body">
                                @foreach($actions['plan'] as $index => $action)
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <input type="text" wire:model="actions.plan.{{ $index }}.action" class="form-control" placeholder="Action">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" wire:model="actions.plan.{{ $index }}.detail" class="form-control" placeholder="Detail">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" wire:model="actions.plan.{{ $index }}.pic" class="form-control" placeholder="PIC">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" wire:model="actions.plan.{{ $index }}.target_date" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm" wire:click="removeAction('plan', {{ $index }})">Remove</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Do Actions -->
                        <div class="card mb-3">
                            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                <span>Do</span>
                                <button type="button" class="btn btn-light btn-sm" wire:click="addAction('do')">Add Action</button>
                            </div>
                            <div class="card-body">
                                @foreach($actions['do'] as $index => $action)
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <input type="text" wire:model="actions.do.{{ $index }}.action" class="form-control" placeholder="Action">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" wire:model="actions.do.{{ $index }}.detail" class="form-control" placeholder="Detail">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" wire:model="actions.do.{{ $index }}.pic" class="form-control" placeholder="PIC">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" wire:model="actions.do.{{ $index }}.target_date" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm" wire:click="removeAction('do', {{ $index }})">Remove</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Check Actions -->
                        <div class="card mb-3">
                            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                <span>Check</span>
                                <button type="button" class="btn btn-light btn-sm" wire:click="addAction('check')">Add Action</button>
                            </div>
                            <div class="card-body">
                                @foreach($actions['check'] as $index => $action)
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <input type="text" wire:model="actions.check.{{ $index }}.action" class="form-control" placeholder="Action">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" wire:model="actions.check.{{ $index }}.detail" class="form-control" placeholder="Detail">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" wire:model="actions.check.{{ $index }}.pic" class="form-control" placeholder="PIC">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" wire:model="actions.check.{{ $index }}.target_date" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm" wire:click="removeAction('check', {{ $index }})">Remove</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Act Actions -->
                        <div class="card mb-3">
                            <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                                <span>Act</span>
                                <button type="button" class="btn btn-light btn-sm" wire:click="addAction('act')">Add Action</button>
                            </div>
                            <div class="card-body">
                                @foreach($actions['act'] as $index => $action)
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <input type="text" wire:model="actions.act.{{ $index }}.action" class="form-control" placeholder="Action">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" wire:model="actions.act.{{ $index }}.detail" class="form-control" placeholder="Detail">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" wire:model="actions.act.{{ $index }}.pic" class="form-control" placeholder="PIC">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" wire:model="actions.act.{{ $index }}.target_date" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm" wire:click="removeAction('act', {{ $index }})">Remove</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Update PDCA Project</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>