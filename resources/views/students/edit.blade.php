@extends('layouts.app')

@section('title', 'Edit Student - ' . $student->name)

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Student</h2>
            <p class="text-gray-600">Update student information and details</p>
        </div>
        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Students
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-edit text-blue-600 mr-2"></i>
                        Student Information
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.students.update', $student) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Personal Information -->
                        <div class="section">
                            <h4 class="section-title">
                                <i class="fas fa-id-card text-blue-600 mr-2"></i>
                                Personal Information
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label class="form-label">Student ID *</label>
                                    <input type="text" name="student_id" class="form-control" 
                                           value="{{ old('student_id', $student->student_id) }}" 
                                           placeholder="Enter student ID">
                                    @error('student_id')
                                    <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" name="name" class="form-control" 
                                           value="{{ old('name', $student->name) }}" 
                                           placeholder="Enter full name" required>
                                    @error('name')
                                    <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label class="form-label">Email Address *</label>
                                    <input type="email" name="email" class="form-control" 
                                           value="{{ old('email', $student->email) }}" 
                                           placeholder="Enter email address" required>
                                    @error('email')
                                    <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Phone Number *</label>
                                    <input type="text" name="phone" class="form-control" 
                                           value="{{ old('phone', $student->phone) }}" 
                                           placeholder="Enter phone number" required>
                                    @error('phone')
                                    <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control" 
                                           value="{{ old('date_of_birth', $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}" 
                                           max="{{ date('Y-m-d', strtotime('-16 years')) }}">
                                    @error('date_of_birth')
                                    <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Address *</label>
                                <textarea name="address" class="form-control" rows="3" 
                                          placeholder="Enter complete address" required>{{ old('address', $student->address) }}</textarea>
                                @error('address')
                                <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Academic Information -->
                        <div class="section">
                            <h4 class="section-title">
                                <i class="fas fa-graduation-cap text-green-600 mr-2"></i>
                                Academic Information
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="form-group">
                                    <label class="form-label">University *</label>
                                    <input type="text" name="university" class="form-control" 
                                           value="{{ old('university', $student->university) }}" 
                                           placeholder="e.g., University of Example" required>
                                    @error('university')
                                    <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Course *</label>
                                    <input type="text" name="course" class="form-control" 
                                           value="{{ old('course', $student->course) }}" 
                                           placeholder="e.g., BSc Computer Science" required>
                                    @error('course')
                                    <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Year of Study *</label>
                                    <select name="year_of_study" class="form-control" required>
                                        <option value="">Select Year</option>
                                        @for($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}" {{ old('year_of_study', $student->year_of_study) == $i ? 'selected' : '' }}>
                                                Year {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('year_of_study')
                                    <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="section">
                            <h4 class="section-title">
                                <i class="fas fa-phone-alt text-orange-600 mr-2"></i>
                                Emergency Contact
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label class="form-label">Emergency Contact Name *</label>
                                    <input type="text" name="emergency_contact_name" class="form-control" 
                                           value="{{ old('emergency_contact_name', $student->emergency_contact_name) }}" 
                                           placeholder="Emergency contact person" required>
                                    @error('emergency_contact_name')
                                    <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Emergency Contact Phone *</label>
                                    <input type="text" name="emergency_contact_phone" class="form-control" 
                                           value="{{ old('emergency_contact_phone', $student->emergency_contact_phone) }}" 
                                           placeholder="Emergency contact number" required>
                                    @error('emergency_contact_phone')
                                    <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Update Student
                            </button>
                            <a href="{{ route('admin.students.show', $student) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Current Details -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Current Details
                    </h3>
                </div>
                <div class="card-body">
                    <div class="student-details">
                        <div class="detail-item">
                            <div class="detail-label">Student ID</div>
                            <div class="detail-value">{{ $student->student_id }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Current Status</div>
                            <div class="detail-value">
                                <span class="student-status status-{{ $student->status }}">
                                    {{ $student->is_active ? 'Active Resident' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">University</div>
                            <div class="detail-value">{{ $student->university }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Course</div>
                            <div class="detail-value">{{ $student->course }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Year of Study</div>
                            <div class="detail-value">Year {{ $student->year_of_study }}</div>
                        </div>
                        @if($student->currentBooking)
                        <div class="detail-item">
                            <div class="detail-label">Current Room</div>
                            <div class="detail-value">
                                <div class="font-medium">{{ $student->currentBooking->room->room_number ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $student->currentBooking->room->hostel->name ?? 'No Hostel' }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card border-l-4 border-l-red-500">
                <div class="card-header bg-red-50">
                    <h3 class="card-title text-red-800">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Danger Zone
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-sm text-gray-600 mb-4">
                        Once you delete a student, all their data including booking history and payments will be permanently removed. This action cannot be undone.
                    </p>
                    <form action="{{ route('admin.students.destroy', $student) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this student? This will permanently delete all their data.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-full">
                            <i class="fas fa-trash"></i>
                            Delete Student
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .section {
        background: var(--gray-50);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        font-weight: 500;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--gray-300);
        border-radius: var(--radius);
        background: white;
        transition: all 0.2s ease;
        font-size: 0.875rem;
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

    .student-details {
        space-y: 0.75rem;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--gray-200);
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: var(--gray-600);
        font-weight: 500;
        font-size: 0.875rem;
    }

    .detail-value {
        color: var(--gray-900);
        font-weight: 600;
        text-align: right;
        font-size: 0.875rem;
    }

    .student-status {
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

    .bg-red-50 {
        background-color: rgba(239, 68, 68, 0.05);
    }

    .text-red-800 {
        color: #991b1b;
    }

    .border-l-red-500 {
        border-left-color: var(--danger);
    }

    .grid {
        display: grid;
    }

    .grid-cols-1 { grid-template-columns: repeat(1, 1fr); }
    .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
    .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }

    @media (min-width: 768px) {
        .md\:grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
        .md\:grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
    }

    @media (min-width: 1024px) {
        .lg\:grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
        .lg\:col-span-2 { grid-column: span 2 / span 2; }
    }

    .gap-4 { gap: 1rem; }
    .gap-6 { gap: 1.5rem; }

    .space-y-6 > * + * {
        margin-top: 1.5rem;
    }
</style>
@endsection