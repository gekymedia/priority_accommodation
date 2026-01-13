<!-- resources/views/bookings/show.blade.php -->
@extends('layouts.app')

@section('title', 'Booking #' . $booking->id . ' - Priority Accommodations')
@section('page-title', 'Booking #' . $booking->id)
@section('page-subtitle', 'Booking details and management')

@section('content')
<div class="fade-in">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Booking Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking Overview Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Booking Overview</h3>
                    <div class="flex gap-2">
                        <span class="booking-status status-{{ $booking->status }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                        @if($booking->is_active)
                            <span class="booking-status status-confirmed">Active</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Booking Information -->
                        <div class="space-y-4">
                            <div class="info-group">
                                <label class="info-label">Booking Reference</label>
                                <div class="info-value">#{{ $booking->id }}</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Created On</label>
                                <div class="info-value">{{ $booking->created_at->format('M d, Y h:i A') }}</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Duration</label>
                                <div class="info-value">{{ $booking->duration }} days</div>
                            </div>
                            @if($booking->special_requirements)
                            <div class="info-group">
                                <label class="info-label">Special Requirements</label>
                                <div class="info-value text-gray-700">{{ $booking->special_requirements }}</div>
                            </div>
                            @endif
                        </div>

                        <!-- Dates Information -->
                        <div class="space-y-4">
                            <div class="info-group">
                                <label class="info-label">Check-in Date</label>
                                <div class="info-value">{{ $booking->check_in->format('M d, Y') }}</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Check-out Date</label>
                                <div class="info-value">{{ $booking->check_out->format('M d, Y') }}</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">Days Remaining</label>
                                <div class="info-value">
                                    @php
                                        $today = now();
                                        $daysRemaining = $today->diffInDays($booking->check_out, false);
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
                </div>
            </div>

            <!-- Student Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Student Information</h3>
                </div>
                <div class="card-body">
                    <div class="flex items-start space-x-4">
                        <div class="student-avatar-large">
                            {{ strtoupper(substr($booking->student->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-3">
                                <div>
                                    <label class="info-label">Full Name</label>
                                    <div class="info-value">{{ $booking->student->name }}</div>
                                </div>
                                <div>
                                    <label class="info-label">Email</label>
                                    <div class="info-value">{{ $booking->student->email }}</div>
                                </div>
                                <div>
                                    <label class="info-label">Phone</label>
                                    <div class="info-value">{{ $booking->student->phone ?? 'Not provided' }}</div>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="info-label">Student ID</label>
                                    <div class="info-value">{{ $booking->student->student_id ?? 'Not provided' }}</div>
                                </div>
                                <div>
                                    <label class="info-label">University</label>
                                    <div class="info-value">{{ $booking->student->university ?? 'Not provided' }}</div>
                                </div>
                                <div>
                                    <label class="info-label">Course</label>
                                    <div class="info-value">{{ $booking->student->course ?? 'Not provided' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-4">
                        <a href="{{ route('admin.students.show', $booking->student) }}" class="btn btn-secondary">
                            <i class="fas fa-user"></i>
                            View Student Profile
                        </a>
                        <a href="mailto:{{ $booking->student->email }}" class="btn btn-secondary">
                            <i class="fas fa-envelope"></i>
                            Send Email
                        </a>
                    </div>
                </div>
            </div>

            <!-- Room Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Room Information</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div>
                                <label class="info-label">Room Number</label>
                                <div class="info-value">{{ $booking->room->room_number }}</div>
                            </div>
                            <div>
                                <label class="info-label">Hostel</label>
                                <div class="info-value">{{ $booking->room->hostel->name ?? 'No Hostel' }}</div>
                            </div>
                            <div>
                                <label class="info-label">Room Type</label>
                                <div class="info-value capitalize">{{ $booking->room->type }}</div>
                            </div>
                            <div>
                                <label class="info-label">Capacity</label>
                                <div class="info-value">{{ $booking->room->capacity }} person(s)</div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <label class="info-label">Price per Semester</label>
                                <div class="info-value text-green-600">₵{{ number_format($booking->room->price_per_semester, 2) }}</div>
                            </div>
                            <div>
                                <label class="info-label">Room Status</label>
                                <div class="info-value">
                                    <span class="status-badge status-{{ $booking->room->status }}">
                                        {{ ucfirst($booking->room->status) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="info-label">Availability</label>
                                <div class="info-value">
                                    <span class="availability-badge availability-{{ $booking->room->available ? 'yes' : 'no' }}">
                                        {{ $booking->room->available ? 'Available' : 'Not Available' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-4">
                        <a href="{{ route('admin.rooms.show', $booking->room) }}" class="btn btn-secondary">
                            <i class="fas fa-bed"></i>
                            View Room Details
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Actions & Financial -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-primary w-full justify-center">
                            <i class="fas fa-edit"></i>
                            Edit Booking
                        </a>
                        
                        @if($booking->status == 'pending')
                        <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="btn btn-success w-full justify-center">
                                <i class="fas fa-check"></i>
                                Confirm Booking
                            </button>
                        </form>
                        @endif

                        @if($booking->status == 'confirmed')
                        <form action="{{ route('admin.bookings.checkin', $booking) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="btn btn-success w-full justify-center">
                                <i class="fas fa-sign-in-alt"></i>
                                Check In Student
                            </button>
                        </form>
                        @endif

                        @if($booking->status == 'checked_in')
                        <form action="{{ route('admin.bookings.checkout', $booking) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="btn btn-warning w-full justify-center">
                                <i class="fas fa-sign-out-alt"></i>
                                Check Out Student
                            </button>
                        </form>
                        @endif

                        <a href="{{ route('admin.bookings.invoice', $booking) }}" class="btn btn-secondary w-full justify-center" target="_blank">
                            <i class="fas fa-file-invoice"></i>
                            Generate Invoice
                        </a>

                        <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-full justify-center" 
                                    onclick="return confirm('Are you sure you want to delete this booking? This action cannot be undone.')">
                                <i class="fas fa-trash"></i>
                                Delete Booking
                            </button>
                        </form>

                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary w-full justify-center">
                            <i class="fas fa-arrow-left"></i>
                            Back to Bookings
                        </a>
                    </div>
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Financial Summary</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Amount:</span>
                            <span class="font-bold text-green-600">₵{{ number_format($booking->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Advance Paid:</span>
                            <span class="font-medium">₵{{ number_format($booking->advance_paid, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center border-t border-gray-200 pt-2">
                            <span class="text-gray-800 font-semibold">Balance Due:</span>
                            <span class="font-bold text-orange-600">₵{{ number_format($booking->balance_due, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            @if($booking->payments->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payment History</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        @foreach($booking->payments->take(3) as $payment)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <div class="font-medium">₵{{ number_format($payment->amount, 2) }}</div>
                                <div class="text-sm text-gray-500">{{ $payment->created_at->format('M d, Y') }}</div>
                            </div>
                            <span class="payment-status status-{{ $payment->status }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>
                        @endforeach
                        @if($booking->payments->count() > 3)
                        <div class="text-center">
                            <a href="{{ route('admin.payments.index', ['booking_id' => $booking->id]) }}" class="btn btn-secondary btn-sm">
                                View All Payments
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Booking Timeline -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Booking Timeline</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="timeline-item">
                            <div class="timeline-dot dot-completed"></div>
                            <div class="timeline-content">
                                <div class="timeline-title">Booking Created</div>
                                <div class="timeline-date">{{ $booking->created_at->format('M d, Y h:i A') }}</div>
                            </div>
                        </div>
                        
                        @if($booking->status != 'pending')
                        <div class="timeline-item">
                            <div class="timeline-dot dot-completed"></div>
                            <div class="timeline-content">
                                <div class="timeline-title">Booking Confirmed</div>
                                <div class="timeline-date">{{ $booking->updated_at->format('M d, Y h:i A') }}</div>
                            </div>
                        </div>
                        @endif

                        @if($booking->status == 'checked_in')
                        <div class="timeline-item">
                            <div class="timeline-dot dot-completed"></div>
                            <div class="timeline-content">
                                <div class="timeline-title">Student Checked In</div>
                                <div class="timeline-date">--</div>
                            </div>
                        </div>
                        @endif

                        @if($booking->status == 'checked_out')
                        <div class="timeline-item">
                            <div class="timeline-dot dot-completed"></div>
                            <div class="timeline-content">
                                <div class="timeline-title">Student Checked Out</div>
                                <div class="timeline-date">--</div>
                            </div>
                        </div>
                        @endif

                        <div class="timeline-item">
                            <div class="timeline-dot {{ in_array($booking->status, ['checked_out', 'cancelled']) ? 'dot-completed' : 'dot-pending' }}"></div>
                            <div class="timeline-content">
                                <div class="timeline-title">Booking Completed</div>
                                <div class="timeline-date">--</div>
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

    .booking-status, .payment-status, .status-badge, .availability-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-pending, .payment-status-pending {
        background-color: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    .status-confirmed, .payment-status-completed {
        background-color: rgba(59, 130, 246, 0.1);
        color: var(--primary);
    }

    .status-checked_in {
        background-color: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .status-checked_out {
        background-color: rgba(107, 114, 128, 0.1);
        color: var(--gray-600);
    }

    .status-cancelled, .payment-status-failed {
        background-color: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .availability-yes {
        background-color: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .availability-no {
        background-color: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .student-avatar-large {
        width: 4rem;
        height: 4rem;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    /* Timeline Styles */
    .timeline-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .timeline-dot {
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        flex-shrink: 0;
        margin-top: 0.25rem;
    }

    .dot-completed {
        background-color: var(--success);
    }

    .dot-pending {
        background-color: var(--gray-300);
        border: 2px solid var(--gray-400);
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-title {
        font-weight: 600;
        color: var(--gray-900);
        margin-bottom: 0.25rem;
    }

    .timeline-date {
        font-size: 0.875rem;
        color: var(--gray-500);
    }
</style>
@endsection