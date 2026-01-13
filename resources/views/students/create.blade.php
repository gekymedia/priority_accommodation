<!-- resources/views/students/create.blade.php -->
@extends('layouts.app')

@section('title', 'Add New Student - Priority Accommodations')
@section('page-title', 'Add New Student')
@section('page-subtitle', 'Create a new student record')

@section('content')
<div class="fade-in">
    <!-- Session Messages -->
    @if(session('error'))
        <div class="alert alert-danger mb-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success mb-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Validation Errors -->
    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span class="font-medium">Please fix the following errors:</span>
            </div>
            <ul class="mt-2 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Student Information</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.students.store') }}" id="studentForm">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column - Personal Information -->
                    <div class="space-y-6">
                        <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Personal Information</h4>
                        
                        <!-- Name -->
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name <span class="required">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" 
                                   value="{{ old('name') }}" placeholder="Enter student's full name" required>
                            @error('name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address <span class="required">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" 
                                   value="{{ old('email') }}" placeholder="student@university.edu" required>
                            @error('email')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number <span class="required">*</span></label>
                            <input type="tel" name="phone" id="phone" class="form-control" 
                                   value="{{ old('phone') }}" placeholder="+1234567890" required>
                            @error('phone')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Student ID -->
                        <div class="form-group">
                            <label for="student_id" class="form-label">Student ID</label>
                            <input type="text" name="student_id" id="student_id" class="form-control" 
                                   value="{{ old('student_id') }}" placeholder="Leave blank to auto-generate">
                            @error('student_id')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                            <div class="form-hint">If left blank, a student ID will be automatically generated</div>
                        </div>

                        <!-- Date of Birth -->
                        <div class="form-group">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" 
                                   value="{{ old('date_of_birth') }}" max="{{ date('Y-m-d', strtotime('-16 years')) }}">
                            @error('date_of_birth')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column - Academic & Emergency Information -->
                    <div class="space-y-6">
                        <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Academic Information</h4>
                      
                        <!-- Add this after the University field -->
                        <div class="form-group">
                            <label for="department" class="form-label">Department <span class="required">*</span></label>
                            <input type="text" name="department" id="department" class="form-control" 
                                value="{{ old('department') }}" placeholder="e.g., Computer Science Department" required>
                            @error('department')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- University -->
                        <div class="form-group">
                            <label for="university" class="form-label">University <span class="required">*</span></label>
                            <input type="text" name="university" id="university" class="form-control" 
                                   value="{{ old('university') }}" placeholder="University name" required>
                            @error('university')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Course -->
                        <div class="form-group">
                            <label for="course" class="form-label">Course/Program <span class="required">*</span></label>
                            <input type="text" name="course" id="course" class="form-control" 
                                   value="{{ old('course') }}" placeholder="e.g., Computer Science, Engineering" required>
                            @error('course')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Year of Study -->
                        <div class="form-group">
                            <label for="year_of_study" class="form-label">Year of Study <span class="required">*</span></label>
                            <select name="year_of_study" id="year_of_study" class="form-control" required>
                                <option value="">Select Year</option>
                                @for($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}" {{ old('year_of_study') == $i ? 'selected' : '' }}>
                                        Year {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('year_of_study')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mt-8">Emergency Contact</h4>
                        
                        <!-- Emergency Contact Name -->
                        <div class="form-group">
                            <label for="emergency_contact_name" class="form-label">Emergency Contact Name <span class="required">*</span></label>
                            <input type="text" name="emergency_contact_name" id="emergency_contact_name" class="form-control" 
                                   value="{{ old('emergency_contact_name') }}" placeholder="Full name of emergency contact" required>
                            @error('emergency_contact_name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Emergency Contact Phone -->
                        <div class="form-group">
                            <label for="emergency_contact_phone" class="form-label">Emergency Contact Phone <span class="required">*</span></label>
                            <input type="tel" name="emergency_contact_phone" id="emergency_contact_phone" class="form-control" 
                                   value="{{ old('emergency_contact_phone') }}" placeholder="+1234567890" required>
                            @error('emergency_contact_phone')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="form-group mt-6">
                    <label for="address" class="form-label">Permanent Address <span class="required">*</span></label>
                    <textarea name="address" id="address" class="form-control" rows="3" 
                              placeholder="Enter complete permanent address..." required>{{ old('address') }}</textarea>
                    @error('address')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- ID Proof & Photo Placeholder -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div class="form-group">
                        <label class="form-label">ID Proof Upload</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <i class="fas fa-id-card text-gray-400 text-3xl mb-3"></i>
                            <p class="text-gray-600 mb-2">ID proof upload feature coming soon</p>
                            <p class="text-sm text-gray-500">You'll be able to upload student's ID proof document</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Student Photo</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <i class="fas fa-camera text-gray-400 text-3xl mb-3"></i>
                            <p class="text-gray-600 mb-2">Photo upload feature coming soon</p>
                            <p class="text-sm text-gray-500">You'll be able to upload student's profile photo</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Create Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .alert {
        padding: 1rem;
        border-radius: 0.375rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .error-message {
        color: var(--danger);
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .required {
        color: var(--danger);
    }

    .form-hint {
        font-size: 0.75rem;
        color: var(--gray);
        margin-top: 0.25rem;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    document.getElementById('studentForm').addEventListener('submit', function(e) {
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

        if (!valid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });

    // Phone number formatting
    const phoneInput = document.getElementById('phone');
    const emergencyPhoneInput = document.getElementById('emergency_contact_phone');

    [phoneInput, emergencyPhoneInput].forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    });
});
</script>
@endsection