@extends('layouts.app')

@section('title', 'Student Management - Priority Accommodations')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Student Management</h1>
    <div class="page-actions">
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i>
            New Student
        </a>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $totalStudents }}</div>
                <div class="stat-label">Total Students</div>
            </div>
            <div class="stat-icon blue">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $activeStudents }}</div>
                <div class="stat-label">Active Residents</div>
            </div>
            <div class="stat-icon green">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $inactiveStudents }}</div>
                <div class="stat-label">Inactive Students</div>
            </div>
            <div class="stat-icon orange">
                <i class="fas fa-user-clock"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $universities->count() }}</div>
                <div class="stat-label">Universities</div>
            </div>
            <div class="stat-icon purple">
                <i class="fas fa-university"></i>
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
        <form method="GET" action="{{ route('admin.students.index') }}" class="filter-form">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Search Students</label>
                    <div class="search-input-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search by name, email, phone, or student ID..." 
                               class="search-input">
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Residents</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive Students</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">University</label>
                    <select name="university" class="filter-select">
                        <option value="">All Universities</option>
                        @foreach($universities as $university)
                            <option value="{{ $university }}" {{ request('university') == $university ? 'selected' : '' }}>
                                {{ $university }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i>
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                        <i class="fas fa-refresh"></i>
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Students Table Card -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Student Records</h3>
        <div class="card-actions">
            <span class="text-sm text-gray-500">{{ $students->total() }} students found</span>
        </div>
    </div>
    <div class="card-body">
        @if($students->count() > 0)
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Student Information</th>
                        <th>Contact Details</th>
                        <th>Academic Info</th>
                        <th>Status</th>
                        <th>Current Accommodation</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr class="table-row-hover">
                        <td>
                            <div class="student-info">
                                <div class="student-avatar">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div class="student-details">
                                    <div class="student-name">{{ $student->name }}</div>
                                    <div class="student-id">{{ $student->student_id }}</div>
                                    <div class="student-year">Year {{ $student->year_of_study }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="contact-info">
                                <div class="contact-email">
                                    <i class="fas fa-envelope contact-icon"></i>
                                    {{ $student->email }}
                                </div>
                                <div class="contact-phone">
                                    <i class="fas fa-phone contact-icon"></i>
                                    {{ $student->phone }}
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="academic-info">
                                <div class="university">
                                    <i class="fas fa-university academic-icon"></i>
                                    {{ $student->university }}
                                </div>
                                <div class="course">{{ $student->course }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="status {{ $student->is_active ? 'active' : 'inactive' }}">
                                <i class="status-icon {{ $student->is_active ? 'fas fa-circle-check' : 'fas fa-circle-pause' }}"></i>
                                {{ $student->is_active ? 'Active Resident' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            @if($student->currentBooking)
                                <div class="accommodation-info">
                                    <div class="room-number">
                                        <i class="fas fa-door-closed room-icon"></i>
                                        Room {{ $student->currentBooking->room->room_number }}
                                    </div>
                                    <div class="hostel-name">{{ $student->currentBooking->room->hostel->name ?? 'No Hostel' }}</div>
                                    <div class="check-in-date">
                                        Since {{ $student->currentBooking->check_in->format('M d, Y') }}
                                    </div>
                                </div>
                            @else
                                <div class="no-accommodation">
                                    <i class="fas fa-bed-slash"></i>
                                    No Active Booking
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.students.show', $student) }}" class="btn-action btn-view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.students.edit', $student) }}" class="btn-action btn-edit" title="Edit Student">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($student->is_active)
                                <a href="{{ route('admin.students.bookingHistory', $student) }}" class="btn-action btn-history" title="Booking History">
                                    <i class="fas fa-history"></i>
                                </a>
                                @endif
                                <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Delete Student" 
                                            onclick="return confirm('Are you sure you want to delete this student? This action cannot be undone.')">
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
        @if($students->hasPages())
        <div class="table-pagination">
            <div class="pagination-info">
                Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} entries
            </div>
            <div class="pagination-links">
                @if($students->onFirstPage())
                    <span class="pagination-link disabled">
                        <i class="fas fa-chevron-left"></i>
                        Previous
                    </span>
                @else
                    <a href="{{ $students->previousPageUrl() }}" class="pagination-link">
                        <i class="fas fa-chevron-left"></i>
                        Previous
                    </a>
                @endif

                @foreach($students->getUrlRange(1, $students->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="pagination-link {{ $students->currentPage() == $page ? 'active' : '' }}">
                        {{ $page }}
                    </a>
                @endforeach

                @if($students->hasMorePages())
                    <a href="{{ $students->nextPageUrl() }}" class="pagination-link">
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
            <h3 class="empty-state-title">No Students Found</h3>
            <p class="empty-state-description">
                @if(request()->hasAny(['search', 'status', 'university']))
                    No students match your current search criteria. Try adjusting your filters.
                @else
                    No students have been added yet. Start by adding your first student.
                @endif
            </p>
            <div class="empty-state-actions">
                @if(request()->hasAny(['search', 'status', 'university']))
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                        <i class="fas fa-refresh"></i>
                        Clear Filters
                    </a>
                @endif
                <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i>
                    Add New Student
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
/* Stats Grid Extension */
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

/* Student Info */
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
}

.student-id {
    font-size: 0.75rem;
    color: var(--gray);
    font-family: monospace;
}

.student-year {
    font-size: 0.75rem;
    color: var(--primary);
    background: rgba(67, 97, 238, 0.1);
    padding: 0.125rem 0.5rem;
    border-radius: 1rem;
    width: fit-content;
}

/* Contact & Academic Info */
.contact-info,
.academic-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.contact-email,
.contact-phone,
.university {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.contact-icon,
.academic-icon,
.room-icon {
    width: 1rem;
    color: var(--gray);
}

.course {
    font-size: 0.875rem;
    color: var(--gray);
    margin-left: 1.5rem;
}

/* Status */
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

.status-icon {
    font-size: 0.625rem;
}

/* Accommodation Info */
.accommodation-info {
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

.hostel-name {
    font-size: 0.75rem;
    color: var(--gray);
    margin-left: 1.5rem;
}

.check-in-date {
    font-size: 0.75rem;
    color: var(--primary);
}

.no-accommodation {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--gray);
    font-size: 0.875rem;
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

.btn-history {
    background: rgba(147, 51, 234, 0.1);
    color: #8b5cf6;
}

.btn-history:hover {
    background: #8b5cf6;
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
</style>
@endsection