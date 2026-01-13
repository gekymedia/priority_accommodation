@extends('layouts.app')

@section('title', 'Submit Complaint - Priority Accommodations')

@section('content')
<div class="page-header">
    <h1 class="page-title">Submit a Complaint</h1>
    <a href="{{ route('complaints.my-complaints') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
        My Complaints
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Complaint Details</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('complaints.store') }}">
            @csrf

            @if($currentBooking)
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Current Booking:</strong> {{ $currentBooking->room->name ?? 'N/A' }} 
                @if($currentBooking->hostel)
                    - {{ $currentBooking->hostel->name }}
                @endif
            </div>
            @endif

            <div class="form-group">
                <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                <input type="text" 
                       id="subject" 
                       name="subject" 
                       class="form-control @error('subject') is-invalid @enderror" 
                       value="{{ old('subject') }}" 
                       placeholder="Brief description of your complaint"
                       required>
                @error('subject')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                <textarea id="description" 
                          name="description" 
                          class="form-control @error('description') is-invalid @enderror" 
                          rows="6" 
                          placeholder="Please provide detailed information about your complaint..."
                          required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Minimum 10 characters required</small>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                    <select id="category" 
                            name="category" 
                            class="form-control @error('category') is-invalid @enderror" 
                            required>
                        <option value="">Select Category</option>
                        @foreach(\App\Models\Complaint::getCategories() as $value => $label)
                            <option value="{{ $value }}" {{ old('category') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                    <select id="priority" 
                            name="priority" 
                            class="form-control @error('priority') is-invalid @enderror" 
                            required>
                        <option value="">Select Priority</option>
                        @foreach(\App\Models\Complaint::getPriorities() as $value => $label)
                            <option value="{{ $value }}" {{ old('priority', 'medium') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('priority')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="booking_id" class="form-label">Related Booking (Optional)</label>
                <select id="booking_id" 
                        name="booking_id" 
                        class="form-control @error('booking_id') is-invalid @enderror">
                    <option value="">Select Booking (if applicable)</option>
                    @if($currentBooking)
                        <option value="{{ $currentBooking->id }}" {{ old('booking_id') == $currentBooking->id ? 'selected' : '' }}>
                            Current: {{ $currentBooking->room->name ?? 'N/A' }} - {{ $currentBooking->hostel->name ?? 'N/A' }}
                        </option>
                    @endif
                    @foreach($bookings as $booking)
                        @if(!$currentBooking || $booking->id != $currentBooking->id)
                        <option value="{{ $booking->id }}" {{ old('booking_id') == $booking->id ? 'selected' : '' }}>
                            {{ $booking->room->name ?? 'N/A' }} - {{ $booking->hostel->name ?? 'N/A' }} 
                            ({{ $booking->status }})
                        </option>
                        @endif
                    @endforeach
                </select>
                @error('booking_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    Submit Complaint
                </button>
                <a href="{{ route('complaints.my-complaints') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

