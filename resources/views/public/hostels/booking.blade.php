<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room - {{ $hostel->name }} - Priority Accommodations</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #006b3f;
            --primary-dark: #005530;
            --primary-light: #e8f5f0;
            --secondary: #ce1126;
            --accent: #fcd116;
            --dark: #1a202c;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --radius: 0.75rem;
            --radius-lg: 1rem;
            --shadow: 0 1px 3px rgba(0,0,0,0.12);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--gray-50);
            color: var(--dark);
            line-height: 1.6;
        }

        .nav {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            padding: 0.75rem 1.5rem;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary);
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-links a {
            color: var(--gray-600);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .nav-links a:hover { color: var(--primary); }

        .nav-links .highlight {
            color: var(--primary);
            font-weight: 600;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            border-radius: var(--radius);
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            border: none;
        }

        .btn-primary { background: var(--primary); color: var(--accent) !important; }
        .btn-primary:hover { background: var(--primary-dark); color: white !important; }
        .btn-outline { background: transparent; border: 2px solid var(--gray-200); color: var(--gray-700); }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
        .btn-lg { padding: 1rem 2rem; font-size: 1rem; }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
        }

        .booking-form {
            background: white;
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow);
        }

        .form-header {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .form-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: var(--gray-500);
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .form-section-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-section-title i { color: var(--primary); }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--gray-700);
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius);
            font-family: inherit;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
        }

        .form-hint {
            font-size: 0.8rem;
            color: var(--gray-500);
            margin-top: 0.35rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-top: 0.2rem;
        }

        .checkbox-group label {
            font-weight: 400;
            color: var(--gray-600);
        }

        .checkbox-group a {
            color: var(--primary);
        }

        /* Order Summary */
        .order-summary {
            position: sticky;
            top: 1rem;
        }

        .summary-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-lg);
            margin-bottom: 1rem;
        }

        .summary-header {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .summary-image {
            width: 100px;
            height: 80px;
            border-radius: var(--radius);
            overflow: hidden;
        }

        .summary-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .summary-info h3 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .summary-info p {
            color: var(--gray-500);
            font-size: 0.875rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--gray-100);
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-row.total {
            font-weight: 700;
            font-size: 1.1rem;
            border-top: 2px solid var(--gray-200);
            margin-top: 0.5rem;
            padding-top: 1rem;
        }

        .summary-row .label {
            color: var(--gray-600);
        }

        .summary-row .value {
            font-weight: 600;
        }

        .payment-info {
            background: var(--primary-light);
            border-radius: var(--radius);
            padding: 1rem;
            margin-top: 1rem;
        }

        .payment-info h4 {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .payment-info p {
            font-size: 0.8rem;
            color: var(--gray-600);
        }

        .secure-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: var(--gray-500);
            font-size: 0.8rem;
            margin-top: 1rem;
        }

        .secure-badge i { color: var(--primary); }

        .alert {
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
        }

        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        .alert-info {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        @media (max-width: 1024px) {
            .container {
                grid-template-columns: 1fr;
            }

            .order-summary {
                position: static;
            }
        }
    </style>
</head>
<body>
    <nav class="nav">
        <div class="nav-container">
            <a href="{{ url('/') }}" class="nav-brand">
                <i class="fas fa-building"></i>
                Priority Accommodations
            </a>
            
            <div class="nav-links">
                <a href="{{ route('public.hostels.browse') }}" class="highlight">
                    <i class="fas fa-search"></i> Browse Hostels
                </a>
                <a href="{{ url('/') }}#how-it-works">How It Works</a>
                
                <a href="{{ route('public.hostels.show', $hostel) }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back to Hostel
                </a>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-th-large"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container">
        <form action="{{ route('public.hostels.booking.create', [$hostel, $room]) }}" method="POST" class="booking-form">
            @csrf
            
            <div class="form-header">
                <h1>Complete Your Booking</h1>
                <p>You're booking a room at {{ $hostel->name }}</p>
            </div>

            @if(session('error'))
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                </div>
            @endif

            <div class="form-section">
                <h2 class="form-section-title"><i class="fas fa-user"></i> Your Information</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" value="{{ $user->name }}" readonly style="background: var(--gray-50);">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" value="{{ $user->email }}" readonly style="background: var(--gray-50);">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" value="{{ $user->phone }}" readonly style="background: var(--gray-50);">
                    </div>
                    <div class="form-group">
                        <label>Student ID</label>
                        <input type="text" value="{{ $user->student_id_number ?? 'N/A' }}" readonly style="background: var(--gray-50);">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h2 class="form-section-title"><i class="fas fa-calendar-alt"></i> Booking Details</h2>
                
                @if($room->capacity > 1 && $room->allow_partial_booking)
                    <div class="form-group">
                        <label>Number of Beds</label>
                        <select name="beds_requested" required>
                            @for($i = 1; $i <= $room->beds_available; $i++)
                                <option value="{{ $i }}">{{ $i }} bed(s) - ₵{{ number_format(($pricing['total_price'] / $room->capacity) * $i) }}</option>
                            @endfor
                        </select>
                        <div class="form-hint">This is a shared room with {{ $room->capacity }} beds total.</div>
                    </div>
                @else
                    <input type="hidden" name="beds_requested" value="1">
                @endif

                <div class="form-row">
                    <div class="form-group">
                        <label>Academic Year</label>
                        <select name="academic_year" required>
                            @php $currentYear = date('Y'); @endphp
                            <option value="{{ $currentYear }}/{{ $currentYear + 1 }}">{{ $currentYear }}/{{ $currentYear + 1 }}</option>
                            <option value="{{ $currentYear + 1 }}/{{ $currentYear + 2 }}">{{ $currentYear + 1 }}/{{ $currentYear + 2 }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Semester</label>
                        <select name="semester" required>
                            <option value="First Semester">First Semester</option>
                            <option value="Second Semester">Second Semester</option>
                            <option value="Full Year">Full Year (Both Semesters)</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Check-in Date</label>
                        <input type="date" name="check_in_date" required min="{{ date('Y-m-d') }}" value="{{ old('check_in_date') }}">
                    </div>
                    <div class="form-group">
                        <label>Check-out Date</label>
                        <input type="date" name="check_out_date" required value="{{ old('check_out_date') }}">
                        <div class="form-hint">End of semester date</div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h2 class="form-section-title"><i class="fas fa-shield-alt"></i> Terms & Conditions</h2>
                
                <div class="alert alert-info">
                    <strong>Important:</strong> After payment, the hostel owner will have {{ $settings->confirmation_timeout_minutes }} minutes to confirm your booking. If the room is unavailable, you will receive a full refund.
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" name="terms_accepted" id="terms" required>
                    <label for="terms">
                        I agree to the <a href="{{ route('terms') }}" target="_blank">Terms of Service</a>, 
                        <a href="{{ route('privacy') }}" target="_blank">Privacy Policy</a>, and the hostel rules.
                        I understand that my booking is subject to confirmation.
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center;">
                <i class="fas fa-lock"></i> Proceed to Payment
            </button>
        </form>

        <div class="order-summary">
            <div class="summary-card">
                <div class="summary-header">
                    <div class="summary-image">
                        @if($room->photos && count($room->photos) > 0)
                            <img src="{{ asset('storage/' . $room->photos[0]) }}" alt="Room">
                        @else
                            <img src="{{ $hostel->cover_image_url }}" alt="Room">
                        @endif
                    </div>
                    <div class="summary-info">
                        <h3>Room {{ $room->room_number }}</h3>
                        <p>{{ $hostel->name }}</p>
                        <p><i class="fas fa-users"></i> {{ $room->capacity }} person(s)</p>
                    </div>
                </div>

                <div class="summary-row">
                    <span class="label">Base Price</span>
                    <span class="value">₵{{ number_format($pricing['base_price']) }}</span>
                </div>
                <div class="summary-row">
                    <span class="label">Service Fee</span>
                    <span class="value">₵{{ number_format($pricing['commission_amount']) }}</span>
                </div>
                <div class="summary-row total">
                    <span class="label">Total</span>
                    <span class="value">₵{{ number_format($pricing['total_price']) }}</span>
                </div>

                <div class="payment-info">
                    <h4><i class="fas fa-info-circle"></i> Payment Info</h4>
                    <p>You'll be redirected to a secure payment page. We accept Mobile Money (MTN, Vodafone, AirtelTigo) and Cards.</p>
                </div>
            </div>

            <div class="secure-badge">
                <i class="fas fa-lock"></i>
                <span>Secure checkout powered by {{ $settings->hubtel_enabled ? 'Hubtel' : 'Paystack' }}</span>
            </div>
        </div>
    </div>
</body>
</html>

