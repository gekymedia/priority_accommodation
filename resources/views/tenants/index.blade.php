@extends('layouts.app')

@section('title', 'Tenant Management - Priority Accommodations')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Tenant Management</h1>
    <div class="page-actions">
        <a href="{{ route('tenants.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i>
            Add Tenant
        </a>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $totalTenants ?? 0 }}</div>
                <div class="stat-label">Total Tenants</div>
            </div>
            <div class="stat-icon blue">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $activeTenants ?? 0 }}</div>
                <div class="stat-label">Active Tenants</div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $newThisMonth ?? 0 }}</div>
                <div class="stat-label">New This Month</div>
            </div>
            <div class="stat-icon orange">
                <i class="fas fa-user-plus"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $vacatingSoon ?? 0 }}</div>
                <div class="stat-label">Vacating Soon</div>
            </div>
            <div class="stat-icon red">
                <i class="fas fa-user-clock"></i>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="card mb-6">
    <div class="card-header">
        <h3 class="card-title">Search & Filter</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('tenants.index') }}" class="filter-form">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Search Tenants</label>
                    <div class="search-input-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search by name, email, or phone..." 
                               class="search-input">
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Sort By</label>
                    <select name="sort" class="filter-select">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i>
                        Apply Filters
                    </button>
                    <a href="{{ route('tenants.index') }}" class="btn btn-secondary">
                        <i class="fas fa-refresh"></i>
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tenants Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tenant Records</h3>
        <div class="card-actions">
            <span class="text-sm text-gray-500">{{ $tenants->total() }} tenants found</span>
        </div>
    </div>
    <div class="card-body">
        @if($tenants->count() > 0)
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Tenant Information</th>
                        <th>Contact Details</th>
                        <th>Current Unit</th>
                        <th>Lease Period</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tenants as $tenant)
                    <tr class="table-row-hover">
                        <td>
                            <div class="tenant-info">
                                <div class="tenant-avatar">
                                    {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                </div>
                                <div class="tenant-details">
                                    <div class="tenant-name">{{ $tenant->name }}</div>
                                    <div class="tenant-id">ID: {{ $tenant->id }}</div>
                                    <div class="tenant-meta">
                                        <span class="meta-item">
                                            <i class="fas fa-calendar-plus"></i>
                                            Joined {{ $tenant->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="contact-info">
                                <div class="contact-email">
                                    <i class="fas fa-envelope contact-icon"></i>
                                    {{ $tenant->email }}
                                </div>
                                <div class="contact-phone">
                                    <i class="fas fa-phone contact-icon"></i>
                                    {{ $tenant->phone }}
                                </div>
                                @if($tenant->emergency_contact)
                                <div class="emergency-contact">
                                    <i class="fas fa-phone-alt emergency-icon"></i>
                                    {{ $tenant->emergency_contact }}
                                </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($tenant->currentUnit)
                            <div class="unit-info">
                                <div class="unit-number">
                                    <i class="fas fa-door-closed unit-icon"></i>
                                    {{ $tenant->currentUnit->unit_number }}
                                </div>
                                <div class="property-name">{{ $tenant->currentUnit->property->name ?? 'N/A' }}</div>
                                <div class="unit-type">{{ $tenant->currentUnit->type ?? 'N/A' }}</div>
                            </div>
                            @else
                            <div class="no-unit">
                                <i class="fas fa-times-circle no-unit-icon"></i>
                                No Active Lease
                            </div>
                            @endif
                        </td>
                        <td>
                            @if($tenant->currentLease)
                            <div class="lease-info">
                                <div class="lease-dates">
                                    <div class="date-item">
                                        <i class="fas fa-sign-in-alt date-icon in"></i>
                                        <span class="date-value">{{ $tenant->currentLease->start_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="date-item">
                                        <i class="fas fa-sign-out-alt date-icon out"></i>
                                        <span class="date-value">{{ $tenant->currentLease->end_date->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                <div class="lease-remaining">
                                    @php
                                        $daysRemaining = now()->diffInDays($tenant->currentLease->end_date, false);
                                    @endphp
                                    @if($daysRemaining > 0)
                                        <span class="remaining positive">{{ $daysRemaining }} days left</span>
                                    @else
                                        <span class="remaining negative">Lease expired</span>
                                    @endif
                                </div>
                            </div>
                            @else
                            <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="status {{ $tenant->status ?? 'active' }}">
                                <i class="status-icon {{ $tenant->status_icon }}"></i>
                                {{ ucfirst($tenant->status ?? 'active') }}
                            </span>
                            @if($tenant->currentLease && $tenant->currentLease->rent_due > 0)
                            <div class="rent-due">
                                <i class="fas fa-exclamation-triangle due-icon"></i>
                                ₵{{ number_format($tenant->currentLease->rent_due, 2) }} due
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('tenants.show', $tenant) }}" class="btn-action btn-view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('tenants.edit', $tenant) }}" class="btn-action btn-edit" title="Edit Tenant">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('tenants.payments', $tenant) }}" class="btn-action btn-payment" title="Payment History">
                                    <i class="fas fa-money-bill-wave"></i>
                                </a>
                                <form action="{{ route('tenants.destroy', $tenant) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Delete Tenant" 
                                            onclick="return confirm('Are you sure you want to delete this tenant? This action cannot be undone.')">
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
        @if($tenants->hasPages())
        <div class="table-pagination">
            <div class="pagination-info">
                Showing {{ $tenants->firstItem() }} to {{ $tenants->lastItem() }} of {{ $tenants->total() }} entries
            </div>
            <div class="pagination-links">
                @if($tenants->onFirstPage())
                    <span class="pagination-link disabled">
                        <i class="fas fa-chevron-left"></i>
                        Previous
                    </span>
                @else
                    <a href="{{ $tenants->previousPageUrl() }}" class="pagination-link">
                        <i class="fas fa-chevron-left"></i>
                        Previous
                    </a>
                @endif

                @foreach($tenants->getUrlRange(1, $tenants->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="pagination-link {{ $tenants->currentPage() == $page ? 'active' : '' }}">
                        {{ $page }}
                    </a>
                @endforeach

                @if($tenants->hasMorePages())
                    <a href="{{ $tenants->nextPageUrl() }}" class="pagination-link">
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
                <i class="fas fa-users-slash"></i>
            </div>
            <h3 class="empty-state-title">No Tenants Found</h3>
            <p class="empty-state-description">
                @if(request()->hasAny(['search', 'status', 'sort']))
                    No tenants match your current search criteria. Try adjusting your filters.
                @else
                    No tenants have been added yet. Start by adding your first tenant.
                @endif
            </p>
            <div class="empty-state-actions">
                @if(request()->hasAny(['search', 'status', 'sort']))
                    <a href="{{ route('tenants.index') }}" class="btn btn-secondary">
                        <i class="fas fa-refresh"></i>
                        Clear Filters
                    </a>
                @endif
                <a href="{{ route('tenants.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i>
                    Add New Tenant
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
/* Tenant Specific Styles */
.tenant-info {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.tenant-avatar {
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

.tenant-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.tenant-name {
    font-weight: 600;
    color: var(--dark);
}

.tenant-id {
    font-size: 0.75rem;
    color: var(--gray);
    font-family: monospace;
}

.tenant-meta {
    display: flex;
    gap: 0.5rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.625rem;
    color: var(--gray);
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.contact-email, .contact-phone, .emergency-contact {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.contact-icon, .emergency-icon {
    width: 1rem;
    color: var(--gray);
}

.emergency-contact {
    font-size: 0.75rem;
    color: var(--warning);
}

.unit-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.unit-number {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    font-size: 0.875rem;
}

.unit-icon {
    width: 1rem;
    color: var(--primary);
}

.property-name {
    font-size: 0.75rem;
    color: var(--gray);
}

.unit-type {
    font-size: 0.75rem;
    color: var(--primary);
    background: rgba(67, 97, 238, 0.1);
    padding: 0.125rem 0.5rem;
    border-radius: 1rem;
    width: fit-content;
}

.no-unit {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--gray);
    font-size: 0.875rem;
}

.no-unit-icon {
    color: var(--gray);
}

.lease-info {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.lease-dates {
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

.date-value {
    font-weight: 600;
    color: var(--dark);
}

.lease-remaining {
    margin-top: 0.25rem;
}

.remaining {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 1rem;
    font-weight: 600;
}

.remaining.positive {
    background: rgba(76, 201, 240, 0.1);
    color: var(--success);
}

.remaining.negative {
    background: rgba(247, 37, 133, 0.1);
    color: var(--danger);
}

.rent-due {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: var(--warning);
    margin-top: 0.5rem;
    padding: 0.25rem 0.5rem;
    background: rgba(248, 150, 30, 0.1);
    border-radius: 0.375rem;
}

.due-icon {
    font-size: 0.625rem;
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
}

.status.active {
    background: rgba(76, 201, 240, 0.1);
    color: var(--success);
}

.status.inactive {
    background: rgba(108, 117, 125, 0.1);
    color: var(--gray);
}

.status.pending {
    background: rgba(248, 150, 30, 0.1);
    color: var(--warning);
}

.status-icon {
    font-size: 0.625rem;
}

/* Action Buttons */
.btn-payment {
    background: rgba(76, 201, 240, 0.1);
    color: var(--success);
}

.btn-payment:hover {
    background: var(--success);
    color: white;
}
</style>

<script>
// Add dynamic status icons
document.addEventListener('DOMContentLoaded', function() {
    const statusIcons = {
        'active': 'fas fa-check-circle',
        'inactive': 'fas fa-times-circle',
        'pending': 'fas fa-clock'
    };

    document.querySelectorAll('.status').forEach(status => {
        const statusType = status.classList[1];
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