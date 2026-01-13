@extends('layouts.app')

@section('title', 'Edit Hostel - ' . $hostel->name)

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Edit Hostel</h1>
        <div class="page-actions">
            <a href="{{ route('admin.hostels.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back to Hostels
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-building text-blue-600 mr-2"></i>
                        Hostel Information
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.hostels.update', $hostel) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="section">
                            <h4 class="section-title">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                Basic Information
                            </h4>
                            <div class="form-group">
                                <label class="form-label">Hostel Name *</label>
                                <input type="text" name="name" class="form-control" 
                                       value="{{ old('name', $hostel->name) }}" 
                                       placeholder="Enter hostel name" required>
                                @error('name')
                                <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Address *</label>
                                <textarea name="address" class="form-control" rows="3" 
                                          placeholder="Enter complete hostel address" required>{{ old('address', $hostel->address) }}</textarea>
                                @error('address')
                                <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4" 
                                          placeholder="Enter hostel description, features, location advantages...">{{ old('description', $hostel->description) }}</textarea>
                                @error('description')
                                <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="section">
                            <h4 class="section-title">
                                <i class="fas fa-phone-alt text-green-600 mr-2"></i>
                                Contact Information
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label class="form-label">Contact Phone *</label>
                                    <input type="text" name="contact_phone" class="form-control" 
                                           value="{{ old('contact_phone', $hostel->contact_phone) }}" 
                                           placeholder="Contact phone number" required>
                                    @error('contact_phone')
                                    <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Contact Email *</label>
                                    <input type="email" name="contact_email" class="form-control" 
                                           value="{{ old('contact_email', $hostel->contact_email) }}" 
                                           placeholder="Contact email address" required>
                                    @error('contact_email')
                                    <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Amenities -->
                        <div class="section">
                            <h4 class="section-title">
                                <i class="fas fa-list-check text-orange-600 mr-2"></i>
                                Hostel Amenities
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                @php
                                    $commonAmenities = [
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
                                    $currentAmenities = old('amenities', $hostel->amenities ?? []);
                                @endphp
                                @foreach($commonAmenities as $value => $label)
                                    <label class="inline-flex items-center feature-checkbox">
                                        <input type="checkbox" name="amenities[]" value="{{ $value }}" 
                                               {{ in_array($value, $currentAmenities) ? 'checked' : '' }} class="checkbox-input">
                                        <span class="ml-2 text-sm">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('amenities')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Hostel Images -->
                        <div class="section">
                            <h4 class="section-title">
                                <i class="fas fa-images text-indigo-600 mr-2"></i>
                                Hostel Images
                            </h4>
                            
                            <!-- Cover Image -->
                            <div class="form-group">
                                <label class="form-label">Cover Image</label>
                                <div class="current-image-preview mb-3">
                                    @if($hostel->cover_image)
                                        <div class="relative inline-block">
                                            <img src="{{ asset('storage/' . $hostel->cover_image) }}" 
                                                 alt="Current Cover" class="cover-preview-img">
                                            <span class="image-label">Current Cover</span>
                                        </div>
                                    @else
                                        <div class="no-image-placeholder">
                                            <i class="fas fa-image"></i>
                                            <span>No cover image</span>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" name="cover_image" id="cover_image" 
                                       class="form-control-file" accept="image/*">
                                <p class="text-xs text-gray-500 mt-1">Upload a main cover image (Max 2MB). Recommended size: 800x400px</p>
                                <div id="coverImagePreview" class="mt-3"></div>
                                @error('cover_image')
                                <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Additional Images -->
                            <div class="form-group mt-4">
                                <label class="form-label">Additional Images</label>
                                
                                <!-- Existing Images -->
                                @if($hostel->images && count($hostel->images) > 0)
                                <div class="existing-images-grid mb-4">
                                    @foreach($hostel->images as $index => $image)
                                        <div class="existing-image-item" data-image="{{ $image }}">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Hostel Image {{ $index + 1 }}">
                                            <input type="hidden" name="existing_images[]" value="{{ $image }}">
                                            <button type="button" class="remove-existing-image" onclick="removeExistingImage(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <span class="image-number">{{ $index + 1 }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="no-images-message mb-4">
                                    <i class="fas fa-photo-video text-gray-400"></i>
                                    <span class="text-gray-500">No additional images uploaded yet</span>
                                </div>
                                @endif

                                <input type="file" name="images[]" id="images" 
                                       class="form-control-file" multiple accept="image/*">
                                <p class="text-xs text-gray-500 mt-1">Upload multiple images (Max 2MB each). You can select multiple files.</p>
                                <div id="multiImagePreview" class="mt-3 existing-images-grid"></div>
                                @error('images.*')
                                <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="section">
                            <h4 class="section-title">
                                <i class="fas fa-toggle-on text-purple-600 mr-2"></i>
                                Status
                            </h4>
                            <div class="form-group">
                                <label class="form-label">Hostel Status *</label>
                                <select name="is_active" class="form-control" required>
                                    <option value="1" {{ old('is_active', $hostel->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active', $hostel->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('is_active')
                                <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Update Hostel
                            </button>
                            <a href="{{ route('admin.hostels.show', $hostel) }}" class="btn btn-secondary">
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
                            <div class="detail-label">Hostel Name</div>
                            <div class="detail-value">{{ $hostel->name }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Current Status</div>
                            <div class="detail-value">
                                <span class="hostel-status status-{{ $hostel->is_active ? 'active' : 'inactive' }}">
                                    {{ $hostel->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Total Rooms</div>
                            <div class="detail-value">{{ $hostel->rooms_count }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Occupancy Rate</div>
                            <div class="detail-value">{{ $hostel->occupancy_rate }}%</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Contact Phone</div>
                            <div class="detail-value">{{ $hostel->contact_phone }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Contact Email</div>
                            <div class="detail-value">{{ $hostel->contact_email }}</div>
                        </div>
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
                        Once you delete a hostel, all its data including rooms and bookings will be permanently removed. This action cannot be undone.
                    </p>
                    <form action="{{ route('admin.hostels.destroy', $hostel) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this hostel? This will permanently delete all its data including rooms and bookings.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-full">
                            <i class="fas fa-trash"></i>
                            Delete Hostel
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

    .checkbox-input {
        width: 1rem;
        height: 1rem;
        border: 2px solid var(--gray-300);
        border-radius: var(--radius-sm);
        cursor: pointer;
    }

    .checkbox-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .feature-checkbox {
        padding: 0.5rem;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .feature-checkbox:hover {
        border-color: var(--primary);
        background-color: var(--primary-light);
    }

    .feature-checkbox.checked {
        border-color: var(--primary);
        background-color: var(--primary-light);
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

    /* Image Upload Styles */
    .form-control-file {
        display: block;
        width: 100%;
        padding: 0.5rem;
        border: 2px dashed var(--gray-300);
        border-radius: var(--radius);
        background: var(--gray-50);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .form-control-file:hover {
        border-color: var(--primary);
        background: var(--primary-light);
    }

    .cover-preview-img {
        max-width: 300px;
        max-height: 200px;
        object-fit: cover;
        border-radius: var(--radius);
        border: 2px solid var(--gray-200);
    }

    .image-label {
        position: absolute;
        bottom: 0.5rem;
        left: 0.5rem;
        padding: 0.25rem 0.5rem;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        font-size: 0.7rem;
        border-radius: 0.25rem;
    }

    .no-image-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 200px;
        height: 150px;
        background: var(--gray-100);
        border-radius: var(--radius);
        border: 2px dashed var(--gray-300);
        color: var(--gray-400);
    }

    .no-image-placeholder i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .existing-images-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 1rem;
    }

    .existing-image-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: var(--radius);
        overflow: hidden;
        border: 2px solid var(--gray-200);
    }

    .existing-image-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .existing-image-item .remove-existing-image {
        position: absolute;
        top: 0.25rem;
        right: 0.25rem;
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 50%;
        background: rgba(239, 68, 68, 0.9);
        color: white;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        transition: all 0.2s ease;
    }

    .existing-image-item .remove-existing-image:hover {
        background: var(--danger);
        transform: scale(1.1);
    }

    .existing-image-item .image-number {
        position: absolute;
        bottom: 0.25rem;
        left: 0.25rem;
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        font-weight: 600;
    }

    .no-images-message {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem;
        background: var(--gray-50);
        border-radius: var(--radius);
        border: 1px dashed var(--gray-300);
    }

    .no-images-message i {
        font-size: 1.5rem;
    }

    .new-image-preview {
        position: relative;
        aspect-ratio: 1;
        border-radius: var(--radius);
        overflow: hidden;
        border: 2px solid var(--success);
    }

    .new-image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .new-image-preview .new-badge {
        position: absolute;
        top: 0.25rem;
        left: 0.25rem;
        padding: 0.125rem 0.375rem;
        background: var(--success);
        color: white;
        font-size: 0.6rem;
        font-weight: 600;
        border-radius: 0.25rem;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update feature checkbox appearance
    document.querySelectorAll('.feature-checkbox input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                this.parentElement.classList.add('checked');
            } else {
                this.parentElement.classList.remove('checked');
            }
        });

        // Initialize checked state
        if (checkbox.checked) {
            checkbox.parentElement.classList.add('checked');
        }
    });

    // Phone number formatting
    const phoneInput = document.querySelector('input[name="contact_phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    }

    // Cover image preview
    const coverImageInput = document.getElementById('cover_image');
    const coverImagePreview = document.getElementById('coverImagePreview');
    
    if (coverImageInput) {
        coverImageInput.addEventListener('change', function() {
            coverImagePreview.innerHTML = '';
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    coverImagePreview.innerHTML = `
                        <div class="relative inline-block">
                            <img src="${e.target.result}" class="cover-preview-img" alt="New Cover Preview">
                            <span class="image-label" style="background: var(--success);">New Cover</span>
                        </div>
                    `;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    // Multiple images preview
    const imagesInput = document.getElementById('images');
    const multiImagePreview = document.getElementById('multiImagePreview');
    
    if (imagesInput) {
        imagesInput.addEventListener('change', function() {
            multiImagePreview.innerHTML = '';
            if (this.files) {
                Array.from(this.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'new-image-preview';
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="New Image ${index + 1}">
                            <span class="new-badge">NEW</span>
                        `;
                        multiImagePreview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    }
});

// Remove existing image
function removeExistingImage(button) {
    const imageItem = button.closest('.existing-image-item');
    if (confirm('Are you sure you want to remove this image?')) {
        imageItem.remove();
        
        // Update the remaining image numbers
        document.querySelectorAll('.existing-image-item .image-number').forEach((num, index) => {
            num.textContent = index + 1;
        });
    }
}
</script>
@endsection