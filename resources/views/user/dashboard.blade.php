<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - Priority Accommodations</title>
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
            --gray-300: #d1d5db;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --radius: 0.75rem;
            --shadow: 0 1px 3px rgba(0,0,0,0.12);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--gray-50);
            color: var(--dark);
            min-height: 100vh;
        }

        /* Navigation */
        .nav {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0.75rem 1.5rem;
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
            transition: color 0.2s;
        }

        .nav-links a:hover, .nav-links a.active { color: var(--primary); }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 2.5rem;
            height: 2.5rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
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
            color: var(--accent);
        }

        .btn-primary:hover { 
            background: var(--primary-dark);
            color: white;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--gray-200);
            color: var(--gray-700);
        }

        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }

        /* Main Container */
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        /* Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: var(--radius);
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary) 33.33%, var(--accent) 33.33%, var(--accent) 66.66%, var(--primary-light) 66.66%);
        }

        .welcome-content h1 {
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }

        .welcome-content p {
            opacity: 0.9;
            margin-bottom: 1.5rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
        }

        .stat-card .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }

        .stat-card .stat-icon.green { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .stat-card .stat-icon.blue { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
        .stat-card .stat-icon.orange { background: rgba(245, 158, 11, 0.1); color: var(--warning); }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
        }

        .stat-card .stat-label {
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--dark);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Current Booking */
        .current-booking {
            background: linear-gradient(135deg, var(--primary-light), white);
            border: 2px solid var(--primary);
        }

        .booking-info {
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
        }

        .booking-icon {
            width: 4rem;
            height: 4rem;
            background: var(--primary);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .booking-details h3 {
            font-size: 1.25rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .booking-details p {
            color: var(--gray-600);
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .booking-status {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--success);
            color: white;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-top: 0.75rem;
        }

        /* Booking List */
        .booking-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .booking-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--gray-50);
            border-radius: var(--radius);
            transition: all 0.2s;
        }

        .booking-item:hover {
            background: var(--gray-100);
        }

        .booking-item-icon {
            width: 2.5rem;
            height: 2.5rem;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .booking-item-info {
            flex: 1;
        }

        .booking-item-title {
            font-weight: 600;
            color: var(--dark);
        }

        .booking-item-subtitle {
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        .booking-item-status {
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .booking-item-status.confirmed { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .booking-item-status.pending { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .booking-item-status.cancelled { background: rgba(239, 68, 68, 0.1); color: var(--danger); }

        /* Featured Hostels */
        .hostels-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .hostel-card {
            background: var(--gray-50);
            border-radius: var(--radius);
            padding: 1rem;
            transition: all 0.2s;
        }

        .hostel-card:hover {
            background: var(--gray-100);
            transform: translateY(-2px);
        }

        .hostel-card h4 {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .hostel-card p {
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        .hostel-card .price {
            color: var(--primary);
            font-weight: 700;
            margin-top: 0.5rem;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            gap: 0.75rem;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--gray-50);
            border-radius: var(--radius);
            text-decoration: none;
            color: var(--dark);
            transition: all 0.2s;
        }

        .action-btn:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .action-btn i {
            width: 2.5rem;
            height: 2.5rem;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1rem;
        }

        .action-btn span {
            font-weight: 500;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: var(--gray-500);
        }

        .empty-state i {
            font-size: 3rem;
            opacity: 0.5;
            margin-bottom: 1rem;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .hostels-grid { grid-template-columns: 1fr; }
            .booking-info { flex-direction: column; }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="nav">
        <div class="nav-container">
            <a href="{{ url('/') }}" class="nav-brand">
                <i class="fas fa-building"></i>
                Priority Accommodations
            </a>
            
            <div class="nav-links">
                <a href="{{ route('user.dashboard') }}" class="active">Dashboard</a>
                <a href="{{ route('public.hostels.browse') }}">Find Accommodation</a>
                <a href="{{ route('user.bookings') }}">My Bookings</a>
                <a href="{{ route('complaints.my-complaints') }}">My Complaints</a>
            </div>

            <div class="nav-user">
                <a href="{{ route('user.profile') }}" class="btn btn-outline">
                    <i class="fas fa-user"></i> Profile
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="main-container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-content">
                <h1>Welcome back, {{ explode(' ', $user->name)[0] }}! 👋</h1>
                <p>Manage your bookings and find your perfect accommodation near CUG campus.</p>
                <a href="{{ route('public.hostels.browse') }}" class="btn btn-primary">
                    <i class="fas fa-search"></i> Browse Hostels
                </a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-value">{{ $totalBookings }}</div>
                <div class="stat-label">Total Bookings</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">{{ $pendingBookings }}</div>
                <div class="stat-label">Pending Bookings</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-home"></i>
                </div>
                <div class="stat-value">{{ $currentBooking ? 'Yes' : 'No' }}</div>
                <div class="stat-label">Active Accommodation</div>
            </div>
        </div>

        <div class="content-grid">
            <!-- Main Content -->
            <div>
                <!-- Current Booking -->
                @if($currentBooking)
                <div class="card current-booking" style="margin-bottom: 1.5rem;">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-home text-green-600"></i> Current Accommodation
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="booking-info">
                            <div class="booking-icon">
                                <i class="fas fa-bed"></i>
                            </div>
                            <div class="booking-details">
                                <h3>Room {{ $currentBooking->room->room_number }}</h3>
                                <p><i class="fas fa-building"></i> {{ $currentBooking->room->hostel->name ?? 'Hostel' }}</p>
                                <p><i class="fas fa-calendar"></i> Since {{ $currentBooking->check_in->format('M d, Y') }}</p>
                                <div class="booking-status">
                                    <i class="fas fa-check-circle"></i>
                                    {{ ucfirst($currentBooking->status) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Recent Bookings -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Bookings</h3>
                        <a href="{{ route('user.bookings') }}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.8rem;">
                            View All
                        </a>
                    </div>
                    <div class="card-body">
                        @if($myBookings->count() > 0)
                        <div class="booking-list">
                            @foreach($myBookings as $booking)
                            <div class="booking-item">
                                <div class="booking-item-icon">
                                    <i class="fas fa-door-open"></i>
                                </div>
                                <div class="booking-item-info">
                                    <div class="booking-item-title">
                                        Room {{ $booking->room->room_number ?? 'N/A' }}
                                    </div>
                                    <div class="booking-item-subtitle">
                                        {{ $booking->room->hostel->name ?? 'Unknown Hostel' }} • {{ $booking->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                                <span class="booking-item-status {{ $booking->status }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <p>No bookings yet</p>
                            <a href="{{ route('public.hostels.browse') }}" class="btn btn-primary" style="margin-top: 1rem;">
                                Find Accommodation
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Quick Actions -->
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div class="card-header">
                        <h3 class="card-title">Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="quick-actions">
                            <a href="{{ route('public.hostels.browse') }}" class="action-btn">
                                <i class="fas fa-search"></i>
                                <span>Find Accommodation</span>
                            </a>
                            <a href="{{ route('user.bookings') }}" class="action-btn">
                                <i class="fas fa-list"></i>
                                <span>View My Bookings</span>
                            </a>
                            <a href="{{ route('complaints.create') }}" class="action-btn">
                                <i class="fas fa-comment-dots"></i>
                                <span>Submit Complaint</span>
                            </a>
                            <a href="{{ route('user.profile') }}" class="action-btn">
                                <i class="fas fa-user-edit"></i>
                                <span>Update Profile</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Featured Hostels -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Featured Hostels</h3>
                    </div>
                    <div class="card-body">
                        @if($featuredHostels->count() > 0)
                        <div class="hostels-grid">
                            @foreach($featuredHostels as $hostel)
                            <a href="{{ route('public.hostels.show', $hostel) }}" class="hostel-card">
                                <h4>{{ Str::limit($hostel->name, 20) }}</h4>
                                <p>{{ $hostel->rooms->count() }} rooms available</p>
                                @if($hostel->rooms->first())
                                <div class="price">From ₵{{ number_format($hostel->rooms->min('price_per_semester'), 0) }}</div>
                                @endif
                            </a>
                            @endforeach
                        </div>
                        @else
                        <div class="empty-state">
                            <i class="fas fa-building"></i>
                            <p>No hostels available</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

