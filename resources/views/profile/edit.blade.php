@extends('layouts.app')

@section('title', 'Profile Settings - Priority Accommodations')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Profile Settings</h1>
    <div class="page-actions">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Dashboard
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success fade-in">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div class="profile-layout">
    <!-- Profile Sidebar -->
    <div class="profile-sidebar">
        <div class="card">
            <div class="card-body">
                <div class="profile-summary text-center">
                    <div class="profile-avatar mb-4">
                        @if(Auth::user()->profile_picture)
                            <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" 
                                 alt="Profile Picture" class="profile-image">
                            <div class="avatar-overlay">
                                <i class="fas fa-camera"></i>
                            </div>
                        @else
                            <div class="avatar-placeholder">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <h3 class="profile-name">{{ Auth::user()->name }}</h3>
                    <p class="profile-email">{{ Auth::user()->email }}</p>
                    
                    <!-- Verification Status -->
                    @if(Auth::user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! Auth::user()->hasVerifiedEmail())
                    <div class="verification-alert">
                        <i class="fas fa-exclamation-circle"></i>
                        Email not verified
                    </div>
                    @else
                    <div class="verification-success">
                        <i class="fas fa-check-circle"></i>
                        Email verified
                    </div>
                    @endif
                </div>

                <!-- Quick Stats -->
                <div class="profile-stats">
                    <div class="stat-grid">
                        <div class="stat-item">
                            <div class="stat-icon blue">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">{{ \App\Models\Booking::count() }}</div>
                                <div class="stat-label">Bookings</div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon green">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">{{ \App\Models\Student::count() }}</div>
                                <div class="stat-label">Students</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="profile-nav">
                    <a href="#personal" class="profile-nav-item active" data-section="personal">
                        <i class="fas fa-user-edit"></i>
                        Personal Information
                    </a>
                    <a href="#preferences" class="profile-nav-item" data-section="preferences">
                        <i class="fas fa-cog"></i>
                        Account Preferences
                    </a>
                    <a href="#security" class="profile-nav-item" data-section="security">
                        <i class="fas fa-lock"></i>
                        Security & Password
                    </a>
                    <a href="#notifications" class="profile-nav-item" data-section="notifications">
                        <i class="fas fa-bell"></i>
                        Notifications
                    </a>
                    <a href="#danger" class="profile-nav-item" data-section="danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        Danger Zone
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="profile-content">
        <!-- Personal Information Section -->
        <div class="profile-section active" id="personal-section">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-edit text-blue-600 mr-2"></i>
                        Personal Information
                    </h3>
                    <p class="card-subtitle">Update your personal details and contact information</p>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.profile.info.update') }}" class="profile-form" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="form-grid">
                            <!-- Profile Picture Upload -->
                            <div class="form-group full-width">
                                <label class="form-label">Profile Picture</label>
                                <div class="file-upload-area">
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
                                        <div class="file-info">JPG, PNG or GIF (Max 2MB)</div>
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
                                
                                <!-- Email Verification Status -->
                                @if (Auth::user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! Auth::user()->hasVerifiedEmail())
                                    <div class="verification-notice">
                                        <i class="fas fa-exclamation-circle"></i>
                                        Your email address is unverified.
                                        <form method="post" action="{{ route('verification.send') }}" class="inline-form">
                                            @csrf
                                            <button type="submit" class="verification-link">
                                                Click here to re-send the verification email.
                                            </button>
                                        </form>
                                        @if (session('status') === 'verification-link-sent')
                                            <div class="verification-success">
                                                A new verification link has been sent to your email address.
                                            </div>
                                        @endif
                                    </div>
                                @endif
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
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Save Personal Information
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Account Preferences Section -->
        <div class="profile-section" id="preferences-section">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cog text-green-600 mr-2"></i>
                        Account Preferences
                    </h3>
                    <p class="card-subtitle">Customize your account settings and preferences</p>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.profile.preferences.update') }}" class="preferences-form">
                        @csrf
                        @method('PATCH')

                        <div class="form-grid">
                            <!-- Timezone Field -->
                            <div class="form-group">
                                <label for="timezone" class="form-label">Time Zone *</label>
                                <select name="timezone" id="timezone" class="form-control" required>
                                    <option value="">Select Time Zone</option>
                                    @foreach([
                                        'UTC' => 'UTC',
                                        'America/New_York' => 'Eastern Time (ET)',
                                        'America/Chicago' => 'Central Time (CT)',
                                        'America/Denver' => 'Mountain Time (MT)',
                                        'America/Los_Angeles' => 'Pacific Time (PT)',
                                        'Europe/London' => 'London',
                                        'Europe/Paris' => 'Paris',
                                        'Asia/Kolkata' => 'India (IST)',
                                        'Asia/Tokyo' => 'Tokyo',
                                        'Australia/Sydney' => 'Sydney'
                                    ] as $value => $label)
                                        <option value="{{ $value }}" {{ old('timezone', Auth::user()->timezone) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('timezone')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Language Field -->
                            <div class="form-group">
                                <label for="language" class="form-label">Language *</label>
                                <select name="language" id="language" class="form-control" required>
                                    <option value="">Select Language</option>
                                    <option value="en" {{ old('language', Auth::user()->language) == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="es" {{ old('language', Auth::user()->language) == 'es' ? 'selected' : '' }}>Spanish</option>
                                    <option value="fr" {{ old('language', Auth::user()->language) == 'fr' ? 'selected' : '' }}>French</option>
                                    <option value="de" {{ old('language', Auth::user()->language) == 'de' ? 'selected' : '' }}>German</option>
                                    <option value="hi" {{ old('language', Auth::user()->language) == 'hi' ? 'selected' : '' }}>Hindi</option>
                                </select>
                                @error('language')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Notifications Field -->
                            <div class="form-group full-width">
                                <div class="checkbox-group">
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="notifications" class="checkbox-input" 
                                               value="1" {{ old('notifications', Auth::user()->notifications) ? 'checked' : '' }}>
                                        <span class="checkbox-custom"></span>
                                        Enable email notifications
                                    </label>
                                    <div class="checkbox-description">
                                        Receive notifications about bookings, updates, and system alerts.
                                    </div>
                                    @error('notifications')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Security & Password Section -->
        <div class="profile-section" id="security-section">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lock text-purple-600 mr-2"></i>
                        Security & Password
                    </h3>
                    <p class="card-subtitle">Manage your password and security settings</p>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.profile.password.update') }}" class="password-form">
                        @csrf
                        @method('PATCH')

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="current_password" class="form-label">Current Password *</label>
                                <input type="password" id="current_password" name="current_password" 
                                       class="form-control" autocomplete="current-password" required>
                                @error('current_password')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label">New Password *</label>
                                <input type="password" id="password" name="password" 
                                       class="form-control" autocomplete="new-password" required>
                                @error('password')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">Confirm New Password *</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" 
                                       class="form-control" autocomplete="new-password" required>
                            </div>
                        </div>

                        <!-- Password Strength Meter -->
                        <div class="password-strength">
                            <div class="strength-labels">
                                <span>Password Strength:</span>
                                <span class="strength-text" id="strength-text">None</span>
                            </div>
                            <div class="strength-bars">
                                <div class="strength-bar" id="strength-bar"></div>
                            </div>
                        </div>

                        <!-- Password Requirements -->
                        <div class="password-requirements">
                            <h4 class="requirements-title">Password Requirements:</h4>
                            <ul class="requirements-list">
                                <li class="requirement-item" data-requirement="length">
                                    <i class="fas fa-circle requirement-icon"></i>
                                    <span>At least 8 characters</span>
                                </li>
                                <li class="requirement-item" data-requirement="lowercase">
                                    <i class="fas fa-circle requirement-icon"></i>
                                    <span>One lowercase letter</span>
                                </li>
                                <li class="requirement-item" data-requirement="uppercase">
                                    <i class="fas fa-circle requirement-icon"></i>
                                    <span>One uppercase letter</span>
                                </li>
                                <li class="requirement-item" data-requirement="number">
                                    <i class="fas fa-circle requirement-icon"></i>
                                    <span>One number</span>
                                </li>
                                <li class="requirement-item" data-requirement="special">
                                    <i class="fas fa-circle requirement-icon"></i>
                                    <span>One special character</span>
                                </li>
                            </ul>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key"></i>
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Notifications Section -->
        <div class="profile-section" id="notifications-section">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bell text-orange-600 mr-2"></i>
                        Notification Preferences
                    </h3>
                    <p class="card-subtitle">Choose how you want to receive notifications</p>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.profile.notifications.update') }}" class="notifications-form">
                        @csrf
                        @method('PATCH')

                        <div class="form-grid">
                            <!-- Email Notifications -->
                            <div class="form-group full-width">
                                <label class="form-label">Email Notifications</label>
                                <div class="notification-settings">
                                    <label class="notification-item">
                                        <input type="checkbox" name="email_notifications[]" value="new_booking" 
                                               {{ in_array('new_booking', old('email_notifications', Auth::user()->email_notifications ?? [])) ? 'checked' : '' }}>
                                        <div class="notification-content">
                                            <div class="notification-title">New Booking Requests</div>
                                            <div class="notification-description">Get notified when a new booking is made</div>
                                        </div>
                                    </label>

                                    <label class="notification-item">
                                        <input type="checkbox" name="email_notifications[]" value="payment_received" 
                                               {{ in_array('payment_received', old('email_notifications', Auth::user()->email_notifications ?? [])) ? 'checked' : '' }}>
                                        <div class="notification-content">
                                            <div class="notification-title">Payment Received</div>
                                            <div class="notification-description">Notify when a payment is successfully processed</div>
                                        </div>
                                    </label>

                                    <label class="notification-item">
                                        <input type="checkbox" name="email_notifications[]" value="checkin_reminder" 
                                               {{ in_array('checkin_reminder', old('email_notifications', Auth::user()->email_notifications ?? [])) ? 'checked' : '' }}>
                                        <div class="notification-content">
                                            <div class="notification-title">Check-in Reminders</div>
                                            <div class="notification-description">Send reminders before scheduled check-ins</div>
                                        </div>
                                    </label>

                                    <label class="notification-item">
                                        <input type="checkbox" name="email_notifications[]" value="maintenance" 
                                               {{ in_array('maintenance', old('email_notifications', Auth::user()->email_notifications ?? [])) ? 'checked' : '' }}>
                                        <div class="notification-content">
                                            <div class="notification-title">Maintenance Alerts</div>
                                            <div class="notification-description">Receive maintenance request notifications</div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- SMS Notifications -->
                            <div class="form-group full-width">
                                <label class="form-label">SMS Notifications</label>
                                <div class="notification-settings">
                                    <label class="notification-item">
                                        <input type="checkbox" name="sms_notifications[]" value="booking_confirmation" 
                                               {{ in_array('booking_confirmation', old('sms_notifications', Auth::user()->sms_notifications ?? [])) ? 'checked' : '' }}>
                                        <div class="notification-content">
                                            <div class="notification-title">Booking Confirmation</div>
                                            <div class="notification-description">Send SMS when booking is confirmed</div>
                                        </div>
                                    </label>

                                    <label class="notification-item">
                                        <input type="checkbox" name="sms_notifications[]" value="payment_reminder" 
                                               {{ in_array('payment_reminder', old('sms_notifications', Auth::user()->sms_notifications ?? [])) ? 'checked' : '' }}>
                                        <div class="notification-content">
                                            <div class="notification-title">Payment Reminders</div>
                                            <div class="notification-description">Send payment due reminders via SMS</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Save Notification Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Danger Zone Section -->
        <div class="profile-section" id="danger-section">
            <div class="card danger-zone">
                <div class="card-header">
                    <h3 class="card-title text-red-600">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Danger Zone
                    </h3>
                    <p class="card-subtitle">Permanently delete your account and all associated data</p>
                </div>
                <div class="card-body">
                    <div class="danger-content">
                        <div class="danger-warning">
                            <i class="fas fa-exclamation-circle"></i>
                            <div class="warning-content">
                                <h4 class="warning-title">Delete Your Account</h4>
                                <p class="warning-description">
                                    Once your account is deleted, all of its resources and data will be permanently deleted. 
                                    Before deleting your account, please download any data or information that you wish to retain.
                                </p>
                            </div>
                        </div>

                        <!-- Delete Account Form -->
                        <form method="post" action="{{ route('admin.profile.destroy') }}" class="delete-form">
                            @csrf
                            @method('delete')

                            <div class="delete-actions">
                                <button type="button" class="btn btn-danger" onclick="openDeleteModal()">
                                    <i class="fas fa-trash"></i>
                                    Delete Account
                                </button>
                            </div>

                            <!-- Delete Confirmation Modal -->
                            <div class="modal-overlay" id="delete-modal">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title">Delete Account</h3>
                                        <button type="button" class="modal-close" onclick="closeDeleteModal()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="modal-text">
                                            Are you sure you want to delete your account? This action cannot be undone. 
                                            All your data will be permanently removed.
                                        </p>
                                        <div class="form-group">
                                            <label for="delete_password" class="form-label">Enter your password to confirm:</label>
                                            <input type="password" id="delete_password" name="password" 
                                                   class="form-control" placeholder="Your current password" required>
                                            @error('password', 'userDeletion')
                                                <div class="form-error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                                            Cancel
                                        </button>
                                        <button type="submit" class="btn btn-danger">
                                            Delete Account
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Profile Layout */
.profile-layout {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 2rem;
    align-items: start;
}

.profile-sidebar {
    position: sticky;
    top: 2rem;
}

.profile-content {
    min-height: 600px;
}

/* Profile Summary */
.profile-summary {
    padding: 1.5rem 0;
}

.profile-avatar {
    position: relative;
    display: inline-block;
    cursor: pointer;
}

.profile-image {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 4px solid var(--gray-200);
    transition: all 0.3s ease;
}

.avatar-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    opacity: 0;
    transition: all 0.3s ease;
}

.profile-avatar:hover .avatar-overlay {
    opacity: 1;
}

.profile-avatar:hover .profile-image {
    transform: scale(1.05);
}

.avatar-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2.5rem;
    font-weight: 600;
    border: 4px solid var(--gray-200);
    transition: all 0.3s ease;
}

.profile-avatar:hover .avatar-placeholder {
    transform: scale(1.05);
    border-color: var(--primary);
}

.profile-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.profile-email {
    color: var(--gray-600);
    margin-bottom: 1rem;
}

/* Verification Status */
.verification-alert {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: rgba(248, 150, 30, 0.1);
    color: var(--warning);
    border-radius: 2rem;
    font-size: 0.875rem;
    font-weight: 600;
}

.verification-success {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: rgba(76, 201, 240, 0.1);
    color: var(--success);
    border-radius: 2rem;
    font-size: 0.875rem;
    font-weight: 600;
}

.verification-notice {
    background: rgba(248, 150, 30, 0.1);
    border: 1px solid rgba(248, 150, 30, 0.2);
    border-radius: var(--border-radius);
    padding: 1rem;
    margin-top: 1rem;
    font-size: 0.875rem;
}

.verification-link {
    background: none;
    border: none;
    color: var(--primary);
    text-decoration: underline;
    cursor: pointer;
    font-size: 0.875rem;
}

/* Profile Stats */
.profile-stats {
    margin: 2rem 0;
}

.stat-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: var(--gray-50);
    border-radius: var(--border-radius);
}

.stat-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.stat-icon.blue {
    background: rgba(67, 97, 238, 0.1);
    color: var(--primary);
}

.stat-icon.green {
    background: rgba(76, 201, 240, 0.1);
    color: var(--success);
}

.stat-info {
    flex: 1;
}

.stat-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
    line-height: 1;
}

.stat-label {
    font-size: 0.75rem;
    color: var(--gray-600);
    margin-top: 0.25rem;
}

/* Profile Navigation */
.profile-nav {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.profile-nav .profile-nav-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    color: var(--gray-700);
    text-decoration: none;
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
    border: none;
    background: none;
    text-align: left;
    cursor: pointer;
}

.profile-nav .profile-nav-item:hover {
    background: var(--gray-50);
    color: var(--primary);
}

.profile-nav .profile-nav-item.active {
    background: var(--primary-light);
    color: var(--primary);
    font-weight: 600;
}

/* Profile Sections */
.profile-section {
    display: none;
}

.profile-section.active {
    display: block;
}

.card-subtitle {
    color: var(--gray-600);
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

/* Form Styles */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.form-group.full-width {
    grid-column: 1 / -1;
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

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-start;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-color);
}

/* File Upload */
.file-upload-area {
    display: flex;
    gap: 2rem;
    align-items: flex-start;
}

.upload-preview {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 2px dashed var(--border-color);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden; /* Add this to ensure content stays within circle */
    position: relative; /* Add this for better positioning */
}

.upload-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center; /* Add this for vertical centering */
    gap: 0.5rem;
    color: var(--gray-500);
    width: 100%; /* Ensure it takes full width of container */
    height: 100%; /* Ensure it takes full height of container */
    text-align: center; /* Center the text */
    padding: 1rem; /* Add some padding */
}

.upload-controls {
    flex: 1;
}

.file-input {
    display: none;
}

.file-info {
    font-size: 0.75rem;
    color: var(--gray-500);
    margin-top: 0.5rem;
}

/* Checkbox Styles */
.checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    cursor: pointer;
    padding: 0.75rem;
    border-radius: var(--border-radius);
    transition: background-color 0.3s ease;
}

.checkbox-label:hover {
    background: var(--gray-50);
}

.checkbox-input {
    display: none;
}

.checkbox-custom {
    width: 1.25rem;
    height: 1.25rem;
    border: 2px solid var(--border-color);
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 0.125rem;
    transition: all 0.3s ease;
}

.checkbox-input:checked + .checkbox-custom {
    background: var(--primary);
    border-color: var(--primary);
}

.checkbox-input:checked + .checkbox-custom::after {
    content: '✓';
    color: white;
    font-size: 0.75rem;
    font-weight: bold;
}

.checkbox-description {
    font-size: 0.875rem;
    color: var(--gray-600);
    margin-left: 2rem;
}

/* Password Strength */
.password-strength {
    margin: 1.5rem 0;
    padding: 1rem;
    background: var(--gray-50);
    border-radius: var(--border-radius);
}

.strength-labels {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.strength-text {
    font-weight: 600;
    font-size: 0.875rem;
}

.strength-bars {
    height: 4px;
    background: var(--gray-300);
    border-radius: 2px;
    overflow: hidden;
}

.strength-bar {
    height: 100%;
    width: 0%;
    background: var(--danger);
    transition: all 0.3s ease;
}

.strength-bar.weak { width: 25%; background: var(--danger); }
.strength-bar.fair { width: 50%; background: var(--warning); }
.strength-bar.good { width: 75%; background: var(--info); }
.strength-bar.strong { width: 100%; background: var(--success); }

/* Password Requirements */
.password-requirements {
    margin: 1.5rem 0;
    padding: 1rem;
    background: var(--gray-50);
    border-radius: var(--border-radius);
}

.requirements-title {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: var(--dark);
}

.requirements-list {
    list-style: none;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0.5rem;
}

.requirement-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: var(--gray-600);
}

.requirement-item.valid {
    color: var(--success);
}

.requirement-item.valid .requirement-icon {
    color: var(--success);
}

.requirement-icon {
    font-size: 0.5rem;
    color: var(--gray-400);
    transition: color 0.3s ease;
}

/* Notification Settings */
.notification-settings {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.notification-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: all 0.3s ease;
}

.notification-item:hover {
    border-color: var(--primary);
    background: var(--primary-light);
}

.notification-item input[type="checkbox"] {
    margin-top: 0.25rem;
}

.notification-content {
    flex: 1;
}

.notification-title {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.25rem;
}

.notification-description {
    font-size: 0.875rem;
    color: var(--gray-600);
}

/* Danger Zone */
.danger-zone {
    border: 2px solid var(--danger);
}

.danger-content {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.danger-warning {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem;
    background: rgba(247, 37, 133, 0.05);
    border-radius: var(--border-radius);
    border: 1px solid rgba(247, 37, 133, 0.2);
}

.danger-warning i {
    color: var(--danger);
    font-size: 1.5rem;
    margin-top: 0.25rem;
}

.warning-content {
    flex: 1;
}

.warning-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--danger);
    margin-bottom: 0.5rem;
}

.warning-description {
    color: var(--gray-700);
    line-height: 1.6;
}

.delete-actions {
    display: flex;
    justify-content: flex-end;
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 1rem;
}

.modal-overlay.active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-xl);
    width: 100%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: between;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
}

.modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--dark);
}

.modal-close {
    background: none;
    border: none;
    color: var(--gray-500);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.modal-close:hover {
    background: var(--gray-100);
    color: var(--gray-700);
}

.modal-body {
    padding: 1.5rem;
}

.modal-text {
    color: var(--gray-700);
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.modal-footer {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding: 1.5rem;
    border-top: 1px solid var(--border-color);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .profile-layout {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .profile-sidebar {
        position: static;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .file-upload-area {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .stat-grid {
        grid-template-columns: 1fr;
    }
    
    .requirements-list {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .modal-footer {
        flex-direction: column;
    }
}

/* Color Utilities */
.text-blue-600 { color: #2563eb; }
.text-green-600 { color: #059669; }
.text-purple-600 { color: #7c3aed; }
.text-orange-600 { color: #ea580c; }
.text-red-600 { color: #dc2626; }
</style>

<script>
// Profile Section Navigation
document.addEventListener('DOMContentLoaded', function() {
    // Section navigation
    const navItems = document.querySelectorAll('.profile-nav-item');
    const sections = document.querySelectorAll('.profile-section');
    
    navItems.forEach(item => {
        item.addEventListener('click', function() {
            const targetSection = this.getAttribute('data-section');
            
            // Update active nav item
            navItems.forEach(nav => nav.classList.remove('active'));
            this.classList.add('active');
            
            // Show target section
            sections.forEach(section => {
                section.classList.remove('active');
                if (section.id === `${targetSection}-section`) {
                    section.classList.add('active');
                }
            });
        });
    });
    
    // Profile picture preview
    const profilePictureInput = document.getElementById('profile_picture');
    const profilePreview = document.getElementById('profile-preview');
    
    if (profilePictureInput) {
        profilePictureInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (profilePreview) {
                        profilePreview.src = e.target.result;
                    } else {
                        // Create new preview if it doesn't exist
                        const preview = document.createElement('img');
                        preview.src = e.target.result;
                        preview.alt = 'Profile preview';
                        preview.id = 'profile-preview';
                        preview.className = 'profile-image';
                        
                        const placeholder = document.querySelector('.upload-placeholder');
                        if (placeholder) {
                            placeholder.replaceWith(preview);
                        }
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Password strength meter
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');
    const requirementItems = document.querySelectorAll('.requirement-item');
    
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            
            // Update strength bar
            strengthBar.className = 'strength-bar';
            strengthBar.classList.add(strength.class);
            strengthBar.style.width = strength.percentage + '%';
            
            // Update strength text
            strengthText.textContent = strength.text;
            strengthText.style.color = strength.color;
            
            // Update requirement checks
            updatePasswordRequirements(password);
        });
    }
    
    function calculatePasswordStrength(password) {
        let score = 0;
        let requirements = {
            length: password.length >= 8,
            lowercase: /[a-z]/.test(password),
            uppercase: /[A-Z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[^A-Za-z0-9]/.test(password)
        };
        
        // Calculate score
        Object.values(requirements).forEach(met => {
            if (met) score++;
        });
        
        // Determine strength
        if (password.length === 0) {
            return { class: '', percentage: 0, text: 'None', color: 'var(--gray)' };
        } else if (score <= 2) {
            return { class: 'weak', percentage: 25, text: 'Weak', color: 'var(--danger)' };
        } else if (score <= 3) {
            return { class: 'fair', percentage: 50, text: 'Fair', color: 'var(--warning)' };
        } else if (score <= 4) {
            return { class: 'good', percentage: 75, text: 'Good', color: 'var(--info)' };
        } else {
            return { class: 'strong', percentage: 100, text: 'Strong', color: 'var(--success)' };
        }
    }
    
    function updatePasswordRequirements(password) {
        const requirements = {
            length: password.length >= 8,
            lowercase: /[a-z]/.test(password),
            uppercase: /[A-Z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[^A-Za-z0-9]/.test(password)
        };
        
        requirementItems.forEach(item => {
            const requirement = item.getAttribute('data-requirement');
            const icon = item.querySelector('.requirement-icon');
            
            if (requirements[requirement]) {
                item.classList.add('valid');
                icon.className = 'fas fa-check requirement-icon';
            } else {
                item.classList.remove('valid');
                icon.className = 'fas fa-circle requirement-icon';
            }
        });
    }
});

// Delete Account Modal
function openDeleteModal() {
    document.getElementById('delete-modal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.remove('active');
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('delete-modal');
    if (e.target === modal) {
        closeDeleteModal();
    }
});
</script>
@endsection