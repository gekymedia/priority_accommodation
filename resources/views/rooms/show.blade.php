<!-- resources/views/rooms/show.blade.php -->
@extends('layouts.app')

@section('title', $room->room_number . ' - Room Details - Priority Accommodations')
@section('page-title', 'Room: ' . $room->room_number)
@section('page-subtitle', 'Detailed room information and management')

@section('content')
<div class="fade-in">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Room Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Room Overview Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Room Overview</h3>
                    <div class="flex gap-2">
                        <span class="status-badge status-{{ $room->status }}">
                            {{ ucfirst($room->status) }}
                        </span>
                        <span class="availability-badge availability-{{ $room->available ? 'yes' : 'no' }}">
                            {{ $room->available ? 'Available' : 'Not Available' }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <div class="info-group">
                                <label class="info-label">Room Number</label>
                                <div class="info-value">{{ $room->room_number }}</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Hostel</label>
                                <div class="info-value">{{ $room->hostel->name ?? 'Not Assigned' }}</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Room Type</label>
                                <div class="info-value capitalize">{{ $room->type }}</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Capacity</label>
                                <div class="info-value">{{ $room->capacity }} {{ $room->capacity == 1 ? 'person' : 'people' }}</div>
                            </div>
                        </div>

                        <!-- Pricing & Status -->
                        <div class="space-y-4">
                            <div class="info-group">
                                <label class="info-label">Price per Academic Year</label>
                                <div class="info-value text-green-600">₵{{ number_format($room->price_per_academic_year, 2) }}</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Current Status</label>
                                <div class="info-value">
                                    <span class="status-badge status-{{ $room->status }}">
                                        {{ ucfirst($room->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Availability</label>
                                <div class="info-value">
                                    <span class="availability-badge availability-{{ $room->available ? 'yes' : 'no' }}">
                                        {{ $room->available ? 'Available' : 'Not Available' }}
                                    </span>
                                </div>
                            </div>
                            @if($room->video_url)
                            <div class="info-group">
                                <label class="info-label">Video Tour</label>
                                <div class="info-value">
                                    <a href="{{ $room->video_url }}" target="_blank" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-video"></i>
                                        Watch Video
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Description -->
                    @if($room->description)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <label class="info-label">Description</label>
                        <div class="info-value">
                            <p class="text-gray-700 leading-relaxed">{{ $room->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Room Features -->
            @if($room->features && count($room->features) > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Room Features & Amenities</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @php
                            $featureIcons = [
                                'wifi' => 'fas fa-wifi',
                                'ac' => 'fas fa-snowflake',
                                'heater' => 'fas fa-temperature-high',
                                'tv' => 'fas fa-tv',
                                'fridge' => 'fas fa-refrigerator',
                                'wardrobe' => 'fas fa-archive',
                                'desk' => 'fas fa-pencil-alt',
                                'chair' => 'fas fa-chair',
                                'attached_bathroom' => 'fas fa-bath',
                                'geyser' => 'fas fa-water',
                                'balcony' => 'fas fa-door-open',
                                'laundry' => 'fas fa-tshirt',
                                'cleaning' => 'fas fa-broom',
                                'security' => 'fas fa-shield-alt',
                                'cctv' => 'fas fa-video',
                                'parking' => 'fas fa-parking'
                            ];
                            
                            $featureLabels = [
                                'wifi' => 'WiFi',
                                'ac' => 'Air Conditioning',
                                'heater' => 'Heater',
                                'tv' => 'TV',
                                'fridge' => 'Refrigerator',
                                'wardrobe' => 'Wardrobe',
                                'desk' => 'Study Desk',
                                'chair' => 'Chair',
                                'attached_bathroom' => 'Attached Bathroom',
                                'geyser' => 'Geyser',
                                'balcony' => 'Balcony',
                                'laundry' => 'Laundry Service',
                                'cleaning' => 'Cleaning Service',
                                'security' => '24/7 Security',
                                'cctv' => 'CCTV',
                                'parking' => 'Parking'
                            ];
                        @endphp
                        @foreach($room->features as $feature)
                            @if(isset($featureIcons[$feature]))
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="{{ $featureIcons[$feature] }}"></i>
                                </div>
                                <span class="feature-text">{{ $featureLabels[$feature] ?? $feature }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Room Photos -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Room Photos</h3>
                </div>
                <div class="card-body">
                    @if($room->photos && count($room->photos) > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($room->photos as $photo)
                        <div class="photo-container">
                            <img src="{{ asset('storage/' . $photo) }}" 
                                 alt="Room photo {{ $loop->iteration }}" 
                                 class="room-photo">
                            <div class="photo-overlay">
                                <button type="button" class="btn btn-secondary btn-sm" 
                                        onclick="openPhotoModal('{{ asset('storage/' . $photo) }}')">
                                    <i class="fas fa-expand"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-camera text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">No photos available for this room</p>
                        <p class="text-sm text-gray-400 mt-2">Add photos in the room edit page</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Actions & Current Booking -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <!-- Manage Occupants -->
                        <a href="{{ route('hostel.room-occupants', $room) }}" 
                           class="btn w-full justify-center" 
                           style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white;">
                            <i class="fas fa-users-cog"></i>
                            Manage Occupants ({{ $room->activeOccupants->count() ?? 0 }}/{{ $room->capacity }})
                        </a>

                        <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-primary w-full justify-center">
                            <i class="fas fa-edit"></i>
                            Edit Room
                        </a>
                        
                        @if($room->isAvailable())
                        <a href="{{ route('admin.bookings.create', ['room_id' => $room->id]) }}" 
                           class="btn btn-success w-full justify-center">
                            <i class="fas fa-calendar-plus"></i>
                            Create Booking
                        </a>
                        @endif

                        <button type="button" class="btn btn-warning w-full justify-center toggle-availability" 
                                data-room-id="{{ $room->id }}">
                            <i class="fas {{ $room->available ? 'fa-times' : 'fa-check' }}"></i>
                            {{ $room->available ? 'Mark Unavailable' : 'Mark Available' }}
                        </button>

                        <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-full justify-center" 
                                    onclick="return confirm('Are you sure you want to delete this room? This action cannot be undone.')">
                                <i class="fas fa-trash"></i>
                                Delete Room
                            </button>
                        </form>

                        <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary w-full justify-center">
                            <i class="fas fa-arrow-left"></i>
                            Back to Rooms
                        </a>
                    </div>
                </div>
            </div>

            <!-- Current Booking -->
            @if($room->currentBooking)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Current Occupant</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <div class="student-avatar">
                                {{ strtoupper(substr($room->currentBooking->student->name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">
                                    {{ $room->currentBooking->student->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $room->currentBooking->student->email }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <label class="text-gray-500">Check-in</label>
                                <div class="font-medium">{{ $room->currentBooking->check_in->format('M d, Y') }}</div>
                            </div>
                            <div>
                                <label class="text-gray-500">Check-out</label>
                                <div class="font-medium">{{ $room->currentBooking->check_out->format('M d, Y') }}</div>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('admin.students.show', $room->currentBooking->student) }}" 
                               class="btn btn-secondary btn-sm flex-1">
                                <i class="fas fa-user"></i>
                                View Student
                            </a>
                            <a href="{{ route('admin.bookings.show', $room->currentBooking) }}" 
                               class="btn btn-secondary btn-sm flex-1">
                                <i class="fas fa-calendar"></i>
                                View Booking
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Room Statistics -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Room Statistics</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="stat-item">
                            <label>Occupancy</label>
                            <div class="stat-value">
                                <span class="{{ ($room->activeOccupants->count() ?? 0) >= $room->capacity ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $room->activeOccupants->count() ?? 0 }}/{{ $room->capacity }} beds
                                </span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <label>Beds Available</label>
                            <div class="stat-value">
                                <span class="{{ $room->beds_available > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $room->beds_available ?? 0 }}
                                </span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <label>Total Bookings</label>
                            <div class="stat-value">{{ $room->bookings->count() }}</div>
                        </div>
                        <div class="stat-item">
                            <label>Current Semester</label>
                            <div class="stat-value">
                                @if($room->currentBooking)
                                    <span class="text-green-600">Occupied</span>
                                @else
                                    <span class="text-orange-600">Vacant</span>
                                @endif
                            </div>
                        </div>
                        <div class="stat-item">
                            <label>Last Updated</label>
                            <div class="stat-value text-sm">{{ $room->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking History -->
    @if($room->bookings->count() > 0)
    <div class="mt-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Booking History</h3>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($room->bookings->sortByDesc('created_at')->take(5) as $booking)
                            <tr>
                                <td>
                                    <div class="font-medium">{{ $booking->student->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $booking->student->email }}</div>
                                </td>
                                <td>{{ $booking->check_in->format('M d, Y') }}</td>
                                <td>{{ $booking->check_out->format('M d, Y') }}</td>
                                <td>
                                    <span class="booking-status status-{{ $booking->status }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="text-green-600 font-medium">₵{{ number_format($booking->total_amount, 2) }}</td>
                                <td>
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($room->bookings->count() > 5)
                <div class="card-footer text-center">
                    <a href="{{ route('admin.bookings.index', ['room_id' => $room->id]) }}" class="btn btn-secondary">
                        View All Bookings
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Photo Modal -->
<div id="photoModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closePhotoModal()">&times;</span>
        <img id="modalImage" src="" alt="Room photo" class="modal-image">
    </div>
</div>

<style>
    .info-group {
        margin-bottom: 1rem;
    }

    .info-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--gray-600);
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 1rem;
        color: var(--gray-900);
        font-weight: 500;
    }

    .status-badge, .availability-badge, .booking-status {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-available, .booking-status.confirmed, .booking-status.checked_in {
        background-color: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .status-occupied, .booking-status.pending {
        background-color: rgba(59, 130, 246, 0.1);
        color: var(--primary);
    }

    .status-maintenance, .booking-status.cancelled {
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

    .feature-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: var(--gray-50);
        border-radius: var(--radius);
        border: 1px solid var(--gray-200);
    }

    .feature-icon {
        width: 2rem;
        height: 2rem;
        background: var(--primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.875rem;
    }

    .feature-text {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--gray-700);
    }

    .photo-container {
        position: relative;
        border-radius: var(--radius);
        overflow: hidden;
        aspect-ratio: 4/3;
    }

    .room-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .photo-container:hover .room-photo {
        transform: scale(1.05);
    }

    .photo-overlay {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .photo-container:hover .photo-overlay {
        opacity: 1;
    }

    .student-avatar {
        width: 3rem;
        height: 3rem;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1.125rem;
    }

    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--gray-200);
    }

    .stat-item:last-child {
        border-bottom: none;
    }

    .stat-item label {
        color: var(--gray-600);
        font-size: 0.875rem;
    }

    .stat-value {
        font-weight: 600;
        color: var(--gray-900);
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        animation: fadeIn 0.3s ease;
    }

    .modal-content {
        position: relative;
        margin: auto;
        padding: 2rem;
        width: 90%;
        max-width: 800px;
        top: 50%;
        transform: translateY(-50%);
    }

    .modal-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        color: white;
        font-size: 2rem;
        font-weight: bold;
        cursor: pointer;
        z-index: 1001;
    }

    .modal-image {
        width: 100%;
        height: auto;
        border-radius: var(--radius);
    }
</style>

<script>
// Toggle room availability - FIXED VERSION
document.querySelector('.toggle-availability').addEventListener('click', function() {
    const roomId = this.dataset.roomId;
    const button = this;
    const currentAvailable = {{ $room->available ? 'true' : 'false' }};
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

// Alert function
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