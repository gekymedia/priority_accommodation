@extends('layouts.app')

@section('title', 'Audit Log Details - Priority Accommodations')

@section('content')
<div class="page-header">
    <h1 class="page-title">Audit Log Details</h1>
    <div class="btn-group">
        <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Logs
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Log Information</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Log ID:</strong>
                        <p>#{{ $auditLog->id }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>User:</strong>
                        <p>{{ $auditLog->user->name ?? 'System' }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Action:</strong>
                        <p>
                            <span class="badge badge-{{ $auditLog->action === 'created' ? 'success' : ($auditLog->action === 'updated' ? 'warning' : 'danger') }}">
                                {{ ucfirst($auditLog->action) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <strong>Entity Type:</strong>
                        <p>{{ $auditLog->entity }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Entity ID:</strong>
                        <p>#{{ $auditLog->entity_id }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Timestamp:</strong>
                        <p>{{ $auditLog->created_at->format('M d, Y H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection