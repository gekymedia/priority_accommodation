@extends('layouts.app')

@section('title', 'Dashboard - Priority Accommodations')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard <span class="page-title-highlight">Overview</span></h1>
        <p class="text-gray-500 text-sm mt-1">Welcome back! Here's what's happening with your hostels.</p>
    </div>
    <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        New Booking
    </a>
</div>

<!-- Main Stats Grid -->
<div class="stats-grid">
    <!-- Total Rooms -->
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $totalRooms ?? 0 }}</div>
                <div class="stat-label">Total Rooms</div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-building"></i>
            </div>
        </div>
    </div>

    <!-- Occupied Rooms -->
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $occupiedRooms ?? 0 }}</div>
                <div class="stat-label">Occupied Rooms</div>
            </div>
            <div class="stat-icon gold">
                <i class="fas fa-bed"></i>
            </div>
        </div>
    </div>

    <!-- Available Rooms -->
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $availableRooms ?? 0 }}</div>
                <div class="stat-label">Available Rooms</div>
            </div>
            <div class="stat-icon blue">
                <i class="fas fa-door-open"></i>
            </div>
        </div>
    </div>

    <!-- Maintenance Rooms -->
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $maintenanceRooms ?? 0 }}</div>
                <div class="stat-label">Under Maintenance</div>
            </div>
            <div class="stat-icon orange">
                <i class="fas fa-tools"></i>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Stats -->
<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
    <!-- Occupancy Rate -->
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value" style="color: var(--primary);">{{ $occupancyRate ?? 0 }}%</div>
                <div class="stat-label">Occupancy Rate</div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>

    <!-- Total Students -->
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $totalStudents ?? 0 }}</div>
                <div class="stat-label">Total Students</div>
            </div>
            <div class="stat-icon gold">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
    </div>

    <!-- Active Students -->
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $activeStudents ?? 0 }}</div>
                <div class="stat-label">Active Residents</div>
            </div>
            <div class="stat-icon blue">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue -->
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value" style="color: var(--primary);">₵{{ number_format($revenue ?? 0, 2) }}</div>
                <div class="stat-label">Monthly Revenue</div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
    </div>

    <!-- Pending Bookings -->
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $pendingBookings ?? 0 }}</div>
                <div class="stat-label">Pending Bookings</div>
            </div>
            <div class="stat-icon orange">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>

    <!-- Today's Bookings -->
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $todayBookings ?? 0 }}</div>
                <div class="stat-label">Today's Bookings</div>
            </div>
            <div class="stat-icon blue">
                <i class="fas fa-calendar-day"></i>
            </div>
        </div>
    </div>
</div>

<!-- Pending Booking Requests Alert (for Hostel Owners) -->
@if(Auth::user()->isHostelOwner() || Auth::user()->role === 'admin')
    @php
        $pendingRequests = \App\Models\BookingRequest::whereIn('hostel_id', Auth::user()->hostels()->pluck('id'))
            ->where('status', 'awaiting_confirmation')
            ->count();
    @endphp
    @if($pendingRequests > 0)
    <div class="mb-6">
        <div class="alert-card-urgent">
            <div class="alert-card-content">
                <div class="alert-card-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="alert-card-text">
                    <h3>{{ $pendingRequests }} Booking Request(s) Awaiting Confirmation</h3>
                    <p>Students have paid and are waiting for room confirmation. Please respond quickly!</p>
                </div>
                <a href="{{ route('hostel.booking-requests') }}" class="btn btn-accent">
                    <i class="fas fa-eye"></i>
                    View Requests
                </a>
            </div>
        </div>
    </div>
    @endif
@endif

<div class="content-grid">
    <!-- Recent Bookings -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-calendar-check"></i>
                Recent Bookings
            </h3>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.8rem;">
                View All
            </a>
        </div>
        <div class="card-body">
            @if(isset($recentBookings) && $recentBookings->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Room</th>
                            <th>Check-in</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentBookings as $booking)
                        <tr>
                            <td><strong>#{{ $booking->id }}</strong></td>
                            <td>{{ $booking->student->name ?? 'N/A' }}</td>
                            <td>{{ $booking->room->room_number ?? 'N/A' }}</td>
                            <td>{{ $booking->check_in ? $booking->check_in->format('M d, Y') : 'N/A' }}</td>
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
                                    <i class="fas fa-circle" style="font-size: 6px;"></i>
                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <p>No recent bookings found</p>
                <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    <i class="fas fa-plus"></i>
                    Create First Booking
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-history"></i>
                Recent Activities
            </h3>
            <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.8rem;">
                View All
            </a>
        </div>
        <div class="card-body">
            @if(isset($recentActivities) && count($recentActivities) > 0)
            <div class="activity-list">
                @foreach($recentActivities as $activity)
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="activity-content">
                        <p class="activity-text">
                            <strong>
                                @if($activity->user)
                                    {{ $activity->user->name }} 
                                @else
                                    System
                                @endif
                            </strong>
                            {{ $activity->action }} {{ $activity->entity }} #{{ $activity->entity_id }}
                        </p>
                        <span class="activity-time">
                            {{ $activity->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-history"></i>
                <p>No recent activities</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card mt-6">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-bolt"></i>
            Quick Actions
        </h3>
    </div>
    <div class="card-body">
        <div class="quick-actions-grid">
            <a href="{{ route('admin.bookings.create') }}" class="quick-action-btn">
                <div class="quick-action-icon green">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <span>New Booking</span>
            </a>
            <a href="{{ route('admin.students.create') }}" class="quick-action-btn">
                <div class="quick-action-icon gold">
                    <i class="fas fa-user-plus"></i>
                </div>
                <span>Add Student</span>
            </a>
            <a href="{{ route('admin.rooms.create') }}" class="quick-action-btn">
                <div class="quick-action-icon blue">
                    <i class="fas fa-door-closed"></i>
                </div>
                <span>Add Room</span>
            </a>
            <a href="{{ route('admin.payments.create') }}" class="quick-action-btn">
                <div class="quick-action-icon green">
                    <i class="fas fa-money-bill"></i>
                </div>
                <span>Record Payment</span>
            </a>
            <a href="{{ route('admin.hostels.create') }}" class="quick-action-btn">
                <div class="quick-action-icon gold">
                    <i class="fas fa-hotel"></i>
                </div>
                <span>Add Hostel</span>
            </a>
            <a href="{{ route('admin.reports') }}" class="quick-action-btn">
                <div class="quick-action-icon blue">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <span>View Reports</span>
            </a>
        </div>
    </div>
</div>

<style>
/* Alert Card Urgent */
.alert-card-urgent {
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    box-shadow: 0 8px 25px rgba(252, 209, 22, 0.3);
}

.alert-card-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.alert-card-icon {
    width: 60px;
    height: 60px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.alert-card-icon i {
    font-size: 1.75rem;
    color: var(--dark);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.alert-card-text {
    flex: 1;
    min-width: 200px;
}

.alert-card-text h3 {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 0.25rem;
}

.alert-card-text p {
    color: rgba(0,0,0,0.7);
    font-size: 0.9rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: var(--gray-500);
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: var(--gray-300);
}

.empty-state p {
    font-size: 1rem;
    font-weight: 500;
}

/* Activity List */
.activity-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 0.875rem;
    padding: 0.75rem;
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.activity-item:hover {
    background: var(--gray-50);
}

.activity-icon {
    width: 36px;
    height: 36px;
    background: var(--primary-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.activity-icon i {
    color: var(--primary);
    font-size: 0.875rem;
}

.activity-content {
    flex: 1;
}

.activity-text {
    font-size: 0.875rem;
    color: var(--gray-700);
    margin: 0;
}

.activity-time {
    font-size: 0.75rem;
    color: var(--gray-500);
}

/* Quick Actions Grid */
.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 1rem;
}

.quick-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    padding: 1.5rem 1rem;
    background: var(--gray-50);
    border-radius: var(--border-radius-lg);
    text-decoration: none;
    color: var(--gray-700);
    transition: var(--transition);
    text-align: center;
    border: 2px solid transparent;
}

.quick-action-btn:hover {
    background: white;
    border-color: var(--primary);
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}

.quick-action-btn:hover .quick-action-icon {
    transform: scale(1.1);
}

.quick-action-icon {
    width: 50px;
    height: 50px;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    transition: var(--transition);
}

.quick-action-icon.green {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
}

.quick-action-icon.gold {
    background: linear-gradient(135deg, var(--accent), var(--accent-dark));
    color: var(--dark);
}

.quick-action-icon.blue {
    background: linear-gradient(135deg, var(--info), #0891b2);
    color: white;
}

.quick-action-btn span {
    font-weight: 600;
    font-size: 0.875rem;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .alert-card-content {
        flex-direction: column;
        text-align: center;
    }

    .alert-card-content .btn {
        width: 100%;
    }

    .quick-actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Page Title Enhancement */
.page-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.page-title-highlight {
    color: var(--primary);
}
</style>
@endsection
