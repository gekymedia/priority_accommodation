@extends('layouts.app')

@section('title', 'My Complaints - Priority Accommodations')

@section('content')
<div class="page-header">
    <h1 class="page-title">My Complaints</h1>
    <a href="{{ route('complaints.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        Submit New Complaint
    </a>
</div>

@if($complaints->count() > 0)
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($complaints as $complaint)
                    <tr>
                        <td>
                            <strong>{{ $complaint->subject }}</strong>
                            @if($complaint->room)
                                <br><small class="text-muted">{{ $complaint->room->name }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-secondary">
                                {{ \App\Models\Complaint::getCategories()[$complaint->category] }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $complaint->priority_color }}">
                                {{ \App\Models\Complaint::getPriorities()[$complaint->priority] }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $complaint->status_color }}">
                                {{ \App\Models\Complaint::getStatuses()[$complaint->status] }}
                            </span>
                        </td>
                        <td>{{ $complaint->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('complaints.show', $complaint) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $complaints->links() }}
        </div>
    </div>
</div>
@else
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
        <h4>No Complaints Yet</h4>
        <p class="text-muted">You haven't submitted any complaints.</p>
        <a href="{{ route('complaints.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Submit Your First Complaint
        </a>
    </div>
</div>
@endif
@endsection

