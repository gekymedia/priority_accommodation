@extends('layouts.app')

@section('title', 'Complaint #' . $complaint->id . ' - Priority Accommodations')

@section('content')
<div class="page-header">
    <h1 class="page-title">Complaint #{{ $complaint->id }}</h1>
    <div class="page-actions">
        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin'))
            <a href="{{ route('admin.complaints.edit', $complaint) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
                Manage Complaint
            </a>
        @endif
        <a href="{{ route('admin.complaints.my-complaints') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to My Complaints
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Complaint Details -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Complaint Details</h3>
                <div class="flex gap-2">
                    <span class="badge badge-{{ $complaint->status_color }}">
                        {{ \App\Models\Complaint::getStatuses()[$complaint->status] }}
                    </span>
                    <span class="badge badge-{{ $complaint->priority_color }}">
                        {{ \App\Models\Complaint::getPriorities()[$complaint->priority] }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="info-group mb-4">
                    <label class="info-label">Subject</label>
                    <div class="info-value">{{ $complaint->subject }}</div>
                </div>

                <div class="info-group mb-4">
                    <label class="info-label">Description</label>
                    <div class="info-value">{{ $complaint->description }}</div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="info-group">
                        <label class="info-label">Category</label>
                        <div class="info-value">
                            <span class="badge badge-secondary">
                                {{ \App\Models\Complaint::getCategories()[$complaint->category] }}
                            </span>
                        </div>
                    </div>
                    <div class="info-group">
                        <label class="info-label">Priority</label>
                        <div class="info-value">
                            <span class="badge badge-{{ $complaint->priority_color }}">
                                {{ \App\Models\Complaint::getPriorities()[$complaint->priority] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Response -->
        @if($complaint->admin_response)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Admin Response</h3>
            </div>
            <div class="card-body">
                <div class="info-group">
                    <div class="info-value">{{ $complaint->admin_response }}</div>
                </div>
                @if($complaint->resolvedBy)
                <div class="mt-3 text-sm text-muted">
                    Resolved by: {{ $complaint->resolvedBy->name }} 
                    @if($complaint->resolved_at)
                        on {{ $complaint->resolved_at->format('M d, Y h:i A') }}
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Student Information -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Student Information</h3>
            </div>
            <div class="card-body">
                <div class="info-group">
                    <label class="info-label">Name</label>
                    <div class="info-value">{{ $complaint->student->name }}</div>
                </div>
                <div class="info-group">
                    <label class="info-label">Email</label>
                    <div class="info-value">{{ $complaint->student->email }}</div>
                </div>
                <div class="info-group">
                    <label class="info-label">Phone</label>
                    <div class="info-value">{{ $complaint->student->phone ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <!-- Related Information -->
        @if($complaint->booking || $complaint->room || $complaint->hostel)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Related Information</h3>
            </div>
            <div class="card-body">
                @if($complaint->booking)
                <div class="info-group">
                    <label class="info-label">Booking</label>
                    <div class="info-value">
                        <a href="{{ route('admin.bookings.show', $complaint->booking) }}">
                            Booking #{{ $complaint->booking->id }}
                        </a>
                    </div>
                </div>
                @endif
                @if($complaint->room)
                <div class="info-group">
                    <label class="info-label">Room</label>
                    <div class="info-value">{{ $complaint->room->name }}</div>
                </div>
                @endif
                @if($complaint->hostel)
                <div class="info-group">
                    <label class="info-label">Hostel</label>
                    <div class="info-value">{{ $complaint->hostel->name }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Complaint Timeline -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Timeline</h3>
            </div>
            <div class="card-body">
                <div class="info-group">
                    <label class="info-label">Submitted</label>
                    <div class="info-value">{{ $complaint->created_at->format('M d, Y h:i A') }}</div>
                </div>
                @if($complaint->assignedTo)
                <div class="info-group">
                    <label class="info-label">Assigned To</label>
                    <div class="info-value">{{ $complaint->assignedTo->name }}</div>
                </div>
                @endif
                @if($complaint->resolved_at)
                <div class="info-group">
                    <label class="info-label">Resolved</label>
                    <div class="info-value">{{ $complaint->resolved_at->format('M d, Y h:i A') }}</div>
                </div>
                <div class="info-group">
                    <label class="info-label">Days Open</label>
                    <div class="info-value">{{ $complaint->days_open }} days</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

