<!-- resources/views/profile/partials/update-profile-information-form.blade.php -->
<div class="profile-form-section">
    <div class="form-header">
        <h3 class="form-title">
            <i class="fas fa-user-edit text-blue-600 mr-2"></i>
            Profile Information
        </h3>
        <p class="form-subtitle">Update your account's profile information and email address</p>
    </div>

    <!-- Email Verification Notice -->
    @if (Auth::user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! Auth::user()->hasVerifiedEmail())
    <div class="verification-alert">
        <div class="alert-content">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Email Not Verified</strong>
                <p>Your email address is unverified. Please check your email for a verification link.</p>
                <form method="POST" action="{{ route('verification.send') }}" class="inline-form">
                    @csrf
                    <button type="submit" class="verification-resend">
                        Click here to resend the verification email
                    </button>
                </form>
                @if (session('status') === 'verification-link-sent')
                    <p class="verification-sent">
                        A new verification link has been sent to your email address.
                    </p>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- FIXED: Changed route to profile.info.update --}}
    <form method="POST" action="{{ route('admin.profile.info.update') }}" class="modern-form" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Picture Upload -->
        <div class="form-group full-width">
            <label class="form-label">Profile Picture</label>
            <div class="file-upload-wrapper">
                <div class="upload-preview">
                    @if(Auth::user()->profile_picture)
                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" 
                             alt="Current profile picture" id="profile-preview">
                    @else
                        <div class="upload-placeholder">
                            <i class="fas fa-user"></i>
                            <span>No profile picture</span>
                        </div>
                    @endif
                </div>
                <div class="upload-controls">
                    <input type="file" name="profile_picture" id="profile_picture" 
                           accept="image/*" class="file-input">
                    <label for="profile_picture" class="btn btn-secondary">
                        <i class="fas fa-upload"></i>
                        Choose Image
                    </label>
                    <div class="file-hint">JPG, PNG or GIF (Max 2MB)</div>
                    @error('profile_picture')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Name Field -->
        <div class="form-group">
            <label for="name" class="form-label">Full Name *</label>
            <input type="text" id="name" name="name" class="form-control" 
                   value="{{ old('name', Auth::user()->name) }}" 
                   required autofocus autocomplete="name">
            @error('name')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Field -->
        <div class="form-group">
            <label for="email" class="form-label">Email Address *</label>
            <input type="email" id="email" name="email" class="form-control" 
                   value="{{ old('email', Auth::user()->email) }}" 
                   required autocomplete="email">
            @error('email')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <!-- Phone Field -->
        <div class="form-group">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="tel" id="phone" name="phone" class="form-control" 
                   value="{{ old('phone', Auth::user()->phone) }}" 
                   autocomplete="tel">
            @error('phone')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <!-- Address Field -->
        <div class="form-group full-width">
            <label for="address" class="form-label">Address</label>
            <textarea id="address" name="address" class="form-control" 
                      rows="3" autocomplete="street-address">{{ old('address', Auth::user()->address) }}</textarea>
            @error('address')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <!-- Bio Field -->
        <div class="form-group full-width">
            <label for="bio" class="form-label">Bio</label>
            <textarea id="bio" name="bio" class="form-control" 
                      rows="4" placeholder="Tell us a little about yourself...">{{ old('bio', Auth::user()->bio) }}</textarea>
            @error('bio')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Save Changes
            </button>
            
            @if (session('status') === 'profile-updated')
                <div class="form-success">
                    <i class="fas fa-check-circle"></i>
                    Saved successfully!
                </div>
            @endif
        </div>
    </form>
</div>

{{-- CSS remains the same --}}
<style>
.profile-form-section {
    space-y-6;
}

.form-header {
    margin-bottom: 2rem;
}

.form-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.form-subtitle {
    color: var(--gray-600);
    font-size: 0.875rem;
}

.verification-alert {
    background: rgba(248, 150, 30, 0.1);
    border: 1px solid rgba(248, 150, 30, 0.2);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.alert-content {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.alert-content i {
    color: var(--warning);
    font-size: 1.5rem;
    margin-top: 0.125rem;
}

.verification-resend {
    background: none;
    border: none;
    color: var(--primary);
    text-decoration: underline;
    cursor: pointer;
    font-size: 0.875rem;
    padding: 0;
    margin-top: 0.5rem;
    display: inline-block;
}

.verification-sent {
    color: var(--success);
    font-size: 0.875rem;
    margin-top: 0.5rem;
    font-weight: 500;
}

.modern-form {
    space-y-6;
}

.form-success {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--success);
    font-weight: 500;
    font-size: 0.875rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profile picture preview
    const profilePictureInput = document.getElementById('profile_picture');
    if (profilePictureInput) {
        profilePictureInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = document.getElementById('profile-preview');
                    const placeholder = document.querySelector('.upload-placeholder');
                    
                    if (!preview && placeholder) {
                        preview = document.createElement('img');
                        preview.id = 'profile-preview';
                        preview.alt = 'Profile preview';
                        preview.style.width = '100%';
                        preview.style.height = '100%';
                        preview.style.objectFit = 'cover';
                        preview.style.borderRadius = '50%';
                        placeholder.parentNode.replaceChild(preview, placeholder);
                    }
                    
                    if (preview) {
                        preview.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>