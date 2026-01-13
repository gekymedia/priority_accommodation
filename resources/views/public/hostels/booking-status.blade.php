<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Status - Priority Accommodations</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #006b3f;
            --primary-dark: #005530;
            --primary-light: #e8f5f0;
            --secondary: #ce1126;
            --accent: #fcd116;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1a202c;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --radius: 0.75rem;
            --radius-lg: 1rem;
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--gray-50);
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .nav {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            padding: 0.75rem 1.5rem;
        }

        .nav-container {
            max-width: 800px;
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
            padding: 0.625rem 1.25rem;
            font-weight: 600;
            font-size: 0.875rem;
            border-radius: var(--radius);
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            border: none;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--accent) !important;
        }

        .btn-primary:hover { background: var(--primary-dark); color: white !important; }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 3rem 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .status-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 2.5rem;
            text-align: center;
            box-shadow: var(--shadow-lg);
        }

        /* Status Icons */
        .status-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
        }

        .status-icon.waiting {
            background: #fef3c7;
            color: var(--warning);
            animation: pulse 2s infinite;
        }

        .status-icon.confirmed {
            background: #d1fae5;
            color: var(--success);
        }

        .status-icon.rejected {
            background: #fee2e2;
            color: var(--danger);
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .status-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .status-message {
            color: var(--gray-600);
            margin-bottom: 2rem;
        }

        /* Countdown Timer */
        .countdown-section {
            margin-bottom: 2rem;
        }

        .countdown-label {
            font-size: 0.875rem;
            color: var(--gray-500);
            margin-bottom: 0.5rem;
        }

        .countdown-timer {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary);
            font-variant-numeric: tabular-nums;
        }

        .countdown-timer.urgent {
            color: var(--danger);
        }

        /* Progress Bar */
        .progress-bar {
            height: 8px;
            background: var(--gray-200);
            border-radius: 4px;
            overflow: hidden;
            margin: 1.5rem 0;
        }

        .progress-fill {
            height: 100%;
            background: var(--primary);
            border-radius: 4px;
            transition: width 1s linear;
        }

        .progress-fill.urgent {
            background: var(--danger);
        }

        /* Booking Details */
        .booking-details {
            background: var(--gray-50);
            border-radius: var(--radius);
            padding: 1.5rem;
            margin: 1.5rem 0;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--gray-200);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: var(--gray-500);
        }

        .detail-value {
            font-weight: 600;
        }

        /* Steps */
        .steps {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin: 2rem 0;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        .step.active {
            color: var(--primary);
            font-weight: 600;
        }

        .step.completed {
            color: var(--success);
        }

        .step-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gray-200);
            font-size: 0.75rem;
        }

        .step.active .step-icon {
            background: var(--primary);
            color: white;
        }

        .step.completed .step-icon {
            background: var(--success);
            color: white;
        }

        .step-connector {
            width: 30px;
            height: 2px;
            background: var(--gray-200);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem 1.5rem;
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

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1.5rem;
        }

        /* Info Box */
        .info-box {
            background: var(--primary-light);
            border-radius: var(--radius);
            padding: 1rem;
            margin-top: 1.5rem;
            font-size: 0.875rem;
            color: var(--gray-600);
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            text-align: left;
        }

        .info-box i {
            color: var(--primary);
            margin-top: 0.2rem;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .status-card {
            animation: fadeIn 0.5s ease-out;
        }

        /* Confetti for confirmed status */
        .confetti {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: 100;
        }

        .confetti-piece {
            position: absolute;
            width: 10px;
            height: 10px;
            background: var(--primary);
            animation: confetti-fall 3s ease-out forwards;
        }

        @keyframes confetti-fall {
            0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
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
        <div class="status-card" id="statusCard">
            @if($request->isAwaitingConfirmation())
                <!-- Waiting for Confirmation -->
                <div class="status-icon waiting">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <h1 class="status-title">Awaiting Confirmation</h1>
                <p class="status-message">
                    Your payment was successful! The hostel is being notified to confirm your room.
                </p>

                <div class="countdown-section">
                    <div class="countdown-label">Time remaining for confirmation</div>
                    <div class="countdown-timer" id="countdown">
                        {{ gmdate('i:s', max(0, $request->getConfirmationRemainingSeconds())) }}
                    </div>
                </div>

                <div class="progress-bar">
                    <div class="progress-fill" id="progressBar" style="width: {{ ($request->getConfirmationRemainingSeconds() / 180) * 100 }}%"></div>
                </div>

                <div class="steps">
                    <div class="step completed">
                        <span class="step-icon"><i class="fas fa-check"></i></span>
                        Payment
                    </div>
                    <span class="step-connector"></span>
                    <div class="step active">
                        <span class="step-icon">2</span>
                        Confirmation
                    </div>
                    <span class="step-connector"></span>
                    <div class="step">
                        <span class="step-icon">3</span>
                        Complete
                    </div>
                </div>

                <div class="booking-details">
                    <div class="detail-row">
                        <span class="detail-label">Hostel</span>
                        <span class="detail-value">{{ $request->hostel->name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Room</span>
                        <span class="detail-value">Room {{ $request->room->room_number }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Amount Paid</span>
                        <span class="detail-value">₵{{ number_format($request->total_amount, 2) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Reference</span>
                        <span class="detail-value">{{ $request->payment_reference }}</span>
                    </div>
                </div>

                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <span>
                        The hostel manager is being notified about your booking. If they don't respond within the time limit, 
                        your payment will be refunded automatically.
                    </span>
                </div>

            @elseif($request->isConfirmed())
                <!-- Booking Confirmed -->
                <div class="confetti" id="confetti"></div>
                <div class="status-icon confirmed">
                    <i class="fas fa-check"></i>
                </div>
                <h1 class="status-title">Booking Confirmed! 🎉</h1>
                <p class="status-message">
                    Congratulations! Your room has been reserved successfully.
                </p>

                <div class="steps">
                    <div class="step completed">
                        <span class="step-icon"><i class="fas fa-check"></i></span>
                        Payment
                    </div>
                    <span class="step-connector" style="background: var(--success);"></span>
                    <div class="step completed">
                        <span class="step-icon"><i class="fas fa-check"></i></span>
                        Confirmed
                    </div>
                    <span class="step-connector" style="background: var(--success);"></span>
                    <div class="step completed">
                        <span class="step-icon"><i class="fas fa-check"></i></span>
                        Complete
                    </div>
                </div>

                <div class="booking-details">
                    <div class="detail-row">
                        <span class="detail-label">Hostel</span>
                        <span class="detail-value">{{ $request->hostel->name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Room</span>
                        <span class="detail-value">Room {{ $request->room->room_number }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Check-in Date</span>
                        <span class="detail-value">{{ $request->check_in_date->format('M d, Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Reference</span>
                        <span class="detail-value">{{ $request->payment_reference }}</span>
                    </div>
                </div>

                <div class="actions">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-th-large"></i> Go to Dashboard
                    </a>
                    <a href="{{ route('public.hostels.browse') }}" class="btn btn-outline">
                        Browse More Hostels
                    </a>
                </div>

            @elseif($request->isRejected() || $request->isTimedOut())
                <!-- Booking Rejected/Timed Out -->
                <div class="status-icon rejected">
                    <i class="fas fa-times"></i>
                </div>
                <h1 class="status-title">Room Not Available</h1>
                <p class="status-message">
                    @if($request->isTimedOut())
                        Unfortunately, the hostel did not respond in time.
                    @else
                        Unfortunately, this room is no longer available. {{ $request->rejection_reason }}
                    @endif
                </p>

                <div class="booking-details">
                    <div class="detail-row">
                        <span class="detail-label">Hostel</span>
                        <span class="detail-value">{{ $request->hostel->name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Room</span>
                        <span class="detail-value">Room {{ $request->room->room_number }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Amount</span>
                        <span class="detail-value">₵{{ number_format($request->total_amount, 2) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Refund Status</span>
                        <span class="detail-value" style="color: var(--warning);">Processing</span>
                    </div>
                </div>

                <div class="info-box" style="background: #fef3c7;">
                    <i class="fas fa-undo" style="color: var(--warning);"></i>
                    <span>
                        Your payment of ₵{{ number_format($request->total_amount, 2) }} will be refunded within 24-48 hours 
                        to your original payment method.
                    </span>
                </div>

                <div class="actions">
                    <a href="{{ route('public.hostels.browse') }}" class="btn btn-primary">
                        <i class="fas fa-search"></i> Find Another Room
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline">
                        Go to Dashboard
                    </a>
                </div>
            @endif
        </div>
    </div>

    @if($request->isAwaitingConfirmation())
    <script>
        const deadline = new Date('{{ $request->confirmation_deadline->toIso8601String() }}').getTime();
        const totalTime = 180; // 3 minutes in seconds
        
        function updateCountdown() {
            const now = Date.now();
            const remaining = Math.max(0, Math.floor((deadline - now) / 1000));
            
            const minutes = Math.floor(remaining / 60);
            const seconds = remaining % 60;
            
            const countdownEl = document.getElementById('countdown');
            const progressBar = document.getElementById('progressBar');
            
            countdownEl.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            progressBar.style.width = `${(remaining / totalTime) * 100}%`;
            
            if (remaining <= 30) {
                countdownEl.classList.add('urgent');
                progressBar.classList.add('urgent');
            }
            
            if (remaining <= 0) {
                // Refresh page to get updated status
                location.reload();
            }
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
        
        // Poll for status updates every 5 seconds
        setInterval(() => {
            fetch(location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.text())
                .then(html => {
                    if (html.includes('Booking Confirmed') || html.includes('Room Not Available')) {
                        location.reload();
                    }
                });
        }, 5000);
    </script>
    @endif

    @if($request->isConfirmed())
    <script>
        // Simple confetti effect
        const confettiContainer = document.getElementById('confetti');
        const colors = ['#006b3f', '#fcd116', '#ce1126', '#10b981'];
        
        for (let i = 0; i < 50; i++) {
            const piece = document.createElement('div');
            piece.className = 'confetti-piece';
            piece.style.left = Math.random() * 100 + '%';
            piece.style.background = colors[Math.floor(Math.random() * colors.length)];
            piece.style.animationDelay = Math.random() * 2 + 's';
            confettiContainer.appendChild(piece);
        }
    </script>
    @endif
</body>
</html>

