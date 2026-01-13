@extends('layouts.app')

@section('title', 'Account Settings - Priority Accommodations')
@section('page-title', 'Account Settings')
@section('page-subtitle', 'Manage your account preferences and security')

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Account Settings</h2>
            <p class="text-gray-600">Manage your account preferences and security</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Account Preferences -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-cog text-blue-600 mr-2"></i>
                        Account Preferences
                    </h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success fade-in">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('account.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Time Zone *</label>
                                <select name="timezone" class="form-control" required>
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
                                        <option value="{{ $value }}" {{ old('timezone', $user->timezone) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('timezone')
                                <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Language *</label>
                                <select name="language" class="form-control" required>
                                    <option value="">Select Language</option>
                                    <option value="en" {{ old('language', $user->language) == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="es" {{ old('language', $user->language) == 'es' ? 'selected' : '' }}>Spanish</option>
                                    <option value="fr" {{ old('language', $user->language) == 'fr' ? 'selected' : '' }}>French</option>
                                    <option value="de" {{ old('language', $user->language) == 'de' ? 'selected' : '' }}>German</option>
                                    <option value="hi" {{ old('language', $user->language) == 'hi' ? 'selected' : '' }}>Hindi</option>
                                </select>
                                @error('language')
                                <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="notifications" class="checkbox-input" 
                                           value="1" {{ old('notifications', $user->notifications) ? 'checked' : '' }}>
                                    <span class="checkbox-custom"></span>
                                    Enable email notifications
                                </label>
                                <div class="checkbox-description">
                                    Receive notifications about bookings, updates, and system alerts.
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-key text-green-600 mr-2"></i>
                        Change Password
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('account.password.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label class="form-label">Current Password *</label>
                            <input type="password" name="current_password" class="form-control" required
                                   placeholder="Enter your current password">
                            @error('current_password')
                            <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">New Password *</label>
                                <input type="password" name="password" class="form-control" required
                                       placeholder="Enter new password">
                                @error('password')
                                <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Confirm New Password *</label>
                                <input type="password" name="password_confirmation" class="form-control" required
                                       placeholder="Confirm new password">
                            </div>
                        </div>

                        <div class="password-strength">
                            <div class="strength-meter">
                                <div class="strength-bar"></div>
                            </div>
                            <div class="strength-text text-sm text-gray-600">
                                Password strength: <span class="strength-value">None</span>
                            </div>
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

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Security Tips -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-shield-alt text-purple-600 mr-2"></i>
                        Security Tips
                    </h3>
                </div>
                <div class="card-body">
                    <div class="security-tips space-y-4">
                        <div class="tip-item">
                            <div class="tip-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="tip-content">
                                <div class="tip-title">Use a strong password</div>
                                <div class="tip-description">Include letters, numbers, and special characters</div>
                            </div>
                        </div>
                        <div class="tip-item">
                            <div class="tip-icon">
                                <i class="fas fa-sync-alt"></i>
                            </div>
                            <div class="tip-content">
                                <div class="tip-title">Change password regularly</div>
                                <div class="tip-description">Update your password every 3-6 months</div>
                            </div>
                        </div>
                        <div class="tip-item">
                            <div class="tip-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="tip-content">
                                <div class="tip-title">Enable notifications</div>
                                <div class="tip-description">Stay informed about system activities</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-link text-orange-600 mr-2"></i>
                        Quick Links
                    </h3>
                </div>
                <div class="card-body">
                    <div class="quick-links space-y-3">
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-secondary w-full justify-center">
                            <i class="fas fa-user"></i>
                            Edit Profile
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary w-full justify-center">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                        <a href="{{ route('settings') }}" class="btn btn-secondary w-full justify-center">
                            <i class="fas fa-cogs"></i>
                            System Settings
                        </a>
                    </div>
                </div>
            </div>

            <!-- Account Status -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Account Status
                    </h3>
                </div>
                <div class="card-body">
                    <div class="account-status space-y-3">
                        <div class="status-item">
                            <div class="status-label">Member Since</div>
                            <div class="status-value">{{ $user->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="status-item">
                            <div class="status-label">Last Updated</div>
                            <div class="status-value">{{ $user->updated_at->format('M d, Y') }}</div>
                        </div>
                        <div class="status-item">
                            <div class="status-label">Email Verified</div>
                            <div class="status-value status-verified">
                                <i class="fas fa-check-circle"></i>
                                Verified
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Form Styles */
    .space-y-6 > * + * {
        margin-top: 1.5rem;
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
        border: 2px solid var(--gray-200);
        border-radius: var(--radius);
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background: white;
        font-family: 'Inter', sans-serif;
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

    /* Checkbox Styles */
    .checkbox-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        font-weight: 500;
        color: var(--dark);
    }

    .checkbox-input {
        display: none;
    }

    .checkbox-custom {
        width: 1.25rem;
        height: 1.25rem;
        border: 2px solid var(--gray-300);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        position: relative;
    }

    .checkbox-custom::after {
        content: '✓';
        color: white;
        font-size: 0.75rem;
        font-weight: bold;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .checkbox-input:checked + .checkbox-custom {
        background: var(--primary);
        border-color: var(--primary);
    }

    .checkbox-input:checked + .checkbox-custom::after {
        opacity: 1;
    }

    .checkbox-description {
        color: var(--gray-600);
        font-size: 0.875rem;
        margin-left: 2rem;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: flex-start;
        padding-top: 1rem;
        border-top: 1px solid var(--gray-200);
    }

    /* Security Tips */
    .security-tips {
        display: flex;
        flex-direction: column;
    }

    .tip-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        background: var(--gray-50);
        border-radius: var(--radius);
        border-left: 4px solid var(--primary);
    }

    .tip-icon {
        color: var(--primary);
        font-size: 1.25rem;
        width: 1.5rem;
        text-align: center;
    }

    .tip-content {
        flex: 1;
    }

    .tip-title {
        font-weight: 600;
        color: var(--dark);
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .tip-description {
        color: var(--gray-600);
        font-size: 0.75rem;
    }

    /* Quick Links */
    .quick-links {
        display: flex;
        flex-direction: column;
    }

    /* Account Status */
    .account-status {
        display: flex;
        flex-direction: column;
    }

    .status-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--gray-200);
    }

    .status-item:last-child {
        border-bottom: none;
    }

    .status-label {
        color: var(--gray-600);
        font-size: 0.875rem;
    }

    .status-value {
        color: var(--dark);
        font-weight: 600;
        font-size: 0.875rem;
    }

    .status-verified {
        color: var(--success);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Password Strength Meter */
    .password-strength {
        margin-top: 1rem;
    }

    .strength-meter {
        width: 100%;
        height: 4px;
        background: var(--gray-200);
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }

    .strength-bar {
        height: 100%;
        width: 0%;
        background: var(--danger);
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    .strength-bar.weak { width: 25%; background: var(--danger); }
    .strength-bar.fair { width: 50%; background: var(--warning); }
    .strength-bar.good { width: 75%; background: var(--accent); }
    .strength-bar.strong { width: 100%; background: var(--success); }

    .strength-value {
        font-weight: 600;
    }

    .strength-value.weak { color: var(--danger); }
    .strength-value.fair { color: var(--warning); }
    .strength-value.good { color: var(--accent); }
    .strength-value.strong { color: var(--success); }

    /* Grid System */
    .grid {
        display: grid;
    }

    .grid-cols-1 { grid-template-columns: repeat(1, 1fr); }
    .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }

    @media (min-width: 768px) {
        .md\:grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
    }

    @media (min-width: 1024px) {
        .lg\:grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
        .lg\:col-span-2 { grid-column: span 2 / span 2; }
    }

    .gap-4 { gap: 1rem; }
    .gap-6 { gap: 1.5rem; }

    .space-y-3 > * + * { margin-top: 0.75rem; }
    .space-y-4 > * + * { margin-top: 1rem; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password strength meter
    const passwordInput = document.querySelector('input[name="password"]');
    const strengthBar = document.querySelector('.strength-bar');
    const strengthValue = document.querySelector('.strength-value');

    if (passwordInput && strengthBar && strengthValue) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let text = 'None';

            if (password.length >= 8) strength += 25;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 25;
            if (password.match(/\d/)) strength += 25;
            if (password.match(/[^a-zA-Z\d]/)) strength += 25;

            // Update strength bar
            strengthBar.className = 'strength-bar';
            if (strength >= 75) {
                strengthBar.classList.add('strong');
                text = 'Strong';
            } else if (strength >= 50) {
                strengthBar.classList.add('good');
                text = 'Good';
            } else if (strength >= 25) {
                strengthBar.classList.add('fair');
                text = 'Fair';
            } else if (password.length > 0) {
                strengthBar.classList.add('weak');
                text = 'Weak';
            }

            strengthValue.textContent = text;
            strengthValue.className = 'strength-value ' + text.toLowerCase();
        });
    }

    // Add focus effects to form controls
    const formControls = document.querySelectorAll('.form-control');
    formControls.forEach(control => {
        control.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });

        control.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });

    // Enhanced checkbox interactions
    const checkboxes = document.querySelectorAll('.checkbox-input');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.closest('.checkbox-label');
            if (this.checked) {
                label.classList.add('checked');
            } else {
                label.classList.remove('checked');
            }
        });
    });
});
</script>
@endsection