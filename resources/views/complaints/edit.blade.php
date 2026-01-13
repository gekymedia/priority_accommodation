@extends('layouts.app')

@section('title', 'Manage Complaint #' . $complaint->id . ' - Priority Accommodations')

@section('content')
<div class="page-header">
    <h1 class="page-title">Manage Complaint #{{ $complaint->id }}</h1>
    <a href="{{ route('admin.complaints.show', $complaint) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Back to Complaint
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Complaint Information</h3>
    </div>
    <div class="card-body">
        <div class="mb-4">
            <h4>{{ $complaint->subject }}</h4>
            <p class="text-muted">{{ $complaint->description }}</p>
            <div class="mt-2">
                <span class="badge badge-{{ $complaint->status_color }}">
                    {{ \App\Models\Complaint::getStatuses()[$complaint->status] }}
                </span>
                <span class="badge badge-{{ $complaint->priority_color }}">
                    {{ \App\Models\Complaint::getPriorities()[$complaint->priority] }}
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.complaints.update', $complaint) }}">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select id="status" 
                            name="status" 
                            class="form-control @error('status') is-invalid @enderror" 
                            required>
                        @foreach(\App\Models\Complaint::getStatuses() as $value => $label)
                            <option value="{{ $value }}" {{ old('status', $complaint->status) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                    <select id="priority" 
                            name="priority" 
                            class="form-control @error('priority') is-invalid @enderror" 
                            required>
                        @foreach(\App\Models\Complaint::getPriorities() as $value => $label)
                            <option value="{{ $value }}" {{ old('priority', $complaint->priority) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('priority')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="assigned_to" class="form-label">Assign To</label>
                <select id="assigned_to" 
                        name="assigned_to" 
                        class="form-control @error('assigned_to') is-invalid @enderror">
                    <option value="">Unassigned</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}" {{ old('assigned_to', $complaint->assigned_to) == $admin->id ? 'selected' : '' }}>
                            {{ $admin->name }}
                        </option>
                    @endforeach
                </select>
                @error('assigned_to')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="admin_response" class="form-label">
                    Admin Response
                    @if(in_array(request('status', $complaint->status), ['resolved', 'rejected']))
                        <span class="text-danger">*</span>
                    @endif
                </label>
                <textarea id="admin_response" 
                          name="admin_response" 
                          class="form-control @error('admin_response') is-invalid @enderror" 
                          rows="5" 
                          placeholder="Enter your response or resolution details...">{{ old('admin_response', $complaint->admin_response) }}</textarea>
                @error('admin_response')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">
                    Required when resolving or rejecting a complaint.
                </small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Complaint
                </button>
                <a href="{{ route('admin.complaints.show', $complaint) }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

