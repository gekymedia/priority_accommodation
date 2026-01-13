@extends('layouts.app')

@section('title', 'Audit Logs - Priority Accommodations')

@section('content')
<div class="page-header">
    <h1 class="page-title">Audit Logs</h1>
    <div class="btn-group">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Dashboard
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">System Activities</h3>
    </div>
    <div class="card-body">
        @if($auditLogs->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Entity</th>
                        <th>Entity ID</th>
                        <th>Timestamp</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($auditLogs as $log)
                    <tr>
                        <td>#{{ $log->id }}</td>
                        <td>{{ $log->user->name ?? 'System' }}</td>
                        <td>
                            <span class="badge badge-{{ $log->action === 'created' ? 'success' : ($log->action === 'updated' ? 'warning' : 'danger') }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td>{{ $log->entity }}</td>
                        <td>#{{ $log->entity_id }}</td>
                        <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.audit-logs.show', $log->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $auditLogs->links() }}
        </div>
        @else
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-history text-4xl mb-4"></i>
            <p>No audit logs found</p>
        </div>
        @endif
    </div>
</div>
@endsection