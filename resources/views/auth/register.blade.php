<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Priority Accommodations</title>
    
    <!-- Modern Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            /* Ghana-inspired Color Palette */
            --primary: #006b3f;
            --primary-dark: #005530;
            --primary-light: #e8f5f0;
            --secondary: #ce1126;
            --accent: #fcd116;
            --accent-dark: #e5bc00;
            --success: #10b981;
            --warning: #fcd116;
            --danger: #ce1126;
            --dark: #1a202c;
            --light: #f7fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            
            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            
            /* Border Radius */
            --radius-sm: 0.375rem;
            --radius: 0.5rem;
            --radius-md: 0.75rem;
            --radius-lg: 1rem;
            --radius-xl: 1.5rem;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #006b3f 0%, #004d2c 50%, #003d23 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            color: var(--dark);
            line-height: 1.6;
            position: relative;
            overflow-x: hidden;
        }

        /* Decorative Ghana Flag Elements */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary) 33.33%, var(--accent) 33.33%, var(--accent) 66.66%, var(--primary) 66.66%);
            z-index: 1000;
        }

        .floating-star {
            position: fixed;
            color: var(--accent);
            opacity: 0.15;
            font-size: 120px;
            z-index: 0;
            animation: float-star 6s ease-in-out infinite;
        }

        .floating-star:nth-child(1) { top: 10%; left: 5%; animation-delay: 0s; }
        .floating-star:nth-child(2) { bottom: 15%; right: 8%; animation-delay: 2s; }

        @keyframes float-star {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
        }

        .register-container {
            width: 100%;
            max-width: 480px;
            position: relative;
            z-index: 1;
        }

        .glass-container {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl), 0 0 60px rgba(0, 107, 63, 0.2);
        }

        .register-card {
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .register-content {
            position: relative;
            z-index: 1;
        }

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-logo {
            width: 4.5rem;
            height: 4.5rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            color: white;
            font-size: 1.75rem;
            box-shadow: var(--shadow-lg), 0 0 20px rgba(0, 107, 63, 0.3);
            position: relative;
        }

        .register-logo::after {
            content: '★';
            position: absolute;
            top: -8px;
            right: -8px;
            color: var(--accent);
            font-size: 1.25rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .register-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .register-subtitle {
            color: var(--gray-600);
            font-size: 0.95rem;
            font-weight: 400;
        }

        /* Step Indicator */
        .step-indicator {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }

        .step-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--gray-300);
            transition: all 0.3s ease;
        }

        .step-dot.active {
            background: var(--primary);
            width: 30px;
            border-radius: 5px;
        }

        .step-dot.completed {
            background: var(--success);
        }

        /* Form Sections */
        .form-section {
            display: none;
            animation: fadeIn 0.4s ease;
        }

        .form-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .form-label .required {
            color: var(--danger);
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius);
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 107, 63, 0.1);
        }

        .form-control.error {
            border-color: var(--danger);
        }

        .form-control.success {
            border-color: var(--success);
        }

        .form-hint {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-top: 0.35rem;
        }

        .form-error {
            font-size: 0.75rem;
            color: var(--danger);
            margin-top: 0.35rem;
        }

        /* Phone Input Group */
        .phone-input-group {
            display: flex;
            gap: 0;
        }

        .phone-prefix {
            padding: 0.875rem 1rem;
            background: var(--gray-100);
            border: 2px solid var(--gray-200);
            border-right: none;
            border-radius: var(--radius) 0 0 var(--radius);
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--gray-700);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .phone-prefix img {
            width: 20px;
            height: 14px;
            border-radius: 2px;
        }

        .phone-input-group .form-control {
            border-radius: 0 var(--radius) var(--radius) 0;
        }

        /* Lookup Result Card */
        .lookup-result {
            background: var(--primary-light);
            border: 2px solid var(--primary);
            border-radius: var(--radius-md);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            animation: slideIn 0.3s ease;
        }

        .lookup-result.not-found {
            background: #fef3c7;
            border-color: var(--accent-dark);
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .lookup-result-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .lookup-result-icon {
            width: 36px;
            height: 36px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .lookup-result.not-found .lookup-result-icon {
            background: var(--accent-dark);
        }

        .lookup-result-title {
            font-weight: 600;
            color: var(--dark);
            font-size: 0.95rem;
        }

        .lookup-result-subtitle {
            font-size: 0.8rem;
            color: var(--gray-600);
        }

        .lookup-result-data {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            font-size: 0.85rem;
        }

        .lookup-result-data span {
            color: var(--gray-600);
        }

        .lookup-result-data strong {
            color: var(--dark);
        }

        /* Form Grid */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 480px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg), 0 0 20px rgba(0, 107, 63, 0.3);
        }

        .btn-secondary {
            background: white;
            color: var(--gray-700);
            border: 2px solid var(--gray-300);
        }

        .btn-secondary:hover:not(:disabled) {
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .btn-group {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .btn-group .btn {
            flex: 1;
        }

        /* Spinner */
        .spinner {
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Password Strength */
        .password-strength {
            margin-top: 0.5rem;
        }

        .strength-bar {
            height: 4px;
            background: var(--gray-200);
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak { background: var(--danger); width: 33%; }
        .strength-medium { background: var(--accent); width: 66%; }
        .strength-strong { background: var(--success); width: 100%; }

        .strength-text {
            font-size: 0.7rem;
            margin-top: 0.25rem;
            color: var(--gray-500);
        }

        /* Terms */
        .terms-group {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-top: 1.5rem;
            padding: 1rem;
            background: var(--gray-100);
            border-radius: var(--radius);
        }

        .terms-group input[type="checkbox"] {
            margin-top: 0.15rem;
            flex-shrink: 0;
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
        }

        .terms-text {
            font-size: 0.8rem;
            color: var(--gray-700);
            line-height: 1.5;
        }

        .terms-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .terms-link:hover {
            text-decoration: underline;
        }

        /* Alert */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: var(--radius);
            margin-bottom: 1.25rem;
            font-size: 0.875rem;
            border-left: 4px solid;
        }

        .alert-danger {
            background: rgba(206, 17, 38, 0.05);
            color: var(--danger);
            border-left-color: var(--danger);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.05);
            color: var(--success);
            border-left-color: var(--success);
        }

        /* Login Section */
        .login-section {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--gray-200);
        }

        .login-text {
            color: var(--gray-600);
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
        }

        .login-link {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        .login-link:hover {
            text-decoration: underline;
        }

        /* Back Home */
        .back-home {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary);
            text-decoration: none;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            transition: color 0.3s ease;
        }

        .back-home:hover {
            color: var(--primary-dark);
        }

        /* Footer */
        .register-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--gray-200);
        }

        .footer-text {
            color: var(--gray-500);
            font-size: 0.75rem;
        }

        .footer-text.brand {
            color: var(--primary);
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .register-card {
                padding: 1.75rem;
            }
            
            .register-title {
                font-size: 1.5rem;
            }

            .floating-star {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Decorative Stars -->
    <i class="fas fa-star floating-star"></i>
    <i class="fas fa-star floating-star"></i>

    <div class="register-container">
        <div class="glass-container register-card">
            <div class="register-content">
                <!-- Back to Home -->
                <a href="{{ url('/') }}" class="back-home">
                    <i class="fas fa-arrow-left"></i>
                    Back to Home
                </a>

                <div class="register-header">
                    <div class="register-logo">
                        <i class="fas fa-building"></i>
                    </div>
                    <h1 class="register-title">Create Account</h1>
                    <p class="register-subtitle">Register for Priority Accommodations</p>
                </div>

                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step-dot active" id="stepDot1"></div>
                    <div class="step-dot" id="stepDot2"></div>
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <p><i class="fas fa-exclamation-circle"></i> {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @if(session('status'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> {{ session('status') }}
                    </div>
                @endif

                <!-- STEP 1: Phone Number Lookup -->
                <div class="form-section active" id="step1">
                    <div class="form-group">
                        <label for="phone_lookup" class="form-label">Phone Number <span class="required">*</span></label>
                        <div class="phone-input-group">
                            <span class="phone-prefix">
                                🇬🇭 +233
                            </span>
                            <input
                                type="tel"
                                id="phone_lookup"
                                class="form-control"
                                placeholder="e.g., 0241234567"
                                maxlength="10"
                                autocomplete="tel"
                            >
                        </div>
                        <div class="form-hint">Enter your Ghana phone number to check if you're in the CUG system</div>
                        <div class="form-error" id="phoneError" style="display: none;"></div>
                    </div>

                    <button type="button" class="btn btn-primary" id="lookupBtn" onclick="lookupPhone()">
                        <span id="lookupBtnText">Check My Number</span>
                        <div class="spinner" id="lookupSpinner" style="display: none;"></div>
                    </button>
                </div>

                <!-- STEP 2: Registration Form -->
                <div class="form-section" id="step2">
                    <!-- Lookup Result (shown if found) -->
                    <div class="lookup-result" id="lookupResult" style="display: none;">
                        <div class="lookup-result-header">
                            <div class="lookup-result-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <div class="lookup-result-title" id="resultName">Student Found!</div>
                                <div class="lookup-result-subtitle" id="resultSubtitle">Your details have been auto-filled</div>
                            </div>
                        </div>
                        <div class="lookup-result-data" id="resultData"></div>
                    </div>

                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf
                        
                        <!-- Hidden phone field -->
                        <input type="hidden" name="phone" id="phone">
                        
                        <div class="form-row">
                            <!-- First Name -->
                            <div class="form-group">
                                <label for="first_name" class="form-label">First Name <span class="required">*</span></label>
                                <input
                                    type="text"
                                    id="first_name"
                                    name="first_name"
                                    class="form-control"
                                    placeholder="Enter first name"
                                    value="{{ old('first_name') }}"
                                    required
                                >
                            </div>

                            <!-- Last Name -->
                            <div class="form-group">
                                <label for="last_name" class="form-label">Last Name <span class="required">*</span></label>
                                <input
                                    type="text"
                                    id="last_name"
                                    name="last_name"
                                    class="form-control"
                                    placeholder="Enter last name"
                                    value="{{ old('last_name') }}"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Email Address -->
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address <span class="required">*</span></label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                placeholder="Enter your email address"
                                value="{{ old('email') }}"
                                required
                            >
                        </div>

                        <!-- Student ID (Optional) -->
                        <div class="form-group">
                            <label for="student_id" class="form-label">Student ID</label>
                            <input
                                type="text"
                                id="student_id"
                                name="student_id"
                                class="form-control"
                                placeholder="e.g., CUG/2024/0001"
                                value="{{ old('student_id') }}"
                            >
                            <div class="form-hint">Optional - Your CUG student ID if you have one</div>
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password" class="form-label">Password <span class="required">*</span></label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="Create a secure password"
                                required
                                minlength="8"
                            >
                            <div class="password-strength">
                                <div class="strength-bar">
                                    <div class="strength-fill" id="strengthFill"></div>
                                </div>
                                <div class="strength-text" id="strengthText">Minimum 8 characters</div>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="required">*</span></label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control"
                                placeholder="Confirm your password"
                                required
                            >
                            <div class="form-hint" id="passwordMatchText"></div>
                        </div>

                        <!-- Terms -->
                        <div class="terms-group">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms" class="terms-text">
                                I agree to the <a href="{{ route('terms') }}" class="terms-link" target="_blank">Terms of Service</a> and <a href="{{ route('privacy') }}" class="terms-link" target="_blank">Privacy Policy</a>.
                            </label>
                        </div>

                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary" onclick="goToStep(1)">
                                <i class="fas fa-arrow-left"></i>
                                Back
                            </button>
                            <button type="submit" class="btn btn-primary" id="registerButton">
                                <span id="buttonText">Create Account</span>
                                <div class="spinner" id="spinner" style="display: none;"></div>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Login Section -->
                @if (Route::has('login'))
                    <div class="login-section">
                        <p class="login-text">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="login-link">Sign In</a>
                        </p>
                    </div>
                @endif
                
                <div class="register-footer">
                    <p class="footer-text brand">Priority Accommodations</p>
                    <p class="footer-text" style="margin-top: 0.25rem;">
                        © {{ date('Y') }} • Powered by CUG
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Store the phone number and lookup data
        let lookupData = null;
        let currentPhone = '';

        // Phone number validation (Ghana format)
        function isValidGhanaPhone(phone) {
            // Remove any spaces or dashes
            phone = phone.replace(/[\s-]/g, '');
            // Ghana phone: starts with 0, second digit is 2, 3, or 5, total 10 digits
            return /^0[235][0-9]{8}$/.test(phone);
        }

        // Format phone for display
        function formatPhone(phone) {
            if (phone.length === 10) {
                return phone.substring(0, 3) + ' ' + phone.substring(3, 6) + ' ' + phone.substring(6);
            }
            return phone;
        }

        // Lookup phone number
        async function lookupPhone() {
            const phoneInput = document.getElementById('phone_lookup');
            const phoneError = document.getElementById('phoneError');
            const lookupBtn = document.getElementById('lookupBtn');
            const lookupBtnText = document.getElementById('lookupBtnText');
            const lookupSpinner = document.getElementById('lookupSpinner');

            let phone = phoneInput.value.trim().replace(/[\s-]/g, '');

            // Validate phone
            if (!phone) {
                phoneError.textContent = 'Please enter your phone number';
                phoneError.style.display = 'block';
                phoneInput.classList.add('error');
                return;
            }

            if (!isValidGhanaPhone(phone)) {
                phoneError.textContent = 'Please enter a valid Ghana phone number (e.g., 0241234567)';
                phoneError.style.display = 'block';
                phoneInput.classList.add('error');
                return;
            }

            // Clear errors
            phoneError.style.display = 'none';
            phoneInput.classList.remove('error');
            phoneInput.classList.add('success');
            currentPhone = phone;

            // Show loading state
            lookupBtn.disabled = true;
            lookupBtnText.textContent = 'Checking...';
            lookupSpinner.style.display = 'block';

            try {
                const response = await fetch('/api/cug-admissions/lookup', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ phone: phone }),
                });

                const data = await response.json();

                if (data.found) {
                    lookupData = data.data;
                    showStep2WithData(data.data);
                } else {
                    lookupData = null;
                    showStep2Manual(data.message || 'Phone number not found. Please fill in your details manually.');
                }
            } catch (error) {
                console.error('Lookup error:', error);
                lookupData = null;
                showStep2Manual('Unable to connect to the system. Please fill in your details manually.');
            } finally {
                lookupBtn.disabled = false;
                lookupBtnText.textContent = 'Check My Number';
                lookupSpinner.style.display = 'none';
            }
        }

        // Show step 2 with auto-filled data
        function showStep2WithData(data) {
            // Set the phone number
            document.getElementById('phone').value = currentPhone;
            
            // Auto-fill the form
            document.getElementById('first_name').value = data.first_name || '';
            document.getElementById('last_name').value = data.last_name || '';
            document.getElementById('email').value = data.email || '';
            document.getElementById('student_id').value = data.student_id || '';

            // Show the result card
            const resultCard = document.getElementById('lookupResult');
            resultCard.style.display = 'block';
            resultCard.classList.remove('not-found');
            
            document.getElementById('resultName').textContent = `${data.first_name} ${data.last_name}`;
            document.getElementById('resultSubtitle').textContent = 'Your details have been auto-filled from CUG system';
            
            let resultDataHtml = '';
            if (data.student_id) resultDataHtml += `<span>Student ID:</span> <strong>${data.student_id}</strong>`;
            if (data.programme) resultDataHtml += `<span>Programme:</span> <strong>${data.programme}</strong>`;
            if (data.level) resultDataHtml += `<span>Level:</span> <strong>${data.level}</strong>`;
            document.getElementById('resultData').innerHTML = resultDataHtml;

            // Mark fields as success
            ['first_name', 'last_name', 'email', 'student_id'].forEach(id => {
                const el = document.getElementById(id);
                if (el.value) el.classList.add('success');
            });

            goToStep(2);
        }

        // Show step 2 for manual entry
        function showStep2Manual(message) {
            // Set the phone number
            document.getElementById('phone').value = currentPhone;

            // Clear the form fields for manual entry
            document.getElementById('first_name').value = '';
            document.getElementById('last_name').value = '';
            document.getElementById('email').value = '';
            document.getElementById('student_id').value = '';
            
            // Remove success styling from fields
            ['first_name', 'last_name', 'email', 'student_id'].forEach(id => {
                document.getElementById(id).classList.remove('success', 'error');
            });

            // Show the result card with not-found styling
            const resultCard = document.getElementById('lookupResult');
            resultCard.style.display = 'block';
            resultCard.classList.add('not-found');
            
            resultCard.querySelector('.lookup-result-icon').innerHTML = '<i class="fas fa-info-circle"></i>';
            document.getElementById('resultName').textContent = 'Phone Not in CUG System';
            document.getElementById('resultSubtitle').textContent = message;
            document.getElementById('resultData').innerHTML = `<span>Phone:</span> <strong>${formatPhone(currentPhone)}</strong>`;

            goToStep(2);
        }

        // Navigate between steps
        function goToStep(step) {
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const stepDot1 = document.getElementById('stepDot1');
            const stepDot2 = document.getElementById('stepDot2');

            if (step === 1) {
                step1.classList.add('active');
                step2.classList.remove('active');
                stepDot1.classList.add('active');
                stepDot1.classList.remove('completed');
                stepDot2.classList.remove('active');
            } else {
                step1.classList.remove('active');
                step2.classList.add('active');
                stepDot1.classList.remove('active');
                stepDot1.classList.add('completed');
                stepDot2.classList.add('active');
            }
        }

        // Password strength indicator
        const passwordInput = document.getElementById('password');
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/\d/)) strength++;
            if (password.match(/[^a-zA-Z\d]/)) strength++;

            strengthFill.className = 'strength-fill';
            if (password.length === 0) {
                strengthFill.style.width = '0%';
                strengthText.textContent = 'Minimum 8 characters';
                strengthText.style.color = 'var(--gray-500)';
            } else if (strength <= 2) {
                strengthFill.classList.add('strength-weak');
                strengthText.textContent = 'Weak password';
                strengthText.style.color = 'var(--danger)';
            } else if (strength <= 3) {
                strengthFill.classList.add('strength-medium');
                strengthText.textContent = 'Medium password';
                strengthText.style.color = 'var(--accent-dark)';
            } else {
                strengthFill.classList.add('strength-strong');
                strengthText.textContent = 'Strong password';
                strengthText.style.color = 'var(--success)';
            }
        });

        // Password confirmation
        const confirmPassword = document.getElementById('password_confirmation');
        const passwordMatchText = document.getElementById('passwordMatchText');

        confirmPassword.addEventListener('input', function() {
            const password = passwordInput.value;
            const confirm = this.value;
            
            if (confirm === '') {
                this.classList.remove('error', 'success');
                passwordMatchText.textContent = '';
            } else if (password !== confirm) {
                this.classList.add('error');
                this.classList.remove('success');
                passwordMatchText.textContent = '❌ Passwords do not match';
                passwordMatchText.style.color = 'var(--danger)';
            } else {
                this.classList.add('success');
                this.classList.remove('error');
                passwordMatchText.textContent = '✅ Passwords match';
                passwordMatchText.style.color = 'var(--success)';
            }
        });

        // Form submission
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const button = document.getElementById('registerButton');
            const buttonText = document.getElementById('buttonText');
            const spinner = document.getElementById('spinner');
            const terms = document.getElementById('terms');
            
            if (!terms.checked) {
                e.preventDefault();
                alert('Please accept the Terms of Service and Privacy Policy to continue.');
                terms.focus();
                return;
            }
            
            button.disabled = true;
            buttonText.textContent = 'Creating Account...';
            spinner.style.display = 'block';
        });

        // Phone input formatting
        document.getElementById('phone_lookup').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').substring(0, 10);
            this.classList.remove('error', 'success');
            document.getElementById('phoneError').style.display = 'none';
        });

        // Enter key support for phone lookup
        document.getElementById('phone_lookup').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                lookupPhone();
            }
        });
    </script>
</body>
</html>
