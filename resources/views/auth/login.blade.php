<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Priority Accommodations</title>
    
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

        /* Decorative Ghana Flag Stripe */
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

        /* Floating Star Decorations */
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
        .floating-star:nth-child(3) { top: 50%; right: 5%; animation-delay: 4s; font-size: 80px; }

        @keyframes float-star {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
        }

        /* Decorative Circles */
        .deco-circle {
            position: fixed;
            border-radius: 50%;
            opacity: 0.1;
            z-index: 0;
        }

        .deco-circle-1 {
            width: 300px;
            height: 300px;
            background: var(--accent);
            top: -100px;
            right: -100px;
        }

        .deco-circle-2 {
            width: 200px;
            height: 200px;
            background: var(--secondary);
            bottom: -50px;
            left: -50px;
        }

        .login-container {
            width: 100%;
            max-width: 460px;
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

        .login-card {
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        /* Subtle Pattern Overlay */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23006b3f' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
            z-index: 0;
        }

        .login-content {
            position: relative;
            z-index: 1;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-logo {
            width: 5rem;
            height: 5rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
            box-shadow: var(--shadow-lg), 0 0 20px rgba(0, 107, 63, 0.3);
            position: relative;
            animation: float 3s ease-in-out infinite;
        }

        .login-logo::after {
            content: '★';
            position: absolute;
            top: -8px;
            right: -8px;
            color: var(--accent);
            font-size: 1.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .login-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .login-title-highlight {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-subtitle {
            color: var(--gray-600);
            font-size: 0.95rem;
            font-weight: 400;
        }

        /* Ghana Badge */
        .ghana-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--primary-light), #d1fae5);
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 1rem;
            border: 1px solid rgba(0, 107, 63, 0.2);
        }

        .ghana-badge i {
            color: var(--accent);
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
            background: white;
            box-shadow: 0 0 0 4px rgba(0, 107, 63, 0.1);
        }

        .form-control::placeholder {
            color: var(--gray-500);
        }

        .input-icon-wrapper {
            position: relative;
        }

        .input-icon-wrapper .form-control {
            padding-left: 2.75rem;
        }

        .input-icon-wrapper .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-500);
            transition: color 0.3s ease;
        }

        .input-icon-wrapper:focus-within .input-icon {
            color: var(--primary);
        }

        .form-hint {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            font-size: 0.8rem;
            color: var(--gray-600);
        }

        .form-hint i {
            color: var(--accent);
        }

        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 1rem 1.5rem;
            border: none;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            width: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0 4px 14px rgba(0, 107, 63, 0.35);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 107, 63, 0.45);
        }

        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
        }

        .spinner {
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--gray-600);
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            width: 1.1rem;
            height: 1.1rem;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .forgot-password {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .forgot-password:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Sign Up Section */
        .signup-section {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--gray-200);
        }

        .signup-text {
            color: var(--gray-600);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .btn-signup {
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: var(--dark);
            padding: 0.875rem 2rem;
            width: auto;
            display: inline-flex;
            box-shadow: 0 4px 14px rgba(252, 209, 22, 0.35);
        }

        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(252, 209, 22, 0.45);
        }

        .login-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--gray-200);
        }

        .footer-text {
            color: var(--gray-500);
            font-size: 0.75rem;
        }

        .back-home {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .back-home:hover {
            color: var(--primary-dark);
            gap: 0.75rem;
        }

        /* Alert Styles */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            border-left: 4px solid;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .alert i {
            margin-top: 0.1rem;
        }

        .alert-danger {
            background: rgba(206, 17, 38, 0.08);
            color: var(--danger);
            border-left-color: var(--danger);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.08);
            color: var(--success);
            border-left-color: var(--success);
        }

        /* Animation */
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Quick Features */
        .quick-features {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .quick-feature {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.75rem;
            color: var(--gray-600);
        }

        .quick-feature i {
            color: var(--primary);
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .login-card {
                padding: 2rem 1.5rem;
            }
            
            .login-title {
                font-size: 1.5rem;
            }
            
            .form-options {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .floating-star {
                font-size: 60px;
            }
        }
    </style>
</head>
<body>
    <!-- Floating Decorations -->
    <i class="fas fa-star floating-star"></i>
    <i class="fas fa-star floating-star"></i>
    <i class="fas fa-star floating-star"></i>
    <div class="deco-circle deco-circle-1"></div>
    <div class="deco-circle deco-circle-2"></div>

    <div class="login-container fade-in">
        <div class="glass-container login-card">
            <div class="login-content">
                <!-- Back to Home -->
                <a href="{{ url('/') }}" class="back-home">
                    <i class="fas fa-arrow-left"></i>
                    Back to Home
                </a>

                <div class="login-header">
                    <span class="ghana-badge">
                        <i class="fas fa-star"></i>
                        Made for Ghana
                    </span>
                    <div class="login-logo">
                        <i class="fas fa-building"></i>
                    </div>
                    <h1 class="login-title">
                        Welcome to <span class="login-title-highlight">Priority</span>
                    </h1>
                    <p class="login-subtitle">Sign in to find your perfect accommodation</p>
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(session('status'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    
                    <div class="form-group">
                        <label for="login" class="form-label">Email or Phone Number</label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input
                                type="text"
                                id="login"
                                name="login"
                                class="form-control"
                                placeholder="e.g. 0241234567 or email@example.com"
                                value="{{ old('login') }}"
                                required
                                autofocus
                            >
                        </div>
                        <div class="form-hint">
                            <i class="fas fa-info-circle"></i>
                            <span>Use your Ghana phone number or registered email</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="Enter your password"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            <span>Remember me</span>
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-password">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    
                    <button type="submit" class="btn btn-primary" id="loginButton">
                        <i class="fas fa-sign-in-alt"></i>
                        <span id="buttonText">Sign In</span>
                        <div class="spinner" id="spinner" style="display: none;"></div>
                    </button>

                    <div class="quick-features">
                        <span class="quick-feature">
                            <i class="fas fa-shield-alt"></i>
                            Secure Login
                        </span>
                        <span class="quick-feature">
                            <i class="fas fa-bolt"></i>
                            Fast Access
                        </span>
                        <span class="quick-feature">
                            <i class="fas fa-mobile-alt"></i>
                            Mobile Friendly
                        </span>
                    </div>
                </form>
                
                <!-- Sign Up Section -->
                @if (Route::has('register'))
                    <div class="signup-section">
                        <p class="signup-text">Don't have an account yet?</p>
                        <a href="{{ route('register') }}" class="btn btn-signup">
                            <i class="fas fa-user-plus"></i>
                            Create New Account
                        </a>
                    </div>
                @endif
                
                <div class="login-footer">
                    <p class="footer-text">
                        © {{ date('Y') }} Priority Accommodations. Made with <span style="color: var(--secondary);">❤</span> in Ghana
                    </p>
                    <p class="footer-text" style="margin-top: 0.5rem;">
                        <a href="{{ route('terms') }}" style="color: var(--gray-500); text-decoration: none;">Terms</a> · 
                        <a href="{{ route('privacy') }}" style="color: var(--gray-500); text-decoration: none;">Privacy</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const button = document.getElementById('loginButton');
            const buttonText = document.getElementById('buttonText');
            const spinner = document.getElementById('spinner');
            const icon = button.querySelector('.fa-sign-in-alt');
            
            button.disabled = true;
            buttonText.textContent = 'Signing in...';
            spinner.style.display = 'block';
            if (icon) icon.style.display = 'none';
        });

        // Add subtle animation to form elements on focus
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.closest('.form-group').style.transform = 'translateY(-2px)';
                this.closest('.form-group').style.transition = 'transform 0.3s ease';
            });
            
            input.addEventListener('blur', function() {
                this.closest('.form-group').style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>
