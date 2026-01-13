@extends('layouts.app')

@section('title', 'Add New Hostel - Priority Accommodations')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Add New <span class="page-title-highlight">Hostel</span></h1>
            <p class="text-gray-500 text-sm mt-1">Create a new hostel property with images and details</p>
        </div>
        <a href="{{ route('admin.hostels.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Hostels
        </a>
    </div>

    <form method="POST" action="{{ route('admin.hostels.store') }}" enctype="multipart/form-data" id="hostelForm">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Basic Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle"></i>
                            Basic Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div class="form-group md:col-span-2">
                                <label for="name" class="form-label">Hostel Name <span class="required">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" 
                                       value="{{ old('name') }}" placeholder="e.g., Priority Hostel A" required>
                                @error('name')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="form-group md:col-span-2">
                                <label for="address" class="form-label">Address <span class="required">*</span></label>
                                <textarea name="address" id="address" class="form-control" rows="2" 
                                          placeholder="Enter complete hostel address..." required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="form-group md:col-span-2">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="4" 
                                          placeholder="Describe the hostel, its features, location advantages...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Hostel Type -->
                            <div class="form-group">
                                <label for="hostel_type" class="form-label">Hostel Type</label>
                                <select name="hostel_type" id="hostel_type" class="form-control">
                                    <option value="">Select Type</option>
                                    <option value="boys" {{ old('hostel_type') == 'boys' ? 'selected' : '' }}>Boys Only</option>
                                    <option value="girls" {{ old('hostel_type') == 'girls' ? 'selected' : '' }}>Girls Only</option>
                                    <option value="mixed" {{ old('hostel_type') == 'mixed' ? 'selected' : '' }}>Mixed</option>
                                </select>
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label for="is_active" class="form-label">Status <span class="required">*</span></label>
                                <select name="is_active" id="is_active" class="form-control" required>
                                    <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-phone-alt"></i>
                            Contact Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Contact Phone -->
                            <div class="form-group">
                                <label for="contact_phone" class="form-label">Contact Phone <span class="required">*</span></label>
                                <input type="tel" name="contact_phone" id="contact_phone" class="form-control" 
                                       value="{{ old('contact_phone') }}" placeholder="e.g., 0241234567" required>
                                @error('contact_phone')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contact Email -->
                            <div class="form-group">
                                <label for="contact_email" class="form-label">Contact Email <span class="required">*</span></label>
                                <input type="email" name="contact_email" id="contact_email" class="form-control" 
                                       value="{{ old('contact_email') }}" placeholder="hostel@example.com" required>
                                @error('contact_email')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location & Distance Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-map-marker-alt"></i>
                            Location & Distance to Campus
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Walking Time -->
                            <div class="form-group">
                                <label for="walking_time_minutes" class="form-label">Walking Time (minutes)</label>
                                <input type="number" name="walking_time_minutes" id="walking_time_minutes" class="form-control" 
                                       value="{{ old('walking_time_minutes') }}" placeholder="e.g., 15" min="1">
                                <div class="form-hint">
                                    <i class="fas fa-walking"></i>
                                    <span>Time to walk to campus</span>
                                </div>
                            </div>

                            <!-- Driving Time -->
                            <div class="form-group">
                                <label for="driving_time_minutes" class="form-label">Driving Time (minutes)</label>
                                <input type="number" name="driving_time_minutes" id="driving_time_minutes" class="form-control" 
                                       value="{{ old('driving_time_minutes') }}" placeholder="e.g., 5" min="1">
                                <div class="form-hint">
                                    <i class="fas fa-car"></i>
                                    <span>Time to drive to campus</span>
                                </div>
                            </div>

                            <!-- Distance -->
                            <div class="form-group">
                                <label for="distance_km" class="form-label">Distance (km)</label>
                                <input type="number" name="distance_km" id="distance_km" class="form-control" 
                                       value="{{ old('distance_km') }}" placeholder="e.g., 2.5" step="0.1" min="0">
                                <div class="form-hint">
                                    <i class="fas fa-route"></i>
                                    <span>Distance to campus</span>
                                </div>
                            </div>

                            <!-- Google Maps URL -->
                            <div class="form-group md:col-span-3">
                                <label for="google_maps_url" class="form-label">Google Maps Link</label>
                                <input type="url" name="google_maps_url" id="google_maps_url" class="form-control" 
                                       value="{{ old('google_maps_url') }}" placeholder="https://maps.google.com/...">
                                <div class="form-hint">
                                    <i class="fas fa-map"></i>
                                    <span>Paste the Google Maps share link for the hostel location</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Amenities Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-concierge-bell"></i>
                            Hostel Amenities
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="amenities-grid">
                            @php
                                $commonAmenities = [
                                    'wifi' => ['icon' => 'fas fa-wifi', 'label' => 'WiFi'],
                                    'laundry' => ['icon' => 'fas fa-tshirt', 'label' => 'Laundry Service'],
                                    'cleaning' => ['icon' => 'fas fa-broom', 'label' => 'Cleaning Service'],
                                    'security' => ['icon' => 'fas fa-shield-alt', 'label' => '24/7 Security'],
                                    'cctv' => ['icon' => 'fas fa-video', 'label' => 'CCTV Surveillance'],
                                    'parking' => ['icon' => 'fas fa-parking', 'label' => 'Parking'],
                                    'gym' => ['icon' => 'fas fa-dumbbell', 'label' => 'Gym'],
                                    'common_room' => ['icon' => 'fas fa-couch', 'label' => 'Common Room'],
                                    'study_room' => ['icon' => 'fas fa-book-reader', 'label' => 'Study Room'],
                                    'kitchen' => ['icon' => 'fas fa-utensils', 'label' => 'Shared Kitchen'],
                                    'dining' => ['icon' => 'fas fa-utensils', 'label' => 'Dining Hall'],
                                    'water_supply' => ['icon' => 'fas fa-tint', 'label' => '24/7 Water Supply'],
                                    'electricity' => ['icon' => 'fas fa-bolt', 'label' => 'Backup Power'],
                                    'fan' => ['icon' => 'fas fa-fan', 'label' => 'Ceiling Fan'],
                                    'ac' => ['icon' => 'fas fa-snowflake', 'label' => 'Air Conditioning'],
                                    'medical' => ['icon' => 'fas fa-first-aid', 'label' => 'Medical Support'],
                                ];
                            @endphp
                            @foreach($commonAmenities as $value => $amenity)
                                <label class="amenity-checkbox {{ is_array(old('amenities')) && in_array($value, old('amenities')) ? 'checked' : '' }}">
                                    <input type="checkbox" name="amenities[]" value="{{ $value }}" 
                                           {{ is_array(old('amenities')) && in_array($value, old('amenities')) ? 'checked' : '' }}>
                                    <i class="{{ $amenity['icon'] }}"></i>
                                    <span>{{ $amenity['label'] }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Images -->
            <div class="space-y-6">
                <!-- Cover Image Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-image"></i>
                            Cover Image
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="image-upload-area" id="coverImageArea">
                            <input type="file" name="cover_image" id="cover_image" accept="image/*" class="hidden">
                            <label for="cover_image" class="image-upload-label">
                                <div class="upload-placeholder" id="coverPlaceholder">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Click to upload cover image</span>
                                    <small>JPG, PNG, WebP (max 5MB)</small>
                                </div>
                                <img id="coverPreview" class="image-preview hidden" alt="Cover preview">
                            </label>
                        </div>
                        @error('cover_image')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Gallery Images Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-images"></i>
                            Gallery Images
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="gallery-upload-area">
                            <input type="file" name="images[]" id="gallery_images" accept="image/*" multiple class="hidden">
                            <label for="gallery_images" class="gallery-upload-label">
                                <i class="fas fa-plus-circle"></i>
                                <span>Add Gallery Images</span>
                                <small>Select multiple images</small>
                            </label>
                        </div>
                        <div id="galleryPreview" class="gallery-preview"></div>
                        @error('images.*')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Quick Tips Card -->
                <div class="card bg-primary-light">
                    <div class="card-body">
                        <h4 class="font-semibold text-primary mb-2">
                            <i class="fas fa-lightbulb"></i>
                            Tips for Great Listings
                        </h4>
                        <ul class="text-sm text-gray-700 space-y-2">
                            <li><i class="fas fa-check text-primary mr-2"></i>Use high-quality images</li>
                            <li><i class="fas fa-check text-primary mr-2"></i>Include multiple room views</li>
                            <li><i class="fas fa-check text-primary mr-2"></i>Add accurate distance info</li>
                            <li><i class="fas fa-check text-primary mr-2"></i>List all available amenities</li>
                            <li><i class="fas fa-check text-primary mr-2"></i>Write detailed description</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="card mt-6">
            <div class="card-body">
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.hostels.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Create Hostel
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.required { color: var(--danger); }

.form-hint {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
    font-size: 0.75rem;
    color: var(--gray-500);
}

.form-hint i {
    color: var(--primary);
}

/* Amenities Grid */
.amenities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 0.75rem;
}

.amenity-checkbox {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    border: 2px solid var(--gray-200);
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
}

.amenity-checkbox:hover {
    border-color: var(--primary);
    background: var(--primary-light);
}

.amenity-checkbox.checked {
    border-color: var(--primary);
    background: var(--primary-light);
}

.amenity-checkbox input {
    display: none;
}

.amenity-checkbox i {
    font-size: 1.5rem;
    color: var(--gray-500);
    transition: color 0.3s;
}

.amenity-checkbox.checked i {
    color: var(--primary);
}

.amenity-checkbox span {
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--gray-700);
}

/* Image Upload */
.image-upload-area {
    position: relative;
}

.image-upload-label {
    display: block;
    cursor: pointer;
}

.upload-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 2rem;
    border: 2px dashed var(--gray-300);
    border-radius: var(--border-radius);
    background: var(--gray-50);
    transition: all 0.3s;
    min-height: 200px;
}

.upload-placeholder:hover {
    border-color: var(--primary);
    background: var(--primary-light);
}

.upload-placeholder i {
    font-size: 3rem;
    color: var(--gray-400);
}

.upload-placeholder span {
    font-weight: 600;
    color: var(--gray-600);
}

.upload-placeholder small {
    color: var(--gray-500);
    font-size: 0.75rem;
}

.image-preview {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: var(--border-radius);
}

/* Gallery Upload */
.gallery-upload-area {
    margin-bottom: 1rem;
}

.gallery-upload-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1.5rem;
    border: 2px dashed var(--gray-300);
    border-radius: var(--border-radius);
    background: var(--gray-50);
    cursor: pointer;
    transition: all 0.3s;
}

.gallery-upload-label:hover {
    border-color: var(--primary);
    background: var(--primary-light);
}

.gallery-upload-label i {
    font-size: 2rem;
    color: var(--primary);
}

.gallery-upload-label span {
    font-weight: 600;
    color: var(--gray-700);
}

.gallery-upload-label small {
    color: var(--gray-500);
    font-size: 0.75rem;
}

.gallery-preview {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.5rem;
}

.gallery-item {
    position: relative;
    aspect-ratio: 1;
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: var(--border-radius-sm);
}

.gallery-item .remove-btn {
    position: absolute;
    top: 4px;
    right: 4px;
    width: 20px;
    height: 20px;
    background: var(--danger);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.625rem;
}

.bg-primary-light {
    background: var(--primary-light) !important;
}

.lg\:col-span-2 {
    grid-column: span 2 / span 2;
}

@media (min-width: 1024px) {
    .grid-cols-1.lg\:grid-cols-3 {
        grid-template-columns: repeat(3, 1fr);
    }
}

.md\:col-span-2 {
    grid-column: span 2 / span 2;
}

.md\:col-span-3 {
    grid-column: span 3 / span 3;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cover image preview
    const coverInput = document.getElementById('cover_image');
    const coverPreview = document.getElementById('coverPreview');
    const coverPlaceholder = document.getElementById('coverPlaceholder');

    coverInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                coverPreview.src = e.target.result;
                coverPreview.classList.remove('hidden');
                coverPlaceholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Gallery images preview
    const galleryInput = document.getElementById('gallery_images');
    const galleryPreview = document.getElementById('galleryPreview');
    let selectedFiles = new DataTransfer();

    galleryInput.addEventListener('change', function(e) {
        const files = e.target.files;
        
        for (let i = 0; i < files.length; i++) {
            selectedFiles.items.add(files[i]);
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'gallery-item';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Gallery image">
                    <button type="button" class="remove-btn" data-index="${selectedFiles.items.length - 1}">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                galleryPreview.appendChild(div);
            };
            reader.readAsDataURL(files[i]);
        }
        
        // Update the input with all selected files
        galleryInput.files = selectedFiles.files;
    });

    // Remove gallery image
    galleryPreview.addEventListener('click', function(e) {
        if (e.target.closest('.remove-btn')) {
            const btn = e.target.closest('.remove-btn');
            const index = parseInt(btn.dataset.index);
            btn.closest('.gallery-item').remove();
            
            // Remove from DataTransfer
            const newFiles = new DataTransfer();
            for (let i = 0; i < selectedFiles.files.length; i++) {
                if (i !== index) {
                    newFiles.items.add(selectedFiles.files[i]);
                }
            }
            selectedFiles = newFiles;
            galleryInput.files = selectedFiles.files;
        }
    });

    // Amenity checkbox toggle
    document.querySelectorAll('.amenity-checkbox').forEach(checkbox => {
        checkbox.addEventListener('click', function(e) {
            if (e.target.tagName !== 'INPUT') {
                const input = this.querySelector('input');
                input.checked = !input.checked;
                this.classList.toggle('checked', input.checked);
            }
        });

        const input = checkbox.querySelector('input');
        input.addEventListener('change', function() {
            checkbox.classList.toggle('checked', this.checked);
        });
    });
});
</script>
@endsection
