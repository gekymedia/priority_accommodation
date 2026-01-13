@extends('layouts.app')

@section('title', 'Complaints Management - Priority Accommodations')

@section('content')
<div class="page-header">
    <h1 class="page-title">Complaints Management</h1>
    <div class="page-actions">
        <a href="{{ route('admin.complaints.index') }}" class="btn btn-secondary">
            <i class="fas fa-sync"></i>
            Refresh
        </a>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Complaints</div>
            </div>
            <div class="stat-icon blue">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $stats['pending'] }}</div>
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
                <div class="stat-value">{{ $stats['in_progress'] }}</div>
                <div class="stat-label">In Progress</div>
            </div>
            <div class="stat-icon info">
                <i class="fas fa-spinner"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $stats['urgent'] }}</div>
                <div class="stat-label">Urgent Open</div>
            </div>
            <div class="stat-icon red">
                <i class="fas fa-exclamation-circle"></i>
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
        <form method="GET" action="{{ route('admin.complaints.index') }}" class="filter-form">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Search</label>
                    <div class="search-input-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search by subject, description, or student..." 
                               class="search-input">
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-select">
                        <option value="">All Status</option>
                        @foreach(\App\Models\Complaint::getStatuses() as $value => $label)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Priority</label>
                    <select name="priority" class="filter-select">
                        <option value="">All Priorities</option>
                        @foreach(\App\Models\Complaint::getPriorities() as $value => $label)
                            <option value="{{ $value }}" {{ request('priority') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Category</label>
                    <select name="category" class="filter-select">
                        <option value="">All Categories</option>
                        @foreach(\App\Models\Complaint::getCategories() as $value => $label)
                            <option value="{{ $value }}" {{ request('category') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
                <a href="{{ route('admin.complaints.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Complaints Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Subject</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($complaints as $complaint)
                    <tr>
                        <td>#{{ $complaint->id }}</td>
                        <td>
                            <div>
                                <strong>{{ $complaint->student->name }}</strong>
                                <br><small class="text-muted">{{ $complaint->student->email }}</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>{{ Str::limit($complaint->subject, 40) }}</strong>
                                @if($complaint->room)
                                    <br><small class="text-muted">{{ $complaint->room->name }}</small>
                                @endif
                            </div>
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
                        <td>
                            {{ $complaint->assignedTo->name ?? 'Unassigned' }}
                        </td>
                        <td>{{ $complaint->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.complaints.show', $complaint) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.complaints.edit', $complaint) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No complaints found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $complaints->links() }}
        </div>
    </div>
</div>

<style>
/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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
    color: var(--dark);
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

.stat-icon.info {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.stat-icon.red {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

/* Filter Form */
.filter-form {
    width: 100%;
}

.filter-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
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
    font-size: 0.875rem;
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
    font-size: 0.875rem;
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
    grid-column: span 4;
    justify-content: flex-start;
    margin-top: 1rem;
}

/* Table Styles */
.table-responsive {
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th {
    background: var(--gray-50);
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: var(--dark);
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 2px solid var(--border-color);
}

.table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    vertical-align: middle;
    font-size: 0.875rem;
}

.table tbody tr:hover {
    background: var(--gray-50);
}

/* Badge Styles */
.badge {
    display: inline-flex;
    align-items: center;
    padding: 0.35rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 9999px;
}

.badge-secondary {
    background: var(--gray-100);
    color: var(--gray-700);
}

.badge-success, .badge-green {
    background: rgba(76, 201, 240, 0.1);
    color: var(--success);
}

.badge-warning, .badge-orange {
    background: rgba(248, 150, 30, 0.1);
    color: var(--warning);
}

.badge-danger, .badge-red {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.badge-info, .badge-blue {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.badge-yellow {
    background: rgba(234, 179, 8, 0.1);
    color: #ca8a04;
}

/* Button Group */
.btn-group {
    display: flex;
    gap: 0.5rem;
}

.btn-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.8rem;
}

.btn-info {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    border: none;
}

.btn-info:hover {
    background: #3b82f6;
    color: white;
}

/* Empty State */
.text-center {
    text-align: center;
}

.py-5 {
    padding-top: 3rem;
    padding-bottom: 3rem;
}

.text-muted {
    color: var(--gray);
}

.mb-3 {
    margin-bottom: 1rem;
}

.fa-3x {
    font-size: 3rem;
    opacity: 0.5;
}

/* Pagination */
.mt-4 {
    margin-top: 1.5rem;
}

/* Responsive */
@media (max-width: 1024px) {
    .filter-grid {
        grid-template-columns: 1fr 1fr;
    }
    
    .filter-actions {
        grid-column: span 2;
    }
}

@media (max-width: 768px) {
    .filter-grid {
        grid-template-columns: 1fr;
    }
    
    .filter-actions {
        grid-column: span 1;
        flex-direction: column;
    }
    
    .filter-actions .btn {
        width: 100%;
    }
    
    .stats-grid {
        grid-template-columns: 1fr 1fr;
    }
}
</style>
@endsection

