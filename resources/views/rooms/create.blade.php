@extends('layouts.app')

@section('title', 'Add New Room - Priority Accommodations')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Add New <span class="page-title-highlight">Room</span></h1>
            <p class="text-gray-500 text-sm mt-1">Create a new room with photos and details</p>
        </div>
        <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Rooms
        </a>
    </div>

    <form method="POST" action="{{ route('admin.rooms.store') }}" enctype="multipart/form-data" id="roomForm">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Basic Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-door-closed"></i>
                            Room Details
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Hostel Selection -->
                            <div class="form-group">
                                <label for="hostel_id" class="form-label">Hostel <span class="required">*</span></label>
                                <select name="hostel_id" id="hostel_id" class="form-control" required>
                                    <option value="">Select Hostel</option>
                                    @foreach($hostels as $hostel)
                                        <option value="{{ $hostel->id }}" {{ old('hostel_id') == $hostel->id ? 'selected' : '' }}>
                                            {{ $hostel->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hostel_id')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Room Number -->
                            <div class="form-group">
                                <label for="room_number" class="form-label">Room Number <span class="required">*</span></label>
                                <input type="text" name="room_number" id="room_number" class="form-control" 
                                       value="{{ old('room_number') }}" placeholder="e.g., 101, A-12" required>
                                @error('room_number')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Room Type -->
                            <div class="form-group">
                                <label for="type" class="form-label">Room Type <span class="required">*</span></label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="">Select Room Type</option>
                                    @foreach($roomTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Capacity -->
                            <div class="form-group">
                                <label for="capacity" class="form-label">Capacity (Beds) <span class="required">*</span></label>
                                <select name="capacity" id="capacity" class="form-control" required>
                                    <option value="">Select Capacity</option>
                                    @for($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}" {{ old('capacity') == $i ? 'selected' : '' }}>
                                            {{ $i }} {{ $i == 1 ? 'person' : 'people' }}
                                        </option>
                                    @endfor
                                </select>
                                <div class="form-hint">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Number of beds/occupants the room can hold</span>
                                </div>
                                @error('capacity')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Price per Semester -->
                            <div class="form-group">
                                <label for="price_per_semester" class="form-label">Price per Semester (₵) <span class="required">*</span></label>
                                <input type="number" name="price_per_semester" id="price_per_semester" class="form-control" 
                                       value="{{ old('price_per_semester') }}" placeholder="e.g., 2500" min="0" step="0.01" required>
                                @error('price_per_semester')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label for="status" class="form-label">Status <span class="required">*</span></label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="">Select Status</option>
                                    @foreach($roomStatuses as $value => $label)
                                        <option value="{{ $value }}" {{ old('status', 'available') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="form-group md:col-span-2">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="3" 
                                          placeholder="Describe the room, its view, special features...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Video URL -->
                            <div class="form-group md:col-span-2">
                                <label for="video_url" class="form-label">Video Tour URL</label>
                                <input type="url" name="video_url" id="video_url" class="form-control" 
                                       value="{{ old('video_url') }}" placeholder="https://youtube.com/watch?v=...">
                                <div class="form-hint">
                                    <i class="fas fa-video"></i>
                                    <span>YouTube or direct video link for virtual tour</span>
                                </div>
                                @error('video_url')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Availability -->
                            <div class="form-group md:col-span-2">
                                <label class="form-label">Listing Status</label>
                                <div class="flex gap-4">
                                    <label class="radio-option {{ old('available', 1) == 1 ? 'selected' : '' }}">
                                        <input type="radio" name="available" value="1" {{ old('available', 1) == 1 ? 'checked' : '' }}>
                                        <i class="fas fa-check-circle"></i>
                                        <span>Available for Booking</span>
                                    </label>
                                    <label class="radio-option {{ old('available') === '0' ? 'selected' : '' }}">
                                        <input type="radio" name="available" value="0" {{ old('available') === '0' ? 'checked' : '' }}>
                                        <i class="fas fa-pause-circle"></i>
                                        <span>Hidden from Listings</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-star"></i>
                            Room Features
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="features-grid">
                            @php
                                $commonFeatures = [
                                    'wifi' => ['icon' => 'fas fa-wifi', 'label' => 'WiFi'],
                                    'ac' => ['icon' => 'fas fa-snowflake', 'label' => 'Air Conditioning'],
                                    'fan' => ['icon' => 'fas fa-fan', 'label' => 'Ceiling Fan'],
                                    'tv' => ['icon' => 'fas fa-tv', 'label' => 'TV'],
                                    'fridge' => ['icon' => 'fas fa-ice-cream', 'label' => 'Refrigerator'],
                                    'wardrobe' => ['icon' => 'fas fa-door-closed', 'label' => 'Wardrobe'],
                                    'desk' => ['icon' => 'fas fa-desktop', 'label' => 'Study Desk'],
                                    'chair' => ['icon' => 'fas fa-chair', 'label' => 'Chair'],
                                    'attached_bathroom' => ['icon' => 'fas fa-bath', 'label' => 'Attached Bathroom'],
                                    'shared_bathroom' => ['icon' => 'fas fa-restroom', 'label' => 'Shared Bathroom'],
                                    'hot_water' => ['icon' => 'fas fa-hot-tub', 'label' => 'Hot Water'],
                                    'balcony' => ['icon' => 'fas fa-building', 'label' => 'Balcony'],
                                    'window' => ['icon' => 'fas fa-window-maximize', 'label' => 'Window View'],
                                    'bed_mattress' => ['icon' => 'fas fa-bed', 'label' => 'Bed & Mattress'],
                                    'reading_lamp' => ['icon' => 'fas fa-lightbulb', 'label' => 'Reading Lamp'],
                                    'power_outlets' => ['icon' => 'fas fa-plug', 'label' => 'Multiple Power Outlets'],
                                ];
                            @endphp
                            @foreach($commonFeatures as $value => $feature)
                                <label class="feature-checkbox {{ is_array(old('features')) && in_array($value, old('features')) ? 'checked' : '' }}">
                                    <input type="checkbox" name="features[]" value="{{ $value }}" 
                                           {{ is_array(old('features')) && in_array($value, old('features')) ? 'checked' : '' }}>
                                    <i class="{{ $feature['icon'] }}"></i>
                                    <span>{{ $feature['label'] }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Photos -->
            <div class="space-y-6">
                <!-- Room Photos Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-camera"></i>
                            Room Photos
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="photo-upload-area">
                            <input type="file" name="photos[]" id="room_photos" accept="image/*" multiple class="hidden">
                            <label for="room_photos" class="photo-upload-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Click to upload photos</span>
                                <small>JPG, PNG, WebP (max 5MB each)</small>
                            </label>
                        </div>
                        <div id="photosPreview" class="photos-preview"></div>
                        @error('photos.*')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        
                        <div class="photo-tips">
                            <h5><i class="fas fa-lightbulb"></i> Photo Tips</h5>
                            <ul>
                                <li>Include photos from different angles</li>
                                <li>Show the bathroom if attached</li>
                                <li>Capture natural lighting</li>
                                <li>Show the view from window/balcony</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Price Summary Card -->
                <div class="card bg-primary-light">
                    <div class="card-body">
                        <h4 class="font-semibold text-primary mb-3">
                            <i class="fas fa-calculator"></i>
                            Price Summary
                        </h4>
                        <div class="price-summary">
                            <div class="price-row">
                                <span>Price per Semester:</span>
                                <span id="displayPrice">₵0.00</span>
                            </div>
                            <div class="price-row" id="pricePerBedRow" style="display: none;">
                                <span>Price per Bed:</span>
                                <span id="displayPricePerBed">₵0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="card mt-6">
            <div class="card-body">
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Create Room
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

/* Radio Options */
.radio-option {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1.25rem;
    border: 2px solid var(--gray-200);
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: all 0.3s;
}

.radio-option:hover {
    border-color: var(--primary);
}

.radio-option.selected {
    border-color: var(--primary);
    background: var(--primary-light);
}

.radio-option input {
    display: none;
}

.radio-option i {
    font-size: 1.25rem;
    color: var(--gray-400);
}

.radio-option.selected i {
    color: var(--primary);
}

.radio-option span {
    font-weight: 500;
    color: var(--gray-700);
}

/* Features Grid */
.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: 0.75rem;
}

.feature-checkbox {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 0.75rem;
    border: 2px solid var(--gray-200);
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
}

.feature-checkbox:hover {
    border-color: var(--primary);
    background: var(--primary-light);
}

.feature-checkbox.checked {
    border-color: var(--primary);
    background: var(--primary-light);
}

.feature-checkbox input {
    display: none;
}

.feature-checkbox i {
    font-size: 1.5rem;
    color: var(--gray-500);
    transition: color 0.3s;
}

.feature-checkbox.checked i {
    color: var(--primary);
}

.feature-checkbox span {
    font-size: 0.7rem;
    font-weight: 500;
    color: var(--gray-700);
}

/* Photo Upload */
.photo-upload-area {
    margin-bottom: 1rem;
}

.photo-upload-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    padding: 2rem;
    border: 2px dashed var(--gray-300);
    border-radius: var(--border-radius);
    background: var(--gray-50);
    cursor: pointer;
    transition: all 0.3s;
}

.photo-upload-label:hover {
    border-color: var(--primary);
    background: var(--primary-light);
}

.photo-upload-label i {
    font-size: 2.5rem;
    color: var(--primary);
}

.photo-upload-label span {
    font-weight: 600;
    color: var(--gray-700);
}

.photo-upload-label small {
    color: var(--gray-500);
    font-size: 0.75rem;
}

.photos-preview {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5rem;
}

.photo-item {
    position: relative;
    aspect-ratio: 4/3;
}

.photo-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: var(--border-radius-sm);
}

.photo-item .remove-btn {
    position: absolute;
    top: 4px;
    right: 4px;
    width: 24px;
    height: 24px;
    background: var(--danger);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
}

.photo-tips {
    margin-top: 1.5rem;
    padding: 1rem;
    background: var(--gray-50);
    border-radius: var(--border-radius);
}

.photo-tips h5 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 0.5rem;
}

.photo-tips ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.photo-tips li {
    font-size: 0.75rem;
    color: var(--gray-600);
    padding: 0.25rem 0;
    padding-left: 1rem;
    position: relative;
}

.photo-tips li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: var(--primary);
}

/* Price Summary */
.price-summary {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.price-row {
    display: flex;
    justify-content: space-between;
    font-size: 0.9rem;
}

.price-row span:last-child {
    font-weight: 700;
    color: var(--primary);
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Photo upload preview
    const photosInput = document.getElementById('room_photos');
    const photosPreview = document.getElementById('photosPreview');
    let selectedFiles = new DataTransfer();

    photosInput.addEventListener('change', function(e) {
        const files = e.target.files;
        
        for (let i = 0; i < files.length; i++) {
            selectedFiles.items.add(files[i]);
            
            const reader = new FileReader();
            const index = selectedFiles.items.length - 1;
            
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'photo-item';
                div.dataset.index = index;
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Room photo">
                    <button type="button" class="remove-btn" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                photosPreview.appendChild(div);
            };
            reader.readAsDataURL(files[i]);
        }
        
        photosInput.files = selectedFiles.files;
    });

    // Remove photo
    photosPreview.addEventListener('click', function(e) {
        if (e.target.closest('.remove-btn')) {
            const btn = e.target.closest('.remove-btn');
            const photoItem = btn.closest('.photo-item');
            photoItem.remove();
            
            // Rebuild files list
            const newFiles = new DataTransfer();
            const remainingItems = photosPreview.querySelectorAll('.photo-item');
            remainingItems.forEach((item, idx) => {
                const oldIndex = parseInt(item.dataset.index);
                if (selectedFiles.files[oldIndex]) {
                    newFiles.items.add(selectedFiles.files[oldIndex]);
                }
                item.dataset.index = idx;
                item.querySelector('.remove-btn').dataset.index = idx;
            });
            selectedFiles = newFiles;
            photosInput.files = selectedFiles.files;
        }
    });

    // Feature checkbox toggle
    document.querySelectorAll('.feature-checkbox').forEach(checkbox => {
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

    // Radio option toggle
    document.querySelectorAll('.radio-option').forEach(option => {
        option.addEventListener('click', function() {
            const input = this.querySelector('input');
            const name = input.name;
            
            document.querySelectorAll(`.radio-option input[name="${name}"]`).forEach(i => {
                i.closest('.radio-option').classList.remove('selected');
            });
            
            input.checked = true;
            this.classList.add('selected');
        });
    });

    // Price calculation
    const priceInput = document.getElementById('price_per_semester');
    const capacitySelect = document.getElementById('capacity');
    const displayPrice = document.getElementById('displayPrice');
    const displayPricePerBed = document.getElementById('displayPricePerBed');
    const pricePerBedRow = document.getElementById('pricePerBedRow');

    function updatePriceDisplay() {
        const price = parseFloat(priceInput.value) || 0;
        const capacity = parseInt(capacitySelect.value) || 1;
        
        displayPrice.textContent = '₵' + price.toFixed(2);
        
        if (capacity > 1) {
            pricePerBedRow.style.display = 'flex';
            displayPricePerBed.textContent = '₵' + (price / capacity).toFixed(2);
        } else {
            pricePerBedRow.style.display = 'none';
        }
    }

    priceInput.addEventListener('input', updatePriceDisplay);
    capacitySelect.addEventListener('change', updatePriceDisplay);
});
</script>
@endsection
