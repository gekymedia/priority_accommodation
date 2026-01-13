@extends('layouts.app')

@section('title', 'Booking Management - Priority Accommodations')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Booking Management</h1>
    <div class="page-actions">
        <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            New Booking
        </a>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $totalBookings }}</div>
                <div class="stat-label">Total Bookings</div>
            </div>
            <div class="stat-icon blue">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $pendingBookings }}</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-icon orange">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $confirmedBookings }}</div>
                <div class="stat-label">Confirmed</div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $checkedInBookings }}</div>
                <div class="stat-label">Checked In</div>
            </div>
            <div class="stat-icon purple">
                <i class="fas fa-door-open"></i>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filters Card -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Search & Filter</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="filter-form">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Search Bookings</label>
                    <div class="search-input-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search by student name or email..." 
                               class="search-input">
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-select">
                        <option value="">All Status</option>
                        @foreach($bookingStatuses as $value => $label)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Room</label>
                    <select name="room_id" class="filter-select">
                        <option value="">All Rooms</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                                {{ $room->room_number }} ({{ $room->hostel->name ?? 'No Hostel' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i>
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-refresh"></i>
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Bookings Table Card -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Booking Records</h3>
        <div class="card-actions">
            <span class="text-sm text-gray-500">{{ $bookings->total() }} bookings found</span>
        </div>
    </div>
    <div class="card-body">
        @if($bookings->count() > 0)
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Booking Information</th>
                        <th>Student Details</th>
                        <th>Room Details</th>
                        <th>Stay Duration</th>
                        <th>Payment Info</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr class="table-row-hover">
                        <td>
                            <div class="booking-info">
                                <div class="booking-id">#{{ $booking->id }}</div>
                                <div class="booking-date">
                                    <i class="fas fa-calendar-plus date-icon"></i>
                                    {{ $booking->created_at->format('M d, Y') }}
                                </div>
                                <div class="booking-semesters">
                                    <i class="fas fa-graduation-cap semester-icon"></i>
                                    {{ $booking->semesters }} Semester(s)
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="student-info">
                                <div class="student-name">{{ $booking->student->name }}</div>
                                <div class="student-contact">
                                    <i class="fas fa-envelope contact-icon"></i>
                                    {{ $booking->student->email }}
                                </div>
                                <div class="student-contact">
                                    <i class="fas fa-phone contact-icon"></i>
                                    {{ $booking->student->phone }}
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="room-info">
                                <div class="room-number">
                                    <i class="fas fa-door-closed room-icon"></i>
                                    Room {{ $booking->room->room_number }}
                                </div>
                                <div class="hostel-name">{{ $booking->room->hostel->name ?? 'No Hostel' }}</div>
                                <div class="room-type capitalize">{{ $booking->room->type }}</div>
                                @if($booking->room->price_per_semester)
                                <div class="room-price">
                                    ₵{{ number_format($booking->room->price_per_semester, 2) }}/semester
                                </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="duration-info">
                                <div class="date-range">
                                    <div class="date-item">
                                        <i class="fas fa-sign-in-alt date-icon in"></i>
                                        <span class="date-label">Check-in</span>
                                        <span class="date-value">{{ $booking->check_in->format('M d, Y') }}</span>
                                    </div>
                                    <div class="date-item">
                                        <i class="fas fa-sign-out-alt date-icon out"></i>
                                        <span class="date-label">Check-out</span>
                                        <span class="date-value">{{ $booking->check_out->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                <div class="duration-badge">
                                    <i class="fas fa-clock duration-icon"></i>
                                    {{ $booking->duration }} days
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="payment-info">
                                <div class="payment-total">
                                    <i class="fas fa-money-bill-wave payment-icon"></i>
                                    ₵{{ number_format($booking->total_amount, 2) }}
                                </div>
                                @if($booking->advance_paid > 0)
                                <div class="payment-advance">
                                    Advance: ₵{{ number_format($booking->advance_paid, 2) }}
                                </div>
                                <div class="payment-due">
                                    Due: ₵{{ number_format($booking->balance_due, 2) }}
                                </div>
                                @endif
                                @if($booking->total_paid > 0)
                                <div class="payment-progress">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ $booking->payment_progress }}%"></div>
                                    </div>
                                    <div class="progress-text">{{ number_format($booking->payment_progress, 1) }}% Paid</div>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="status {{ $booking->status }}">
                                <i class="status-icon {{ $booking->status_icon }}"></i>
                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                            </span>
                            @if($booking->is_active && $booking->days_remaining !== null)
                            <div class="days-remaining {{ $booking->is_overdue ? 'overdue' : '' }}">
                                <i class="fas fa-calendar-day remaining-icon"></i>
                                {{ abs($booking->days_remaining) }} days {{ $booking->is_overdue ? 'overdue' : 'remaining' }}
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn-action btn-view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn-action btn-edit" title="Edit Booking">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <!-- Quick Status Actions -->
                                @if($booking->status == 'pending')
                                <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn-action btn-confirm" title="Confirm Booking">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                                
                                @if($booking->status == 'confirmed')
                                <form action="{{ route('admin.bookings.checkin', $booking) }}" method="POST" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn-action btn-checkin" title="Check In">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </button>
                                </form>
                                @endif
                                
                                @if($booking->status == 'checked_in')
                                <form action="{{ route('admin.bookings.checkout', $booking) }}" method="POST" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn-action btn-checkout" title="Check Out">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Delete Booking" 
                                            onclick="return confirm('Are you sure you want to delete this booking? This action cannot be undone.')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
        <div class="table-pagination">
            <div class="pagination-info">
                Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} of {{ $bookings->total() }} entries
            </div>
            <div class="pagination-links">
                @if($bookings->onFirstPage())
                    <span class="pagination-link disabled">
                        <i class="fas fa-chevron-left"></i>
                        Previous
                    </span>
                @else
                    <a href="{{ $bookings->previousPageUrl() }}" class="pagination-link">
                        <i class="fas fa-chevron-left"></i>
                        Previous
                    </a>
                @endif

                @foreach($bookings->getUrlRange(1, $bookings->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="pagination-link {{ $bookings->currentPage() == $page ? 'active' : '' }}">
                        {{ $page }}
                    </a>
                @endforeach

                @if($bookings->hasMorePages())
                    <a href="{{ $bookings->nextPageUrl() }}" class="pagination-link">
                        Next
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span class="pagination-link disabled">
                        Next
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        </div>
        @endif

        @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-calendar-times"></i>
            </div>
            <h3 class="empty-state-title">No Bookings Found</h3>
            <p class="empty-state-description">
                @if(request()->hasAny(['search', 'status', 'room_id']))
                    No bookings match your current search criteria. Try adjusting your filters.
                @else
                    No bookings have been created yet. Start by creating your first booking.
                @endif
            </p>
            <div class="empty-state-actions">
                @if(request()->hasAny(['search', 'status', 'room_id']))
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-refresh"></i>
                        Clear Filters
                    </a>
                @endif
                <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Create New Booking
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
    transition: var(--transition);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stat-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.875rem;
    color: var(--gray);
    font-weight: 500;
}

.stat-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.stat-icon.blue {
    background: rgba(67, 97, 238, 0.1);
    color: var(--primary);
}

.stat-icon.green {
    background: rgba(76, 201, 240, 0.1);
    color: var(--success);
}

.stat-icon.orange {
    background: rgba(248, 150, 30, 0.1);
    color: var(--warning);
}

.stat-icon.purple {
    background: rgba(147, 51, 234, 0.1);
    color: #8b5cf6;
}

/* Filter Form */
.filter-form {
    width: 100%;
}

.filter-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr auto;
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--dark);
}

.search-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 1rem;
    color: var(--gray);
    z-index: 10;
}

.search-input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background: white;
    transition: var(--transition);
}

.search-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.filter-select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background: white;
    transition: var(--transition);
}

.filter-select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

/* Modern Table */
.table-responsive {
    overflow-x: auto;
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
}

.modern-table th {
    background: var(--gray-50);
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: var(--dark);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid var(--border-color);
}

.modern-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    vertical-align: top;
}

.table-row-hover:hover {
    background: var(--gray-50);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.btn-action {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    font-size: 0.875rem;
}

.btn-view {
    background: rgba(67, 97, 238, 0.1);
    color: var(--primary);
}

.btn-view:hover {
    background: var(--primary);
    color: white;
}

.btn-edit {
    background: rgba(248, 150, 30, 0.1);
    color: var(--warning);
}

.btn-edit:hover {
    background: var(--warning);
    color: white;
}

.btn-delete {
    background: rgba(247, 37, 133, 0.1);
    color: var(--danger);
}

.btn-delete:hover {
    background: var(--danger);
    color: white;
}

.inline-form {
    display: inline;
}

/* Pagination */
.table-pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 0 0;
    margin-top: 1.5rem;
    border-top: 1px solid var(--border-color);
}

.pagination-info {
    font-size: 0.875rem;
    color: var(--gray);
}

.pagination-links {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.pagination-link {
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    color: var(--dark);
    text-decoration: none;
    font-size: 0.875rem;
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.pagination-link:hover:not(.disabled):not(.active) {
    background: var(--gray-50);
    border-color: var(--gray-300);
}

.pagination-link.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.pagination-link.disabled {
    color: var(--gray);
    cursor: not-allowed;
    opacity: 0.5;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-state-icon {
    font-size: 4rem;
    color: var(--gray-300);
    margin-bottom: 1.5rem;
}

.empty-state-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.empty-state-description {
    color: var(--gray);
    margin-bottom: 2rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.empty-state-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .filter-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .filter-actions {
        justify-content: flex-end;
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .table-pagination {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .pagination-links {
        justify-content: center;
    }
    
    .empty-state-actions {
        flex-direction: column;
        align-items: center;
    }
}

/* Booking Specific Styles */
.booking-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.booking-id {
    font-weight: 700;
    color: var(--dark);
    font-size: 0.875rem;
}

.booking-date, .booking-semesters {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: var(--gray);
}

.date-icon, .semester-icon {
    width: 1rem;
    color: var(--primary);
}

.student-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.student-name {
    font-weight: 600;
    color: var(--dark);
    font-size: 0.875rem;
}

.student-contact {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: var(--gray);
}

.contact-icon {
    width: 1rem;
    color: var(--gray);
}

.room-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.room-number {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    font-size: 0.875rem;
}

.room-icon {
    width: 1rem;
    color: var(--primary);
}

.hostel-name {
    font-size: 0.75rem;
    color: var(--gray);
    margin-left: 1.5rem;
}

.room-type {
    font-size: 0.75rem;
    color: var(--primary);
    background: rgba(67, 97, 238, 0.1);
    padding: 0.125rem 0.5rem;
    border-radius: 1rem;
    width: fit-content;
}

.room-price {
    font-size: 0.75rem;
    color: var(--success);
    font-weight: 500;
}

.duration-info {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.date-range {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.date-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
}

.date-icon {
    width: 1rem;
}

.date-icon.in {
    color: var(--success);
}

.date-icon.out {
    color: var(--warning);
}

.date-label {
    color: var(--gray);
    min-width: 4rem;
}

.date-value {
    font-weight: 600;
    color: var(--dark);
}

.duration-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(67, 97, 238, 0.1);
    color: var(--primary);
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    width: fit-content;
}

.duration-icon {
    font-size: 0.625rem;
}

.payment-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.payment-total {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 700;
    color: var(--success);
    font-size: 0.875rem;
}

.payment-icon {
    width: 1rem;
    color: var(--success);
}

.payment-advance {
    font-size: 0.75rem;
    color: var(--gray);
}

.payment-due {
    font-size: 0.75rem;
    color: var(--warning);
    font-weight: 600;
}

.payment-progress {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.progress-bar {
    width: 100%;
    height: 4px;
    background: var(--gray-200);
    border-radius: 2px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: var(--success);
    transition: width 0.3s ease;
}

.progress-text {
    font-size: 0.625rem;
    color: var(--gray);
    text-align: center;
}

/* Status Styles with Icons */
.status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    border-radius: 2rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: capitalize;
    margin-bottom: 0.5rem;
}

.status.pending {
    background: rgba(248, 150, 30, 0.1);
    color: var(--warning);
}

.status.confirmed {
    background: rgba(67, 97, 238, 0.1);
    color: var(--primary);
}

.status.checked_in {
    background: rgba(76, 201, 240, 0.1);
    color: var(--success);
}

.status.checked_out {
    background: rgba(108, 117, 125, 0.1);
    color: var(--gray);
}

.status.cancelled {
    background: rgba(247, 37, 133, 0.1);
    color: var(--danger);
}

.status-icon {
    font-size: 0.625rem;
}

.days-remaining {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.625rem;
    padding: 0.25rem 0.5rem;
    border-radius: 1rem;
    background: rgba(76, 201, 240, 0.1);
    color: var(--success);
    width: fit-content;
}

.days-remaining.overdue {
    background: rgba(247, 37, 133, 0.1);
    color: var(--danger);
}

.remaining-icon {
    font-size: 0.5rem;
}

/* Action Buttons for Status Actions */
.btn-confirm {
    background: rgba(76, 201, 240, 0.1);
    color: var(--success);
}

.btn-confirm:hover {
    background: var(--success);
    color: white;
}

.btn-checkin {
    background: rgba(67, 97, 238, 0.1);
    color: var(--primary);
}

.btn-checkin:hover {
    background: var(--primary);
    color: white;
}

.btn-checkout {
    background: rgba(248, 150, 30, 0.1);
    color: var(--warning);
}

.btn-checkout:hover {
    background: var(--warning);
    color: white;
}

/* Add status icon mapping */
.status.pending .status-icon { content: "\f017"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
.status.confirmed .status-icon { content: "\f14a"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
.status.checked_in .status-icon { content: "\f2f6"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
.status.checked_out .status-icon { content: "\f2f5"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
.status.cancelled .status-icon { content: "\f05e"; font-family: "Font Awesome 6 Free"; font-weight: 900; }
</style>

<script>
// Add status icons dynamically
document.addEventListener('DOMContentLoaded', function() {
    const statusIcons = {
        'pending': 'fas fa-clock',
        'confirmed': 'fas fa-check-circle',
        'checked_in': 'fas fa-door-open',
        'checked_out': 'fas fa-door-closed',
        'cancelled': 'fas fa-times-circle'
    };

    document.querySelectorAll('.status').forEach(status => {
        const statusType = status.classList[1]; // Get the status class (pending, confirmed, etc.)
        const iconClass = statusIcons[statusType];
        if (iconClass) {
            const icon = status.querySelector('.status-icon');
            if (icon) {
                icon.className = `status-icon ${iconClass}`;
            }
        }
    });
});
</script>
@endsection