<!-- resources/views/students/show.blade.php -->
@extends('layouts.app')

@section('title', $student->name . ' - Student Details - Priority Accommodations')
@section('page-title', $student->name)
@section('page-subtitle', 'Student details and information')

@section('content')
<div class="fade-in">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Student Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Student Overview Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Student Overview</h3>
                    <div class="flex gap-2">
                        <span class="student-status status-{{ $student->status }}">
                            {{ $student->is_active ? 'Active Resident' : 'Inactive Student' }}
                        </span>
                        @if($student->is_active)
                            <span class="booking-status status-checked_in">Currently Residing</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="flex items-start space-x-6">
                        <!-- Student Avatar -->
                        <div class="student-avatar-large">
                            {{ strtoupper(substr($student->name, 0, 1)) }}
                        </div>
                        
                        <!-- Student Information -->
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="info-group">
                                    <label class="info-label">Student ID</label>
                                    <div class="info-value">{{ $student->student_id }}</div>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Email</label>
                                    <div class="info-value">{{ $student->email }}</div>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Phone</label>
                                    <div class="info-value">{{ $student->phone }}</div>
                                </div>
                                @if($student->date_of_birth)
                                <div class="info-group">
                                    <label class="info-label">Date of Birth</label>
                                    <div class="info-value">
                                        {{ $student->date_of_birth->format('M d, Y') }}
                                        <span class="text-gray-500 text-sm ml-2">({{ $student->age }} years old)</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                            <div class="space-y-4">
                                <div class="info-group">
                                    <label class="info-label">University</label>
                                    <div class="info-value">{{ $student->university }}</div>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Course</label>
                                    <div class="info-value">{{ $student->course }}</div>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Year of Study</label>
                                    <div class="info-value">Year {{ $student->year_of_study }}</div>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Member Since</label>
                                    <div class="info-value">{{ $student->created_at->format('M d, Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="info-group">
                            <label class="info-label">Permanent Address</label>
                            <div class="info-value text-gray-700 leading-relaxed">{{ $student->address }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Emergency Contact</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="info-group">
                            <label class="info-label">Contact Name</label>
                            <div class="info-value">{{ $student->emergency_contact_name }}</div>
                        </div>
                        <div class="info-group">
                            <label class="info-label">Contact Phone</label>
                            <div class="info-value">{{ $student->emergency_contact_phone }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Booking -->
            @if($student->currentBooking)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Current Accommodation</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div>
                                <label class="info-label">Room Number</label>
                                <div class="info-value">{{ $student->currentBooking->room->room_number }}</div>
                            </div>
                            <div>
                                <label class="info-label">Hostel</label>
                                <div class="info-value">{{ $student->currentBooking->room->hostel->name ?? 'No Hostel' }}</div>
                            </div>
                            <div>
                                <label class="info-label">Room Type</label>
                                <div class="info-value capitalize">{{ $student->currentBooking->room->type }}</div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <label class="info-label">Check-in Date</label>
                                <div class="info-value">{{ $student->currentBooking->check_in->format('M d, Y') }}</div>
                            </div>
                            <div>
                                <label class="info-label">Check-out Date</label>
                                <div class="info-value">{{ $student->currentBooking->check_out->format('M d, Y') }}</div>
                            </div>
                            <div>
                                <label class="info-label">Days Remaining</label>
                                <div class="info-value">
                                    @php
                                        $today = now();
                                        $daysRemaining = $today->diffInDays($student->currentBooking->check_out, false);
                                    @endphp
                                    @if($daysRemaining > 0)
                                        <span class="text-green-600">{{ $daysRemaining }} days</span>
                                    @elseif($daysRemaining == 0)
                                        <span class="text-orange-600">Last day</span>
                                    @else
                                        <span class="text-red-600">Overdue by {{ abs($daysRemaining) }} days</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-4">
                        <a href="{{ route('admin.bookings.show', $student->currentBooking) }}" class="btn btn-secondary">
                            <i class="fas fa-calendar"></i>
                            View Booking Details
                        </a>
                        <a href="{{ route('admin.rooms.show', $student->currentBooking->room) }}" class="btn btn-secondary">
                            <i class="fas fa-bed"></i>
                            View Room Details
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Booking History -->
            @if($student->bookings->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Booking History</h3>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Room</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($student->bookings->sortByDesc('created_at')->take(5) as $booking)
                                <tr>
                                    <td>#{{ $booking->id }}</td>
                                    <td>
                                        <div class="font-medium">{{ $booking->room->room_number }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->room->hostel->name ?? 'No Hostel' }}</div>
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
                    
                    @if($student->bookings->count() > 5)
                    <div class="card-footer text-center">
                        <a href="{{ route('admin.students.bookingHistory', $student) }}" class="btn btn-secondary">
                            View Complete History
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif
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
                        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-primary w-full justify-center">
                            <i class="fas fa-edit"></i>
                            Edit Student
                        </a>
                        
                        @if(!$student->is_active)
                        <a href="{{ route('admin.bookings.create', ['student_id' => $student->id]) }}" 
                           class="btn btn-success w-full justify-center">
                            <i class="fas fa-calendar-plus"></i>
                            Create Booking
                        </a>
                        @else
                        <a href="{{ route('admin.bookings.show', $student->currentBooking) }}" 
                           class="btn btn-warning w-full justify-center">
                            <i class="fas fa-calendar-check"></i>
                            Current Booking
                        </a>
                        @endif

                        <a href="mailto:{{ $student->email }}" class="btn btn-secondary w-full justify-center">
                            <i class="fas fa-envelope"></i>
                            Send Email
                        </a>

                        <a href="tel:{{ $student->phone }}" class="btn btn-secondary w-full justify-center">
                            <i class="fas fa-phone"></i>
                            Call Student
                        </a>

                        <a href="{{ route('admin.students.bookingHistory', $student) }}" class="btn btn-secondary w-full justify-center">
                            <i class="fas fa-history"></i>
                            Booking History
                        </a>

                        <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-full justify-center" 
                                    onclick="return confirm('Are you sure you want to delete this student? This action cannot be undone.')">
                                <i class="fas fa-trash"></i>
                                Delete Student
                            </button>
                        </form>

                        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary w-full justify-center">
                            <i class="fas fa-arrow-left"></i>
                            Back to Students
                        </a>
                    </div>
                </div>
            </div>

            <!-- Student Statistics -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Student Statistics</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="stat-item">
                            <label>Total Bookings</label>
                            <div class="stat-value">{{ $bookingStats['total'] }}</div>
                        </div>
                        <div class="stat-item">
                            <label>Active Bookings</label>
                            <div class="stat-value">{{ $bookingStats['active'] }}</div>
                        </div>
                        <div class="stat-item">
                            <label>Completed Stays</label>
                            <div class="stat-value">{{ $bookingStats['completed'] }}</div>
                        </div>
                        <div class="stat-item">
                            <label>Cancelled Bookings</label>
                            <div class="stat-value">{{ $bookingStats['cancelled'] }}</div>
                        </div>
                        <div class="stat-item">
                            <label>Member Since</label>
                            <div class="stat-value text-sm">{{ $student->created_at->diffForHumans() }}</div>
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
                                <div class="text-sm text-gray-600">{{ $student->email }}</div>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone text-green-600"></i>
                            <div>
                                <div class="font-medium">Phone</div>
                                <div class="text-sm text-gray-600">{{ $student->phone }}</div>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-university text-purple-600"></i>
                            <div>
                                <div class="font-medium">University</div>
                                <div class="text-sm text-gray-600">{{ $student->university }}</div>
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

    .student-status, .booking-status {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .student-status.status-active, .booking-status.status-checked_in {
        background-color: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .student-status.status-inactive {
        background-color: rgba(107, 114, 128, 0.1);
        color: var(--gray-600);
    }

    .booking-status.status-pending {
        background-color: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    .booking-status.status-confirmed {
        background-color: rgba(59, 130, 246, 0.1);
        color: var(--primary);
    }

    .booking-status.status-checked_out {
        background-color: rgba(107, 114, 128, 0.1);
        color: var(--gray-600);
    }

    .booking-status.status-cancelled {
        background-color: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .student-avatar-large {
        width: 5rem;
        height: 5rem;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 2rem;
        flex-shrink: 0;
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

    .btn-sm {
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
    }
</style>
@endsection