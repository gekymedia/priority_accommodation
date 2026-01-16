@extends('layouts.app')

@section('title', 'Edit Payment - Priority Accommodations')
@section('page-title', 'Edit Payment')
@section('page-subtitle', 'Update payment information')

@section('content')
<div class="fade-in">
    <div class="max-w-4xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit text-blue-600 mr-2"></i>
                    Edit Payment - #{{ $payment->receipt_number }}
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.payments.update', $payment) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Student Selection -->
                        <div class="form-group">
                            <label class="form-label">Student *</label>
                            <select name="student_id" class="form-control" required>
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id', $payment->student_id) == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} ({{ $student->student_id }})
                                </option>
                                @endforeach
                            </select>
                            @error('student_id')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Booking Selection -->
                        <div class="form-group">
                            <label class="form-label">Booking <span class="text-gray-500 text-sm">(Optional)</span></label>
                            <select name="booking_id" class="form-control">
                                <option value="">Select Booking (Optional - Not required for cash payments)</option>
                                @foreach($bookings as $booking)
                                <option value="{{ $booking->id }}" {{ old('booking_id', $payment->booking_id) == $booking->id ? 'selected' : '' }}>
                                    Room {{ $booking->room->room_number }} - {{ $booking->student->name }}
                                </option>
                                @endforeach
                            </select>
                            <small class="text-gray-500 text-sm">Leave blank if this is a standalone cash payment</small>
                            @error('booking_id')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div class="form-group">
                            <label class="form-label">Amount (₵) *</label>
                            <input type="number" name="amount" class="form-control" 
                                   value="{{ old('amount', $payment->amount) }}" step="0.01" min="0" 
                                   placeholder="Enter amount" required>
                            @error('amount')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div class="form-group">
                            <label class="form-label">Payment Method *</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="">Select Payment Method</option>
                                @foreach($paymentMethods as $value => $label)
                                <option value="{{ $value }}" {{ old('payment_method', $payment->payment_method) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            @error('payment_method')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Payment Type -->
                        <div class="form-group">
                            <label class="form-label">Payment Type *</label>
                            <select name="type" class="form-control" required>
                                <option value="">Select Payment Type</option>
                                @foreach($paymentTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('type', $payment->type) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            @error('type')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Payment Date -->
                        <div class="form-group">
                            <label class="form-label">Payment Date *</label>
                            <input type="date" name="payment_date" class="form-control" 
                                   value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" required>
                            @error('payment_date')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label class="form-label">Status *</label>
                            <select name="status" class="form-control" required>
                                <option value="pending" {{ old('status', $payment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ old('status', $payment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="failed" {{ old('status', $payment->status) == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                            @error('status')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Transaction ID -->
                        <div class="form-group">
                            <label class="form-label">Transaction ID <span class="text-gray-500 text-sm">(Optional)</span></label>
                            <input type="text" name="transaction_id" class="form-control" 
                                   value="{{ old('transaction_id', $payment->transaction_id) }}" 
                                   placeholder="Enter transaction ID (not required for cash payments)">
                            <small class="text-gray-500 text-sm">Only needed for bank transfers, card payments, etc.</small>
                            @error('transaction_id')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" 
                                  placeholder="Enter payment description (optional)">{{ old('description', $payment->description) }}</textarea>
                        @error('description')
                        <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Update Payment
                        </button>
                        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--gray-200);
        border-radius: var(--radius);
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .form-error {
        color: var(--danger);
        font-size: 0.75rem;
        margin-top: 0.25rem;
        font-weight: 500;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-start;
        padding-top: 1rem;
        border-top: 1px solid var(--gray-200);
    }

    .grid {
        display: grid;
    }

    .grid-cols-1 { grid-template-columns: repeat(1, 1fr); }
    .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }

    @media (min-width: 768px) {
        .md\:grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
    }

    .gap-6 { gap: 1.5rem; }
    .max-w-4xl { max-width: 56rem; }
    .mx-auto { margin-left: auto; margin-right: auto; }
</style>
@endsection