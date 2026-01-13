<!-- resources/views/bookings/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Booking - Priority Accommodations')
@section('page-title', 'Edit Booking')
@section('page-subtitle', 'Update booking information')

@section('content')
<div class="fade-in">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Booking #{{ $booking->id }}</h3>
            <div class="flex gap-2">
                <span class="booking-status status-{{ $booking->status }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" id="bookingForm">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column - Student & Room Selection -->
                    <div class="space-y-6">
                        <!-- Student Selection -->
                        <div class="form-group">
                            <label for="student_id" class="form-label">Student <span class="required">*</span></label>
                            <select name="student_id" id="student_id" class="form-control" required>
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id', $booking->student_id) == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }} - {{ $student->email }} ({{ $student->phone }})
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Room Selection -->
                        <div class="form-group">
                            <label for="room_id" class="form-label">Room <span class="required">*</span></label>
                            <select name="room_id" id="room_id" class="form-control" required>
                                <option value="">Select Room</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" 
                                            {{ old('room_id', $booking->room_id) == $room->id ? 'selected' : '' }}
                                            data-price="{{ $room->price_per_semester }}"
                                            data-type="{{ $room->type }}"
                                            data-capacity="{{ $room->capacity }}"
                                            data-hostel="{{ $room->hostel->name ?? 'No Hostel' }}">
                                        {{ $room->room_number }} - {{ $room->hostel->name ?? 'No Hostel' }} 
                                        ({{ ucfirst($room->type) }}, {{ $room->capacity }} person, ₵{{ number_format($room->price_per_semester) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Room Details Preview -->
                        <div id="roomDetails" class="{{ $booking->room ? '' : 'hidden' }}">
                            <div class="card bg-gray-50">
                                <div class="card-body">
                                    <h4 class="font-semibold text-gray-900 mb-3">Selected Room Details</h4>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-600">Type:</span>
                                            <span id="roomType" class="font-medium ml-2">{{ $booking->room->type ?? '' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Capacity:</span>
                                            <span id="roomCapacity" class="font-medium ml-2">{{ $booking->room->capacity ?? '' }} person(s)</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Hostel:</span>
                                            <span id="roomHostel" class="font-medium ml-2">{{ $booking->room->hostel->name ?? 'No Hostel' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Price/Semester:</span>
                                            <span id="roomPrice" class="font-medium ml-2 text-green-600">
                                                ₵{{ number_format($booking->room->price_per_semester ?? 0) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="check_in" class="form-label">Check-in Date <span class="required">*</span></label>
                                <input type="date" name="check_in" id="check_in" class="form-control" 
                                       value="{{ old('check_in', $booking->check_in->format('Y-m-d')) }}" required>
                                @error('check_in')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="check_out" class="form-label">Check-out Date <span class="required">*</span></label>
                                <input type="date" name="check_out" id="check_out" class="form-control" 
                                       value="{{ old('check_out', $booking->check_out->format('Y-m-d')) }}" required>
                                @error('check_out')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Payment & Status -->
                    <div class="space-y-6">
                        <!-- Status -->
                        <div class="form-group">
                            <label for="status" class="form-label">Booking Status <span class="required">*</span></label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="">Select Status</option>
                                @foreach($bookingStatuses as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $booking->status) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Amount Information -->
                        <div class="form-group">
                            <label for="total_amount" class="form-label">Total Amount (₵) <span class="required">*</span></label>
                            <input type="number" name="total_amount" id="total_amount" class="form-control" 
                                   value="{{ old('total_amount', $booking->total_amount) }}" placeholder="0.00" min="0" step="0.01" required>
                            @error('total_amount')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Advance Payment -->
                        <div class="form-group">
                            <label for="advance_paid" class="form-label">Advance Paid (₵)</label>
                            <input type="number" name="advance_paid" id="advance_paid" class="form-control" 
                                   value="{{ old('advance_paid', $booking->advance_paid) }}" placeholder="0.00" min="0" step="0.01">
                            @error('advance_paid')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Balance Display -->
                        <div id="balanceDisplay">
                            <div class="card bg-orange-50">
                                <div class="card-body">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700 font-semibold">Balance Due:</span>
                                        <span id="balanceDue" class="font-bold text-orange-600 text-lg">
                                            ₵{{ number_format($booking->balance_due, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Special Requirements -->
                        <div class="form-group">
                            <label for="special_requirements" class="form-label">Special Requirements</label>
                            <textarea name="special_requirements" id="special_requirements" class="form-control" rows="4" 
                                      placeholder="Any special requirements, notes, or comments...">{{ old('special_requirements', $booking->special_requirements) }}</textarea>
                            @error('special_requirements')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-between gap-3 pt-6 border-t border-gray-200">
                    <div>
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-secondary">
                            <i class="fas fa-eye"></i>
                            View Booking
                        </a>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Update Booking
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .error-message {
        color: var(--danger);
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .required {
        color: var(--danger);
    }

    .hidden {
        display: none;
    }

    .booking-status {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-pending {
        background-color: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    .status-confirmed {
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

    .status-cancelled {
        background-color: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roomSelect = document.getElementById('room_id');
    const roomDetails = document.getElementById('roomDetails');
    const advancePaidInput = document.getElementById('advance_paid');
    const totalAmountInput = document.getElementById('total_amount');

    // Room selection handler
    roomSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            // Update room details
            document.getElementById('roomType').textContent = selectedOption.getAttribute('data-type');
            document.getElementById('roomCapacity').textContent = selectedOption.getAttribute('data-capacity') + ' person(s)';
            document.getElementById('roomHostel').textContent = selectedOption.getAttribute('data-hostel');
            document.getElementById('roomPrice').textContent = '₵' + parseFloat(selectedOption.getAttribute('data-price')).toLocaleString();
            
            roomDetails.classList.remove('hidden');
        } else {
            roomDetails.classList.add('hidden');
        }
    });

    // Balance calculation
    function calculateBalance() {
        const totalAmount = parseFloat(totalAmountInput.value) || 0;
        const advancePaid = parseFloat(advancePaidInput.value) || 0;
        const balanceDue = totalAmount - advancePaid;
        
        document.getElementById('balanceDue').textContent = '₵' + balanceDue.toLocaleString();
    }

    advancePaidInput.addEventListener('input', calculateBalance);
    totalAmountInput.addEventListener('input', calculateBalance);

    // Form validation
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        const requiredFields = this.querySelectorAll('[required]');
        let valid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                valid = false;
                field.style.borderColor = 'var(--danger)';
            } else {
                field.style.borderColor = 'var(--gray-200)';
            }
        });

        // Check if check-out is after check-in
        const checkIn = new Date(document.getElementById('check_in').value);
        const checkOut = new Date(document.getElementById('check_out').value);
        if (checkOut <= checkIn) {
            valid = false;
            document.getElementById('check_out').style.borderColor = 'var(--danger)';
            alert('Check-out date must be after check-in date.');
        }

        if (!valid) {
            e.preventDefault();
            alert('Please fill in all required fields correctly.');
        }
    });

    // Initialize room details if room is selected
    if (roomSelect.value) {
        roomSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection