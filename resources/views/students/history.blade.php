@extends('layouts.app')

@section('title', $student->name . ' - Booking History - Priority Accommodations')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="page-title">Booking History</h1>
            <p class="page-subtitle">for {{ $student->name }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.students.show', $student) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back to Student
            </a>
        </div>
    </div>
</div>

<!-- Student Info Card -->
<div class="card mb-6">
    <div class="card-body">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="info-group">
                <label class="info-label">Student ID</label>
                <div class="info-value">{{ $student->student_id }}</div>
            </div>
            <div class="info-group">
                <label class="info-label">Email</label>
                <div class="info-value">{{ $student->email }}</div>
            </div>
            <div class="info-group">
                <label class="info-label">Phone</label>
                <div class="info-value">{{ $student->phone }}</div>
            </div>
            <div class="info-group">
                <label class="info-label">University</label>
                <div class="info-value">{{ $student->university }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Booking History -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Booking History ({{ $bookings->total() }})</h3>
    </div>
    <div class="card-body">
        @if($bookings->count() > 0)
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Room</th>
                            <th>Hostel</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr class="table-row-hover">
                            <td>
                                <strong>#{{ $booking->id }}</strong>
                                <div class="text-sm text-gray-500">
                                    {{ $booking->created_at->format('M d, Y') }}
                                </div>
                            </td>
                            <td>
                                @if($booking->room)
                                    <div class="font-medium">{{ $booking->room->room_number }}</div>
                                    <div class="text-sm text-gray-500 capitalize">{{ $booking->room->type }}</div>
                                @else
                                    <span class="text-gray-500">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->room && $booking->room->hostel)
                                    {{ $booking->room->hostel->name }}
                                @else
                                    <span class="text-gray-500">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->check_in)
                                    {{ $booking->check_in->format('M d, Y') }}
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->check_out)
                                    {{ $booking->check_out->format('M d, Y') }}
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'orange',
                                        'confirmed' => 'blue',
                                        'checked_in' => 'green',
                                        'checked_out' => 'gray',
                                        'cancelled' => 'red'
                                    ][$booking->status] ?? 'gray';
                                @endphp
                                <span class="status {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </td>
                            <td class="font-medium text-green-600">
                                ₵{{ number_format($booking->total_amount, 2) }}
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" 
                                       class="btn btn-sm btn-secondary" 
                                       title="View Booking">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($booking->room)
                                    <a href="{{ route('admin.rooms.show', $booking->room) }}" 
                                       class="btn btn-sm btn-primary" 
                                       title="View Room">
                                        <i class="fas fa-door-closed"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-calendar-times text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No booking history</h3>
                <p class="text-gray-500 mb-6">This student hasn't made any bookings yet.</p>
                <a href="{{ route('admin.bookings.create', ['student_id' => $student->id]) }}" 
                   class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Create First Booking
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Booking Statistics -->
@if($bookings->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $bookings->whereIn('status', ['confirmed', 'checked_in'])->count() }}</div>
                <div class="stat-label">Active Bookings</div>
            </div>
            <div class="stat-icon blue">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $bookings->where('status', 'checked_out')->count() }}</div>
                <div class="stat-label">Completed</div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $bookings->where('status', 'cancelled')->count() }}</div>
                <div class="stat-label">Cancelled</div>
            </div>
            <div class="stat-icon red">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">₵{{ number_format($bookings->sum('total_amount'), 2) }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
    </div>
</div>
@endif

<style>
.info-group {
    margin-bottom: 0;
}

.info-label {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.info-value {
    font-size: 0.875rem;
    color: var(--gray-900);
    font-weight: 500;
}

.status {
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status.blue {
    background: rgba(67, 97, 238, 0.1);
    color: var(--primary);
}

.status.green {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
}

.status.orange {
    background: rgba(248, 150, 30, 0.1);
    color: var(--warning);
}

.status.red {
    background: rgba(247, 37, 133, 0.1);
    color: var(--danger);
}

.status.gray {
    background: rgba(108, 117, 125, 0.1);
    color: var(--gray);
}

.page-subtitle {
    color: var(--gray-600);
    font-size: 0.875rem;
    margin-top: 0.25rem;
}
</style>
@endsection