@extends('layouts.app')

@section('title', 'Hostel Management - Priority Accommodations')
@section('page-title', 'Hostel Management')
@section('page-subtitle', 'Manage all hostels and their properties')

@section('content')
<div class="fade-in">
    <!-- Header with Actions -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">All Hostels</h2>
            <p class="text-gray-600">Manage hostel properties and their rooms</p>
        </div>
        <a href="{{ route('admin.hostels.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Add New Hostel
        </a>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $totalHostels }}</div>
                <div class="text-sm text-gray-600">Total Hostels</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-green-600">{{ $activeHostels }}</div>
                <div class="text-sm text-gray-600">Active Hostels</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-orange-600">{{ $totalRooms }}</div>
                <div class="text-sm text-gray-600">Total Rooms</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-purple-600">{{ $totalCapacity }}</div>
                <div class="text-sm text-gray-600">Total Capacity</div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card mb-6">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.hostels.index') }}">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search hostels by name, address, or email..." class="form-control">
                    </div>
                    <div class="flex gap-2">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i>
                            Filter
                        </button>
                        <a href="{{ route('admin.hostels.index') }}" class="btn btn-secondary">
                            <i class="fas fa-refresh"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Hostels Grid -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Hostel List ({{ $hostels->total() }} hostels found)</h3>
        </div>
        <div class="card-body">
            @if($hostels->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($hostels as $hostel)
                <div class="hostel-card">
                    <!-- Cover Image -->
                    <div class="hostel-cover-image">
                        @if($hostel->cover_image)
                            <img src="{{ asset('storage/' . $hostel->cover_image) }}" alt="{{ $hostel->name }}" class="cover-img">
                        @elseif($hostel->images && count($hostel->images) > 0)
                            <img src="{{ asset('storage/' . $hostel->images[0]) }}" alt="{{ $hostel->name }}" class="cover-img">
                        @else
                            <div class="cover-placeholder">
                                <i class="fas fa-building"></i>
                            </div>
                        @endif
                        <span class="status-overlay status-{{ $hostel->is_active ? 'active' : 'inactive' }}">
                            {{ $hostel->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        @if($hostel->images && count($hostel->images) > 1)
                            <span class="image-count">
                                <i class="fas fa-images"></i> {{ count($hostel->images) }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="hostel-card-header">
                        <div class="hostel-info">
                            <h4 class="hostel-name">{{ $hostel->name }}</h4>
                        </div>
                    </div>
                    
                    <div class="hostel-details">
                        <div class="detail-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span class="truncate">{{ Str::limit($hostel->address, 40) }}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-phone"></i>
                            <span>{{ $hostel->contact_phone }}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-envelope"></i>
                            <span class="truncate">{{ $hostel->contact_email }}</span>
                        </div>
                    </div>

                    <!-- Room Statistics -->
                    <div class="hostel-stats">
                        <div class="stat">
                            <div class="stat-number">{{ $hostel->rooms_count }}</div>
                            <div class="stat-label">Total Rooms</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number text-green-600">{{ $hostel->available_rooms_count }}</div>
                            <div class="stat-label">Available</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number text-blue-600">{{ $hostel->occupied_rooms_count }}</div>
                            <div class="stat-label">Occupied</div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="hostel-actions">
                        <a href="{{ route('admin.hostels.show', $hostel) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-eye"></i>
                            View
                        </a>
                        <a href="{{ route('admin.hostels.edit', $hostel) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-edit"></i>
                            Edit
                        </a>
                        <button type="button" class="btn btn-sm {{ $hostel->is_active ? 'btn-warning' : 'btn-success' }} toggle-status" 
                                data-hostel-id="{{ $hostel->id }}" title="{{ $hostel->is_active ? 'Deactivate' : 'Activate' }}">
                            <i class="fas {{ $hostel->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                        </button>
                        <form action="{{ route('admin.hostels.destroy', $hostel) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Are you sure you want to delete this hostel?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($hostels->hasPages())
            <div class="mt-6">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-700">
                        Showing {{ $hostels->firstItem() }} to {{ $hostels->lastItem() }} of {{ $hostels->total() }} results
                    </div>
                    <div class="flex gap-1">
                        @if($hostels->onFirstPage())
                            <span class="btn btn-secondary disabled">Previous</span>
                        @else
                            <a href="{{ $hostels->previousPageUrl() }}" class="btn btn-secondary">Previous</a>
                        @endif

                        @foreach($hostels->getUrlRange(1, $hostels->lastPage()) as $page => $url)
                            <a href="{{ $url }}" class="btn {{ $hostels->currentPage() == $page ? 'btn-primary' : 'btn-secondary' }}">
                                {{ $page }}
                            </a>
                        @endforeach

                        @if($hostels->hasMorePages())
                            <a href="{{ $hostels->nextPageUrl() }}" class="btn btn-secondary">Next</a>
                        @else
                            <span class="btn btn-secondary disabled">Next</span>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            @else
            <div class="text-center py-12">
                <i class="fas fa-building text-gray-400 text-5xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hostels found</h3>
                <p class="text-gray-500 mb-6">Get started by creating your first hostel property.</p>
                <a href="{{ route('admin.hostels.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Create First Hostel
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .hostel-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        overflow: hidden;
        transition: var(--transition);
        box-shadow: var(--shadow-sm);
    }

    .hostel-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .hostel-cover-image {
        position: relative;
        width: 100%;
        height: 160px;
        background: linear-gradient(135deg, var(--gray-100), var(--gray-200));
        overflow: hidden;
    }

    .hostel-cover-image .cover-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .hostel-card:hover .cover-img {
        transform: scale(1.05);
    }

    .hostel-cover-image .cover-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        font-size: 3rem;
        opacity: 0.8;
    }

    .status-overlay {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-overlay.status-active {
        background: rgba(16, 185, 129, 0.95);
        color: white;
    }

    .status-overlay.status-inactive {
        background: rgba(107, 114, 128, 0.95);
        color: white;
    }

    .image-count {
        position: absolute;
        bottom: 0.75rem;
        right: 0.75rem;
        padding: 0.25rem 0.5rem;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        border-radius: 0.25rem;
        font-size: 0.7rem;
        font-weight: 500;
    }

    .hostel-card-header {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem 1.5rem 0;
    }

    .hostel-info {
        flex: 1;
        min-width: 0;
    }

    .hostel-name {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.25rem;
        word-wrap: break-word;
    }

    .hostel-details {
        padding: 0 1.5rem;
        margin-bottom: 1rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.875rem;
        color: var(--gray);
    }

    .detail-item i {
        width: 1rem;
        color: var(--primary);
    }

    .hostel-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin: 0 1.5rem 1rem;
        padding: 1rem;
        background: var(--gray-50);
        border-radius: var(--border-radius);
    }

    .stat {
        text-align: center;
    }

    .stat-number {
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--gray);
        margin-top: 0.25rem;
    }

    .hostel-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
        padding: 0 1.5rem 1.5rem;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-active {
        background-color: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .status-inactive {
        background-color: rgba(107, 114, 128, 0.1);
        color: var(--gray-600);
    }

    .btn-sm {
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
    }
</style>

<script>
// Toggle hostel status
document.querySelectorAll('.toggle-status').forEach(button => {
    button.addEventListener('click', function() {
        const hostelId = this.dataset.hostelId;
        const button = this;
        
        fetch(`/admin/hostels/${hostelId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update button appearance
                if (data.is_active) {
                    button.classList.remove('btn-success');
                    button.classList.add('btn-warning');
                    button.innerHTML = '<i class="fas fa-pause"></i>';
                    button.title = 'Deactivate';
                    
                    // Update status badge
                    const statusBadge = button.closest('.hostel-card').querySelector('.status-badge');
                    statusBadge.className = 'status-badge status-active';
                    statusBadge.textContent = 'Active';
                } else {
                    button.classList.remove('btn-warning');
                    button.classList.add('btn-success');
                    button.innerHTML = '<i class="fas fa-play"></i>';
                    button.title = 'Activate';
                    
                    // Update status badge
                    const statusBadge = button.closest('.hostel-card').querySelector('.status-badge');
                    statusBadge.className = 'status-badge status-inactive';
                    statusBadge.textContent = 'Inactive';
                }
                
                // Show success message
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating hostel status');
        });
    });
});
</script>
@endsection