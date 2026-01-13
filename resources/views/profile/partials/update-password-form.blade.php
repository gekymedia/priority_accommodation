<!-- resources/views/profile/partials/update-password-form.blade.php -->
<div class="password-form-section">
    <div class="form-header">
        <h3 class="form-title">
            <i class="fas fa-lock text-green-600 mr-2"></i>
            Update Password
        </h3>
        <p class="form-subtitle">Ensure your account is using a long, random password to stay secure</p>
    </div>

    {{-- FIXED: Changed route to profile.password.update --}}
    <form method="POST" action="{{ route('admin.profile.password.update') }}" class="modern-form">
        @csrf
        @method('patch') {{-- Changed from 'put' to 'patch' to match ProfileController --}}

        <div class="form-grid">
            <!-- Current Password -->
            <div class="form-group">
                <label for="current_password" class="form-label">Current Password *</label>
                <input type="password" id="current_password" name="current_password" 
                       class="form-control" autocomplete="current-password" required>
                @error('current_password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- New Password -->
            <div class="form-group">
                <label for="password" class="form-label">New Password *</label>
                <input type="password" id="password" name="password" 
                       class="form-control" autocomplete="new-password" required>
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm New Password *</label>
                <input type="password" id="password_confirmation" name="password_confirmation" 
                       class="form-control" autocomplete="new-password" required>
                @error('password_confirmation')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Password Strength Meter -->
        <div class="password-strength-meter">
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

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-key"></i>
                Update Password
            </button>
            
            @if (session('status') === 'password-updated')
                <div class="form-success">
                    <i class="fas fa-check-circle"></i>
                    Password updated successfully!
                </div>
            @endif
        </div>
    </form>
</div>

{{-- CSS and JavaScript remain the same --}}
<style>
.password-strength-meter {
    background: var(--gray-50);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    margin: 1.5rem 0;
}

.strength-labels {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.strength-text {
    font-weight: 600;
}

.strength-bars {
    height: 6px;
    background: var(--gray-200);
    border-radius: 3px;
    overflow: hidden;
}

.strength-bar {
    height: 100%;
    width: 0%;
    transition: all 0.3s ease;
    border-radius: 3px;
}

.password-requirements {
    background: var(--gray-50);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    margin: 1.5rem 0;
}

.requirements-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 1rem;
}

.requirements-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0.75rem;
}

.requirement-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.875rem;
    color: var(--gray-600);
}

.requirement-icon {
    font-size: 0.5rem;
    color: var(--gray-400);
    transition: all 0.3s ease;
}

.requirement-item.valid .requirement-icon {
    color: var(--success);
}

.requirement-item.valid {
    color: var(--success);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');
    const requirementItems = document.querySelectorAll('.requirement-item');

    if (passwordInput && strengthBar && strengthText) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            updatePasswordStrength(password);
            updateRequirements(password);
        });
    }

    function updatePasswordStrength(password) {
        let strength = 0;
        const requirements = {
            length: password.length >= 8,
            lowercase: /[a-z]/.test(password),
            uppercase: /[A-Z]/.test(password),
            number: /\d/.test(password),
            special: /[^a-zA-Z\d]/.test(password)
        };

        // Calculate strength based on requirements met
        const metRequirements = Object.values(requirements).filter(Boolean).length;
        strength = (metRequirements / 5) * 100;

        // Update strength bar
        strengthBar.style.width = strength + '%';

        // Update strength text and color
        if (strength < 40) {
            strengthBar.style.background = 'var(--danger)';
            strengthText.textContent = 'Weak';
            strengthText.style.color = 'var(--danger)';
        } else if (strength < 70) {
            strengthBar.style.background = 'var(--warning)';
            strengthText.textContent = 'Medium';
            strengthText.style.color = 'var(--warning)';
        } else {
            strengthBar.style.background = 'var(--success)';
            strengthText.textContent = 'Strong';
            strengthText.style.color = 'var(--success)';
        }
    }

    function updateRequirements(password) {
        requirementItems.forEach(item => {
            const requirement = item.getAttribute('data-requirement');
            let isValid = false;

            switch (requirement) {
                case 'length':
                    isValid = password.length >= 8;
                    break;
                case 'lowercase':
                    isValid = /[a-z]/.test(password);
                    break;
                case 'uppercase':
                    isValid = /[A-Z]/.test(password);
                    break;
                case 'number':
                    isValid = /\d/.test(password);
                    break;
                case 'special':
                    isValid = /[^a-zA-Z\d]/.test(password);
                    break;
            }

            if (isValid) {
                item.classList.add('valid');
            } else {
                item.classList.remove('valid');
            }
        });
    }
});
</script>