@extends('layouts.app')

@section('title', 'Payments Management - Priority Accommodations')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Payments Management</h1>
    <div class="page-actions">
        <a href="{{ route('admin.payments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Record Payment
        </a>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">₵{{ number_format($totalRevenue, 2) }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">₵{{ number_format($monthlyRevenue, 2) }}</div>
                <div class="stat-label">This Month</div>
            </div>
            <div class="stat-icon blue">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $pendingPayments }}</div>
                <div class="stat-label">Pending Payments</div>
            </div>
            <div class="stat-icon orange">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $totalTransactions }}</div>
                <div class="stat-label">Total Transactions</div>
            </div>
            <div class="stat-icon purple">
                <i class="fas fa-receipt"></i>
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
        <form method="GET" action="{{ route('admin.payments.index') }}" class="filter-form">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Search Payments</label>
                    <div class="search-input-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search by student name, receipt number..." 
                               class="search-input">
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Payment Type</label>
                    <select name="type" class="filter-select">
                        <option value="">All Types</option>
                        <option value="rent" {{ request('type') == 'rent' ? 'selected' : '' }}>Rent</option>
                        <option value="security" {{ request('type') == 'security' ? 'selected' : '' }}>Security Deposit</option>
                        <option value="maintenance" {{ request('type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Month</label>
                    <input type="month" name="month" value="{{ request('month') }}" class="filter-select">
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i>
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-refresh"></i>
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Payments Table Card -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Payment Records</h3>
        <div class="card-actions">
            <span class="text-sm text-gray-500">{{ $payments->total() }} payments found</span>
            <button class="btn btn-secondary btn-sm">
                <i class="fas fa-download"></i>
                Export
            </button>
        </div>
    </div>
    <div class="card-body">
        @if($payments->count() > 0)
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Transaction Details</th>
                        <th>Student Information</th>
                        <th>Booking Reference</th>
                        <th>Payment Amount</th>
                        <th>Type & Method</th>
                        <th>Status</th>
                        <th>Payment Date</th>
                        <th class="text-center">Bank</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr class="table-row-hover">
                        <td>
                            <div class="transaction-info">
                                <div class="receipt-number">#{{ $payment->receipt_number }}</div>
                                <div class="payment-method">
                                    <i class="fas {{ $payment->method_icon }} method-icon"></i>
                                    {{ $payment->payment_method_text }}
                                </div>
                                @if($payment->transaction_id)
                                <div class="transaction-id">
                                    <i class="fas fa-hashtag id-icon"></i>
                                    {{ $payment->transaction_id }}
                                </div>
                                @endif
                                @if($payment->description)
                                <div class="payment-description">
                                    {{ Str::limit($payment->description, 60) }}
                                </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="student-info">
                                <div class="student-avatar">
                                    {{ strtoupper(substr($payment->student->name, 0, 1)) }}
                                </div>
                                <div class="student-details">
                                    <div class="student-name">{{ $payment->student->name }}</div>
                                    <div class="student-id">{{ $payment->student->student_id }}</div>
                                    <div class="student-contact">
                                        <i class="fas fa-envelope contact-icon"></i>
                                        {{ $payment->student->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($payment->booking)
                            <div class="booking-reference">
                                <div class="booking-id">
                                    <i class="fas fa-calendar-check booking-icon"></i>
                                    Booking #{{ $payment->booking->id }}
                                </div>
                                <div class="room-info">
                                    Room {{ $payment->booking->room->room_number ?? 'N/A' }}
                                </div>
                                <div class="hostel-name">
                                    {{ $payment->booking->room->hostel->name ?? 'No Hostel' }}
                                </div>
                                <div class="booking-dates">
                                    {{ $payment->booking->check_in->format('M d') }} - {{ $payment->booking->check_out->format('M d, Y') }}
                                </div>
                            </div>
                            @else
                            <div class="no-booking">
                                <i class="fas fa-times-circle no-booking-icon"></i>
                                No Booking
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="payment-amount">
                                <div class="amount-main">₵{{ number_format($payment->amount, 2) }}</div>
                                <div class="amount-type">{{ $payment->type_text }}</div>
                                @if($payment->booking && $payment->type === 'rent')
                                <div class="payment-progress">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ $payment->booking->payment_progress }}%"></div>
                                    </div>
                                    <div class="progress-text">{{ number_format($payment->booking->payment_progress, 1) }}% Paid</div>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="type-method-info">
                                <span class="payment-type type-{{ $payment->type }}">
                                    <i class="type-icon {{ $payment->type_icon }}"></i>
                                    {{ $payment->type_text }}
                                </span>
                                <div class="payment-method-badge">
                                    <i class="method-badge-icon {{ $payment->method_icon }}"></i>
                                    {{ $payment->payment_method_text }}
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="status {{ $payment->status }}">
                                <i class="status-icon {{ $payment->status_icon }}"></i>
                                {{ $payment->status_text }}
                            </span>
                            @if($payment->status === 'pending')
                            <div class="pending-actions">
                                <form action="{{ route('admin.payments.mark-completed', $payment) }}" method="POST" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn-complete" title="Mark as Completed">
                                        <i class="fas fa-check"></i>
                                        Complete
                                    </button>
                                </form>
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="payment-date-info">
                                <div class="date-main">
                                    <i class="fas fa-calendar date-icon"></i>
                                    {{ $payment->formatted_payment_date }}
                                </div>
                                <div class="time-secondary">
                                    {{ $payment->payment_date->format('h:i A') }}
                                </div>
                                <div class="date-relative">
                                    {{ $payment->payment_date->diffForHumans() }}
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($payment->status === 'completed')
                            <form action="{{ route('admin.payments.sync', $payment) }}" method="POST" class="inline-form" data-sync-form>
                                @csrf
                                <button type="submit" class="btn-action btn-view" title="Sync with Priority Bank">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </form>
                            @if($payment->external_transaction_id)
                            <span class="text-xs text-gray-500 d-block mt-1">Synced</span>
                            @endif
                            @else
                            <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.payments.show', $payment) }}" class="btn-action btn-view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.payments.edit', $payment) }}" class="btn-action btn-edit" title="Edit Payment">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn-action btn-print" title="Print Receipt" onclick="printReceipt({{ $payment->id }})">
                                    <i class="fas fa-print"></i>
                                </button>
                                <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Delete Payment" 
                                            onclick="return confirm('Are you sure you want to delete this payment record? This action cannot be undone.')">
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
        @if($payments->hasPages())
        <div class="table-pagination">
            <div class="pagination-info">
                Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} entries
            </div>
            <div class="pagination-links">
                @if($payments->onFirstPage())
                    <span class="pagination-link disabled">
                        <i class="fas fa-chevron-left"></i>
                        Previous
                    </span>
                @else
                    <a href="{{ $payments->previousPageUrl() }}" class="pagination-link">
                        <i class="fas fa-chevron-left"></i>
                        Previous
                    </a>
                @endif

                @foreach($payments->getUrlRange(1, $payments->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="pagination-link {{ $payments->currentPage() == $page ? 'active' : '' }}">
                        {{ $page }}
                    </a>
                @endforeach

                @if($payments->hasMorePages())
                    <a href="{{ $payments->nextPageUrl() }}" class="pagination-link">
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
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <h3 class="empty-state-title">No Payments Found</h3>
            <p class="empty-state-description">
                @if(request()->hasAny(['search', 'status', 'type', 'month']))
                    No payments match your current search criteria. Try adjusting your filters.
                @else
                    No payments have been recorded yet. Start by recording your first payment.
                @endif
            </p>
            <div class="empty-state-actions">
                @if(request()->hasAny(['search', 'status', 'type', 'month']))
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-refresh"></i>
                        Clear Filters
                    </a>
                @endif
                <a href="{{ route('admin.payments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Record New Payment
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
    grid-template-columns: 2fr 1fr 1fr 1fr auto;
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

/* Payment Specific Styles */
.transaction-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.receipt-number {
    font-weight: 700;
    color: var(--dark);
    font-size: 0.875rem;
    font-family: monospace;
}

.payment-method, .transaction-id {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: var(--gray);
}

.method-icon, .id-icon {
    width: 1rem;
    color: var(--primary);
}

.payment-description {
    font-size: 0.75rem;
    color: var(--gray);
    font-style: italic;
    background: var(--gray-50);
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
}

.student-info {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.student-avatar {
    width: 2.5rem;
    height: 2.5rem;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.student-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.student-name {
    font-weight: 600;
    color: var(--dark);
    font-size: 0.875rem;
}

.student-id {
    font-size: 0.75rem;
    color: var(--gray);
    font-family: monospace;
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

.booking-reference {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.booking-id {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--primary);
}

.booking-icon {
    width: 1rem;
    color: var(--primary);
}

.room-info {
    font-size: 0.75rem;
    color: var(--dark);
    font-weight: 500;
}

.hostel-name {
    font-size: 0.75rem;
    color: var(--gray);
}

.booking-dates {
    font-size: 0.625rem;
    color: var(--gray);
    background: var(--gray-50);
    padding: 0.125rem 0.5rem;
    border-radius: 1rem;
    width: fit-content;
}

.no-booking {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--gray);
    font-size: 0.875rem;
}

.no-booking-icon {
    color: var(--gray);
}

.payment-amount {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.amount-main {
    font-weight: 700;
    color: var(--success);
    font-size: 1rem;
}

.amount-type {
    font-size: 0.75rem;
    color: var(--gray);
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

.type-method-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.payment-type {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    border-radius: 2rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: capitalize;
    width: fit-content;
}

.type-rent {
    background: rgba(67, 97, 238, 0.1);
    color: var(--primary);
}

.type-security {
    background: rgba(147, 51, 234, 0.1);
    color: #8b5cf6;
}

.type-maintenance {
    background: rgba(248, 150, 30, 0.1);
    color: var(--warning);
}

.type-other {
    background: rgba(108, 117, 125, 0.1);
    color: var(--gray);
}

.type-icon {
    font-size: 0.625rem;
}

.payment-method-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: var(--gray);
    padding: 0.25rem 0.5rem;
    background: var(--gray-50);
    border-radius: 1rem;
    width: fit-content;
}

.method-badge-icon {
    font-size: 0.625rem;
    color: var(--primary);
}

/* Status Styles */
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

.status.completed {
    background: rgba(76, 201, 240, 0.1);
    color: var(--success);
}

.status.pending {
    background: rgba(248, 150, 30, 0.1);
    color: var(--warning);
}

.status.failed {
    background: rgba(247, 37, 133, 0.1);
    color: var(--danger);
}

.status-icon {
    font-size: 0.625rem;
}

.pending-actions {
    margin-top: 0.5rem;
}

.btn-complete {
    background: rgba(76, 201, 240, 0.1);
    color: var(--success);
    border: 1px solid rgba(76, 201, 240, 0.3);
    border-radius: 0.375rem;
    padding: 0.25rem 0.5rem;
    font-size: 0.625rem;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.btn-complete:hover {
    background: var(--success);
    color: white;
}

.payment-date-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.date-main {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--dark);
}

.date-icon {
    width: 1rem;
    color: var(--primary);
}

.time-secondary {
    font-size: 0.75rem;
    color: var(--gray);
}

.date-relative {
    font-size: 0.625rem;
    color: var(--gray);
    font-style: italic;
}

/* Action Buttons */
.btn-print {
    background: rgba(139, 92, 246, 0.1);
    color: #8b5cf6;
}

.btn-print:hover {
    background: #8b5cf6;
    color: white;
}
</style>

<script>
// Add dynamic icons for payment methods and types
document.addEventListener('DOMContentLoaded', function() {
    const methodIcons = {
        'cash': 'fas fa-money-bill',
        'bank_transfer': 'fas fa-university',
        'upi': 'fas fa-mobile-alt',
        'card': 'fas fa-credit-card'
    };

    const typeIcons = {
        'rent': 'fas fa-home',
        'security': 'fas fa-shield-alt',
        'maintenance': 'fas fa-tools',
        'other': 'fas fa-cube'
    };

    const statusIcons = {
        'completed': 'fas fa-check-circle',
        'pending': 'fas fa-clock',
        'failed': 'fas fa-times-circle'
    };

    // Add method icons
    document.querySelectorAll('.payment-method').forEach(method => {
        const methodText = method.textContent.toLowerCase();
        const iconClass = methodIcons[methodText.includes('cash') ? 'cash' : 
                         methodText.includes('bank') ? 'bank_transfer' :
                         methodText.includes('upi') ? 'upi' : 'card'];
        if (iconClass) {
            const icon = method.querySelector('.method-icon');
            if (icon) {
                icon.className = `method-icon ${iconClass}`;
            }
        }
    });

    // Add type icons
    document.querySelectorAll('.payment-type').forEach(type => {
        const typeClass = type.classList[1].replace('type-', '');
        const iconClass = typeIcons[typeClass];
        if (iconClass) {
            const icon = type.querySelector('.type-icon');
            if (icon) {
                icon.className = `type-icon ${iconClass}`;
            }
        }
    });

    // Add status icons
    document.querySelectorAll('.status').forEach(status => {
        const statusClass = status.classList[1];
        const iconClass = statusIcons[statusClass];
        if (iconClass) {
            const icon = status.querySelector('.status-icon');
            if (icon) {
                icon.className = `status-icon ${iconClass}`;
            }
        }
    });

    // Add method badge icons
    document.querySelectorAll('.payment-method-badge').forEach(badge => {
        const methodText = badge.textContent.toLowerCase();
        const iconClass = methodIcons[methodText.includes('cash') ? 'cash' : 
                         methodText.includes('bank') ? 'bank_transfer' :
                         methodText.includes('upi') ? 'upi' : 'card'];
        if (iconClass) {
            const icon = badge.querySelector('.method-badge-icon');
            if (icon) {
                icon.className = `method-badge-icon ${iconClass}`;
            }
        }
    });
});

function printReceipt(paymentId) {
    window.open(`/payments/${paymentId}/receipt`, '_blank');
}

document.querySelectorAll('[data-sync-form]').forEach(function(f) {
    f.addEventListener('submit', function() {
        var btn = this.querySelector('button[type="submit"]');
        if (btn) { btn.disabled = true; var icon = btn.querySelector('i'); if (icon) icon.classList.add('fa-spin'); }
    });
});
</script>
@endsection