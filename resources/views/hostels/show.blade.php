@extends('layouts.app')

@section('title', $hostel->name . ' - Hostel Details - Priority Accommodations')
@section('page-title', $hostel->name)
@section('page-subtitle', 'Hostel details and information')

@section('content')
<div class="fade-in">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Hostel Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Hostel Overview Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hostel Overview</h3>
                    <div class="flex gap-2">
                        <span class="hostel-status status-{{ $hostel->is_active ? 'active' : 'inactive' }}">
                            {{ $hostel->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="flex items-start space-x-6">
                        <!-- Hostel Avatar -->
                        <div class="hostel-avatar-large">
                            <i class="fas fa-building"></i>
                        </div>
                        
                        <!-- Hostel Information -->
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="info-group">
                                    <label class="info-label">Hostel Name</label>
                                    <div class="info-value">{{ $hostel->name }}</div>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Contact Email</label>
                                    <div class="info-value">{{ $hostel->contact_email }}</div>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Contact Phone</label>
                                    <div class="info-value">{{ $hostel->contact_phone }}</div>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="info-group">
                                    <label class="info-label">Status</label>
                                    <div class="info-value">
                                        <span class="hostel-status status-{{ $hostel->is_active ? 'active' : 'inactive' }}">
                                            {{ $hostel->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Total Rooms</label>
                                    <div class="info-value">{{ $roomStats['total'] }}</div>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Occupancy Rate</label>
                                    <div class="info-value">{{ $hostel->occupancy_rate }}%</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="info-group">
                            <label class="info-label">Address</label>
                            <div class="info-value text-gray-700 leading-relaxed">{{ $hostel->address }}</div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($hostel->description)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="info-group">
                            <label class="info-label">Description</label>
                            <div class="info-value text-gray-700 leading-relaxed">{{ $hostel->description }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Amenities -->
            @if($hostel->amenities && count($hostel->amenities) > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hostel Amenities</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @php
                            $amenityIcons = [
                                'wifi' => 'fas fa-wifi',
                                'laundry' => 'fas fa-tshirt',
                                'cleaning' => 'fas fa-broom',
                                'security' => 'fas fa-shield-alt',
                                'cctv' => 'fas fa-video',
                                'parking' => 'fas fa-parking',
                                'gym' => 'fas fa-dumbbell',
                                'common_room' => 'fas fa-couch',
                                'study_room' => 'fas fa-book',
                                'kitchen' => 'fas fa-utensils',
                                'dining' => 'fas fa-utensil-spoon',
                                'garden' => 'fas fa-leaf',
                                'bike_storage' => 'fas fa-bicycle',
                                'vending_machines' => 'fas fa-cube',
                                'medical_support' => 'fas fa-first-aid',
                                'maintenance' => 'fas fa-tools'
                            ];
                            
                            $amenityLabels = [
                                'wifi' => 'WiFi',
                                'laundry' => 'Laundry Service',
                                'cleaning' => 'Cleaning Service',
                                'security' => '24/7 Security',
                                'cctv' => 'CCTV Surveillance',
                                'parking' => 'Parking',
                                'gym' => 'Gym',
                                'common_room' => 'Common Room',
                                'study_room' => 'Study Room',
                                'kitchen' => 'Shared Kitchen',
                                'dining' => 'Dining Hall',
                                'garden' => 'Garden',
                                'bike_storage' => 'Bike Storage',
                                'vending_machines' => 'Vending Machines',
                                'medical_support' => 'Medical Support',
                                'maintenance' => '24/7 Maintenance'
                            ];
                        @endphp
                        @foreach($hostel->amenities as $amenity)
                            @if(isset($amenityIcons[$amenity]))
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="{{ $amenityIcons[$amenity] }}"></i>
                                </div>
                                <span class="feature-text">{{ $amenityLabels[$amenity] ?? $amenity }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Rooms Overview -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rooms Overview</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="stat-card">
                            <div class="stat-header">
                                <div>
                                    <div class="stat-value">{{ $roomStats['total'] }}</div>
                                    <div class="stat-label">Total Rooms</div>
                                </div>
                                <div class="stat-icon blue">
                                    <i class="fas fa-door-closed"></i>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-header">
                                <div>
                                    <div class="stat-value">{{ $roomStats['available'] }}</div>
                                    <div class="stat-label">Available</div>
                                </div>
                                <div class="stat-icon green">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-header">
                                <div>
                                    <div class="stat-value">{{ $roomStats['occupied'] }}</div>
                                    <div class="stat-label">Occupied</div>
                                </div>
                                <div class="stat-icon orange">
                                    <i class="fas fa-user-check"></i>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-header">
                                <div>
                                    <div class="stat-value">{{ $roomStats['maintenance'] }}</div>
                                    <div class="stat-label">Maintenance</div>
                                </div>
                                <div class="stat-icon red">
                                    <i class="fas fa-tools"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($hostel->rooms->count() > 0)
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Room Number</th>
                                    <th>Type</th>
                                    <th>Capacity</th>
                                    <th>Price/Semester</th>
                                    <th>Status</th>
                                    <th>Current Occupant</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hostel->rooms->sortBy('room_number') as $room)
                                <tr>
                                    <td class="font-medium">{{ $room->room_number }}</td>
                                    <td>
                                        <span class="capitalize">{{ $room->type }}</span>
                                    </td>
                                    <td>{{ $room->capacity }} persons</td>
                                    <td class="text-green-600 font-medium">₵{{ number_format($room->price_per_semester, 2) }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $room->status }}">
                                            {{ ucfirst($room->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($room->currentBooking)
                                            <div class="flex items-center space-x-2">
                                                <div class="student-avatar-small">
                                                    {{ strtoupper(substr($room->currentBooking->student->name, 0, 1)) }}
                                                </div>
                                                <span class="text-sm">{{ $room->currentBooking->student->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-500 text-sm">Vacant</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.rooms.show', $room) }}" class="btn-action btn-view" title="View Room">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.rooms.edit', $room) }}" class="btn-action btn-edit" title="Edit Room">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-door-open text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500">No rooms available in this hostel</p>
                        <a href="{{ route('admin.rooms.create', ['hostel_id' => $hostel->id]) }}" class="btn btn-primary mt-4">
                            <i class="fas fa-plus"></i>
                            Add Room
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Actions & Statistics -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <a href="{{ route('admin.hostels.edit', $hostel) }}" class="btn btn-primary w-full justify-center">
                            <i class="fas fa-edit"></i>
                            Edit Hostel
                        </a>
                        
                        <a href="{{ route('admin.rooms.create', ['hostel_id' => $hostel->id]) }}" class="btn btn-success w-full justify-center">
                            <i class="fas fa-plus"></i>
                            Add Room
                        </a>

                        <button type="button" class="btn btn-warning w-full justify-center toggle-status" 
                                data-hostel-id="{{ $hostel->id }}">
                            <i class="fas {{ $hostel->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                            {{ $hostel->is_active ? 'Deactivate Hostel' : 'Activate Hostel' }}
                        </button>

                        <a href="mailto:{{ $hostel->contact_email }}" class="btn btn-secondary w-full justify-center">
                            <i class="fas fa-envelope"></i>
                            Send Email
                        </a>

                        <a href="tel:{{ $hostel->contact_phone }}" class="btn btn-secondary w-full justify-center">
                            <i class="fas fa-phone"></i>
                            Call Hostel
                        </a>

                        <form action="{{ route('admin.hostels.destroy', $hostel) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-full justify-center" 
                                    onclick="return confirm('Are you sure you want to delete this hostel? This will also delete all associated rooms and bookings.')">
                                <i class="fas fa-trash"></i>
                                Delete Hostel
                            </button>
                        </form>

                        <a href="{{ route('admin.hostels.index') }}" class="btn btn-secondary w-full justify-center">
                            <i class="fas fa-arrow-left"></i>
                            Back to Hostels
                        </a>
                    </div>
                </div>
            </div>

            <!-- Hostel Statistics -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hostel Statistics</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="stat-item">
                            <label>Total Rooms</label>
                            <div class="stat-value">{{ $roomStats['total'] }}</div>
                        </div>
                        <div class="stat-item">
                            <label>Available Rooms</label>
                            <div class="stat-value">{{ $roomStats['available'] }}</div>
                        </div>
                        <div class="stat-item">
                            <label>Occupied Rooms</label>
                            <div class="stat-value">{{ $roomStats['occupied'] }}</div>
                        </div>
                        <div class="stat-item">
                            <label>Maintenance Rooms</label>
                            <div class="stat-value">{{ $roomStats['maintenance'] }}</div>
                        </div>
                        <div class="stat-item">
                            <label>Occupancy Rate</label>
                            <div class="stat-value">{{ $hostel->occupancy_rate }}%</div>
                        </div>
                        <div class="stat-item">
                            <label>Total Revenue</label>
                            <div class="stat-value">₵{{ number_format($hostel->total_revenue, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Contact Information</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <div class="contact-item">
                            <i class="fas fa-envelope text-blue-600"></i>
                            <div>
                                <div class="font-medium">Email</div>
                                <div class="text-sm text-gray-600">{{ $hostel->contact_email }}</div>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone text-green-600"></i>
                            <div>
                                <div class="font-medium">Phone</div>
                                <div class="text-sm text-gray-600">{{ $hostel->contact_phone }}</div>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt text-purple-600"></i>
                            <div>
                                <div class="font-medium">Address</div>
                                <div class="text-sm text-gray-600">{{ $hostel->address }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

    .hostel-status {
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

    .hostel-avatar-large {
        width: 5rem;
        height: 5rem;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        flex-shrink: 0;
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

    .stat-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 1rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--gray);
        font-weight: 500;
    }

    .stat-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
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

    .stat-icon.red {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .student-avatar-small {
        width: 1.5rem;
        height: 1.5rem;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.625rem;
    }

    .btn-action {
        width: 2rem;
        height: 2rem;
        border-radius: var(--border-radius);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        border: none;
        cursor: pointer;
        font-size: 0.75rem;
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
        font-size: 0.875rem;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 0;
    }

    .contact-item:not(:last-child) {
        border-bottom: 1px solid var(--gray-200);
    }

    .contact-item i {
        font-size: 1.25rem;
        width: 1.5rem;
    }

    .status-badge {
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
</style>

<script>
// Toggle hostel status
document.querySelector('.toggle-status').addEventListener('click', function() {
    const hostelId = this.dataset.hostelId;
    const button = this;
    
    fetch(`/hostels/${hostelId}/toggle-status`, {
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
                button.innerHTML = '<i class="fas fa-pause"></i> Deactivate Hostel';
                
                // Update status badge
                const statusBadge = document.querySelector('.hostel-status');
                statusBadge.className = 'hostel-status status-active';
                statusBadge.textContent = 'Active';
            } else {
                button.classList.remove('btn-warning');
                button.classList.add('btn-success');
                button.innerHTML = '<i class="fas fa-play"></i> Activate Hostel';
                
                // Update status badge
                const statusBadge = document.querySelector('.hostel-status');
                statusBadge.className = 'hostel-status status-inactive';
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
</script>
@endsection