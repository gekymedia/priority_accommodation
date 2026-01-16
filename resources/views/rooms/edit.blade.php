@extends('layouts.app')

@section('title', 'Edit Room - Priority Accommodations')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Edit Room</h1>
    <div class="page-actions">
        <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Rooms
        </a>
    </div>
</div>

<!-- Edit Form -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Room Information</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.rooms.update', $room) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Hostel Selection -->
                <div class="form-group">
                    <label for="hostel_id" class="form-label">Hostel *</label>
                    <select name="hostel_id" id="hostel_id" class="form-control" required>
                        <option value="">Select Hostel</option>
                        @foreach($hostels as $hostel)
                            <option value="{{ $hostel->id }}" 
                                {{ $room->hostel_id == $hostel->id ? 'selected' : '' }}>
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
                    <label for="room_number" class="form-label">Room Number *</label>
                    <input type="text" name="room_number" id="room_number" 
                           value="{{ old('room_number', $room->room_number) }}" 
                           class="form-control" required maxlength="10">
                    @error('room_number')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Room Type -->
                <div class="form-group">
                    <label for="type" class="form-label">Room Type *</label>
                    <select name="type" id="type" class="form-control" required>
                        @foreach($roomTypes as $value => $label)
                            <option value="{{ $value }}" 
                                {{ $room->type == $value ? 'selected' : '' }}>
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
                    <label for="capacity" class="form-label">Capacity *</label>
                    <input type="number" name="capacity" id="capacity" 
                           value="{{ old('capacity', $room->capacity) }}" 
                           class="form-control" min="1" max="6" required>
                    @error('capacity')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Price per Academic Year -->
                <div class="form-group">
                    <label for="price_per_academic_year" class="form-label">Price per Academic Year (₵) *</label>
                    <input type="number" name="price_per_academic_year" id="price_per_academic_year" 
                           value="{{ old('price_per_academic_year', $room->price_per_academic_year) }}" 
                           class="form-control" step="0.01" min="0" required>
                    @error('price_per_academic_year')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label for="status" class="form-label">Status *</label>
                    <select name="status" id="status" class="form-control" required>
                        @foreach($roomStatuses as $value => $label)
                            <option value="{{ $value }}" 
                                {{ $room->status == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="form-group mt-4">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" 
                          rows="3" placeholder="Optional room description...">{{ old('description', $room->description) }}</textarea>
                @error('description')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Video URL -->
            <div class="form-group">
                <label for="video_url" class="form-label">Video URL</label>
                <input type="url" name="video_url" id="video_url" 
                       value="{{ old('video_url', $room->video_url) }}" 
                       class="form-control" placeholder="https://...">
                @error('video_url')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Room Photos -->
            <div class="form-group photos-section">
                <label class="form-label">
                    <i class="fas fa-images text-indigo-600 mr-2"></i>
                    Room Photos
                </label>
                
                <!-- Existing Photos -->
                @if($room->photos && count($room->photos) > 0)
                <div class="existing-photos-grid mb-4">
                    @foreach($room->photos as $index => $photo)
                        <div class="existing-photo-item" data-photo="{{ $photo }}">
                            <img src="{{ asset('storage/' . $photo) }}" alt="Room Photo {{ $index + 1 }}">
                            <input type="hidden" name="existing_photos[]" value="{{ $photo }}">
                            <button type="button" class="remove-photo-btn" onclick="removeExistingPhoto(this)">
                                <i class="fas fa-times"></i>
                            </button>
                            <span class="photo-number">{{ $index + 1 }}</span>
                        </div>
                    @endforeach
                </div>
                @else
                <div class="no-photos-message mb-4">
                    <i class="fas fa-camera text-gray-400"></i>
                    <span class="text-gray-500">No photos uploaded yet</span>
                </div>
                @endif

                <input type="file" name="photos[]" id="photos" 
                       class="form-control-file" multiple accept="image/*">
                <p class="text-xs text-gray-500 mt-1">Upload multiple room photos (Max 2MB each). Select multiple files at once.</p>
                <div id="newPhotoPreview" class="mt-3 existing-photos-grid"></div>
                @error('photos.*')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Available Checkbox -->
            <div class="form-group">
                <label class="flex items-center">
                    <input type="checkbox" name="available" value="1" 
                           {{ $room->available ? 'checked' : '' }} class="mr-2">
                    <span class="form-label mb-0">Room is available for booking</span>
                </label>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('admin.rooms.show', $room) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Room
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Current Room Info -->
<div class="card mt-6">
    <div class="card-header">
        <h3 class="card-title">Current Room Information</h3>
    </div>
    <div class="card-body">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <strong>Room Number:</strong> {{ $room->room_number }}
            </div>
            <div>
                <strong>Type:</strong> {{ $roomTypes[$room->type] ?? $room->type }}
            </div>
            <div>
                <strong>Capacity:</strong> {{ $room->capacity }} person(s)
            </div>
            <div>
                <strong>Price:</strong> ₵{{ number_format($room->price_per_academic_year, 2) }} per academic year
            </div>
            <div>
                <strong>Status:</strong> 
                <span class="status {{ $room->status }}">
                    {{ ucfirst($room->status) }}
                </span>
            </div>
            <div>
                <strong>Available:</strong> 
                <span class="{{ $room->available ? 'text-green-600' : 'text-red-600' }}">
                    {{ $room->available ? 'Yes' : 'No' }}
                </span>
            </div>
            @if($room->hostel)
            <div>
                <strong>Hostel:</strong> {{ $room->hostel->name }}
            </div>
            @endif
            @if($room->description)
            <div class="md:col-span-2">
                <strong>Description:</strong> {{ $room->description }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.status {
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status.available {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.status.occupied {
    background: rgba(247, 37, 133, 0.1);
    color: #f72585;
}

.status.maintenance {
    background: rgba(248, 150, 30, 0.1);
    color: #f8961e;
}

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
    border: 2px solid var(--border-color);
    border-radius: var(--border-radius);
    background: white;
    transition: var(--transition);
    font-size: 0.875rem;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.form-error {
    color: var(--danger);
    font-size: 0.75rem;
    margin-top: 0.5rem;
    font-weight: 500;
}

/* Photo upload styles */
.photos-section {
    background: var(--gray-50);
    padding: 1.5rem;
    border-radius: var(--radius);
    margin-top: 1.5rem;
}

.form-control-file {
    display: block;
    width: 100%;
    padding: 0.75rem;
    border: 2px dashed var(--gray-300);
    border-radius: var(--radius);
    background: white;
    cursor: pointer;
    transition: all 0.2s ease;
}

.form-control-file:hover {
    border-color: var(--primary);
    background: rgba(67, 97, 238, 0.05);
}

.existing-photos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 1rem;
}

.existing-photo-item {
    position: relative;
    aspect-ratio: 1;
    border-radius: var(--radius);
    overflow: hidden;
    border: 2px solid var(--gray-200);
    background: var(--gray-100);
}

.existing-photo-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.existing-photo-item .remove-photo-btn {
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
    opacity: 0;
}

.existing-photo-item:hover .remove-photo-btn {
    opacity: 1;
}

.existing-photo-item .remove-photo-btn:hover {
    background: var(--danger);
    transform: scale(1.1);
}

.existing-photo-item .photo-number {
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

.no-photos-message {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: white;
    border-radius: var(--radius);
    border: 1px dashed var(--gray-300);
}

.no-photos-message i {
    font-size: 1.5rem;
}

.new-photo-preview {
    position: relative;
    aspect-ratio: 1;
    border-radius: var(--radius);
    overflow: hidden;
    border: 2px solid var(--success);
    background: var(--gray-100);
}

.new-photo-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.new-photo-preview .new-badge {
    position: absolute;
    top: 0.25rem;
    left: 0.25rem;
    padding: 0.125rem 0.375rem;
    background: var(--success);
    color: white;
    font-size: 0.55rem;
    font-weight: 700;
    border-radius: 0.25rem;
    text-transform: uppercase;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Photo preview
    const photosInput = document.getElementById('photos');
    const newPhotoPreview = document.getElementById('newPhotoPreview');
    
    if (photosInput && newPhotoPreview) {
        photosInput.addEventListener('change', function() {
            newPhotoPreview.innerHTML = '';
            if (this.files) {
                Array.from(this.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'new-photo-preview';
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="New Photo ${index + 1}">
                            <span class="new-badge">New</span>
                        `;
                        newPhotoPreview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    }
});

// Remove existing photo
function removeExistingPhoto(button) {
    const photoItem = button.closest('.existing-photo-item');
    if (confirm('Remove this photo?')) {
        photoItem.remove();
        
        // Update remaining photo numbers
        document.querySelectorAll('.existing-photo-item .photo-number').forEach((num, index) => {
            num.textContent = index + 1;
        });
    }
}
</script>
@endsection