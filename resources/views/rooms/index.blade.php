<!-- resources/views/rooms/index.blade.php -->
@extends('layouts.app')

@section('title', 'Room Management - Priority Accommodations')
@section('page-title', 'Room Management')
@section('page-subtitle', 'Manage all rooms and their availability')

@section('content')
<div class="fade-in">
    <!-- Header with Actions -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">All Rooms</h2>
            <p class="text-gray-600">Manage room inventory and availability</p>
        </div>
        <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Add New Room
        </a>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $totalRooms }}</div>
                <div class="text-sm text-gray-600">Total Rooms</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-green-600">{{ $availableRooms }}</div>
                <div class="text-sm text-gray-600">Available</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-orange-600">{{ $occupiedRooms }}</div>
                <div class="text-sm text-gray-600">Occupied</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-red-600">{{ $maintenanceRooms }}</div>
                <div class="text-sm text-gray-600">Maintenance</div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card mb-6">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.rooms.index') }}">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search by room number..." class="form-control">
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        <!-- Filter by Hostel -->
                        <select name="hostel_id" class="form-control">
                            <option value="">All Hostels</option>
                            @foreach($hostels as $hostel)
                                <option value="{{ $hostel->id }}" {{ request('hostel_id') == $hostel->id ? 'selected' : '' }}>
                                    {{ $hostel->name }}
                                </option>
                            @endforeach
                        </select>
                        
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            @foreach(\App\Models\Room::getStatuses() as $value => $label)
                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <select name="type" class="form-control">
                            <option value="">All Types</option>
                            @foreach(\App\Models\Room::getTypes() as $value => $label)
                                <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <select name="available" class="form-control">
                            <option value="">All Availability</option>
                            <option value="1" {{ request('available') === '1' ? 'selected' : '' }}>Available</option>
                            <option value="0" {{ request('available') === '0' ? 'selected' : '' }}>Not Available</option>
                        </select>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i>
                            Filter
                        </button>
                        <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">
                            <i class="fas fa-refresh"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Rooms Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Room List ({{ $rooms->total() }} rooms found)</h3>
        </div>
        <div class="card-body">
            @if($rooms->count() > 0)
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Photo</th>
                            <th>Room Info</th>
                            <th>Type</th>
                            <th>Capacity</th>
                            <th>Price/Academic Year</th>
                            <th>Status</th>
                            <th>Availability</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rooms as $room)
                        <tr>
                            <td>
                                <div class="room-photo-thumb">
                                    @if($room->photos && count($room->photos) > 0)
                                        <img src="{{ asset('storage/' . $room->photos[0]) }}" alt="Room {{ $room->room_number }}">
                                        @if(count($room->photos) > 1)
                                            <span class="photo-count">+{{ count($room->photos) - 1 }}</span>
                                        @endif
                                    @else
                                        <div class="no-photo">
                                            <i class="fas fa-bed"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="font-medium text-gray-900">{{ $room->room_number }}</div>
                                <div class="text-sm text-gray-500">{{ $room->hostel->name ?? 'No Hostel' }}</div>
                                @if($room->description)
                                <div class="text-xs text-gray-400 mt-1">{{ Str::limit($room->description, 50) }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="room-type capitalize">{{ $room->type }}</span>
                            </td>
                            <td>
                                <span class="font-medium">{{ $room->capacity }}</span> persons
                            </td>
                            <td>
                                <span class="font-medium text-green-600">₵{{ number_format($room->price_per_academic_year, 2) }}</span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $room->status }}">
                                    {{ ucfirst($room->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="availability-badge availability-{{ $room->available ? 'yes' : 'no' }}">
                                    {{ $room->available ? 'Available' : 'Not Available' }}
                                </span>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.rooms.show', $room) }}" class="btn btn-secondary btn-sm" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-secondary btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm {{ $room->available ? 'btn-warning' : 'btn-success' }} toggle-availability" 
                                            data-room-id="{{ $room->id }}" title="{{ $room->available ? 'Mark Unavailable' : 'Mark Available' }}">
                                        <i class="fas {{ $room->available ? 'fa-times' : 'fa-check' }}"></i>
                                    </button>
                                    <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete" 
                                                onclick="return confirm('Are you sure you want to delete this room?')">
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
            @if($rooms->hasPages())
            <div class="card-footer">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-700">
                        Showing {{ $rooms->firstItem() }} to {{ $rooms->lastItem() }} of {{ $rooms->total() }} results
                    </div>
                    <div class="flex gap-1">
                        @if($rooms->onFirstPage())
                            <span class="btn btn-secondary disabled">Previous</span>
                        @else
                            <a href="{{ $rooms->previousPageUrl() }}" class="btn btn-secondary">Previous</a>
                        @endif

                        @foreach($rooms->getUrlRange(1, $rooms->lastPage()) as $page => $url)
                            <a href="{{ $url }}" class="btn {{ $rooms->currentPage() == $page ? 'btn-primary' : 'btn-secondary' }}">
                                {{ $page }}
                            </a>
                        @endforeach

                        @if($rooms->hasMorePages())
                            <a href="{{ $rooms->nextPageUrl() }}" class="btn btn-secondary">Next</a>
                        @else
                            <span class="btn btn-secondary disabled">Next</span>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            @else
            <div class="text-center py-8">
                <i class="fas fa-bed text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No rooms found</h3>
                <p class="text-gray-500 mb-4">No rooms match your search criteria.</p>
                <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-refresh"></i>
                    Clear Filters
                </a>
                <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Add New Room
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Room photo thumbnail */
    .room-photo-thumb {
        position: relative;
        width: 60px;
        height: 60px;
        border-radius: var(--radius);
        overflow: hidden;
        background: var(--gray-100);
    }

    .room-photo-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .room-photo-thumb .photo-count {
        position: absolute;
        bottom: 2px;
        right: 2px;
        padding: 0.125rem 0.25rem;
        background: rgba(0, 0, 0, 0.75);
        color: white;
        font-size: 0.6rem;
        font-weight: 600;
        border-radius: 0.25rem;
    }

    .room-photo-thumb .no-photo {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--gray-200), var(--gray-300));
        color: var(--gray-500);
        font-size: 1.25rem;
    }

    /* Room-specific styles */
    .status-badge, .availability-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-available {
        background-color: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .status-occupied {
        background-color: rgba(59, 130, 246, 0.1);
        color: var(--primary);
    }

    .status-maintenance {
        background-color: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    .availability-yes {
        background-color: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .availability-no {
        background-color: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .btn-sm {
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
    }

    .room-type {
        padding: 0.25rem 0.5rem;
        background: var(--gray-100);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 500;
    }
</style>

<script>
// Toggle room availability - FIXED VERSION
document.querySelectorAll('.toggle-availability').forEach(button => {
    button.addEventListener('click', function() {
        const roomId = this.dataset.roomId;
        const button = this;
        const currentAvailable = this.classList.contains('btn-warning'); // If it's btn-warning, currently available
        const newStatus = currentAvailable ? 'maintenance' : 'available';
        
        if (!confirm(`Are you sure you want to mark this room as ${newStatus === 'maintenance' ? 'unavailable' : 'available'}?`)) {
            return;
        }
        
        fetch(`/rooms/${roomId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                status: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showAlert('success', data.message);
                
                // Reload the page after 1 second to reflect all changes
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while updating room status.');
        });
    });
});

// Alert function for index page
function showAlert(type, message) {
    // Create alert element
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} fixed top-4 right-4 z-50 max-w-sm`;
    alert.innerHTML = `
        <div class="flex items-center justify-between p-4 rounded-lg border ${
            type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 
            'bg-red-50 border-red-200 text-red-800'
        }">
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle mr-2"></i>
                <span>${message}</span>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    document.body.appendChild(alert);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alert.parentElement) {
            alert.remove();
        }
    }, 5000);
}
</script>
@endsection