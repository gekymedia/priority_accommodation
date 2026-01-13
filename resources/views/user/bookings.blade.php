<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Priority Accommodations</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #006b3f;
            --primary-dark: #005530;
            --primary-light: #e8f5f0;
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
            --shadow: 0 1px 3px rgba(0,0,0,0.12);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--gray-50);
            color: var(--dark);
            min-height: 100vh;
        }

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

        .nav-user { display: flex; align-items: center; gap: 1rem; }

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

        .btn-primary { background: var(--primary); color: var(--accent); }
        .btn-primary:hover { background: var(--primary-dark); color: white; }
        .btn-outline { background: transparent; border: 2px solid var(--gray-200); color: var(--gray-600); }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark);
        }

        .card {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }

        .card-body { padding: 1.5rem; }

        .booking-list { display: flex; flex-direction: column; gap: 1rem; }

        .booking-card {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            padding: 1.5rem;
            display: flex;
            gap: 1.5rem;
            transition: all 0.2s;
        }

        .booking-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow);
        }

        .booking-icon {
            width: 4rem;
            height: 4rem;
            background: var(--primary-light);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .booking-content { flex: 1; }

        .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }

        .booking-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--dark);
        }

        .booking-subtitle {
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-confirmed { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .status-pending { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .status-cancelled { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
        .status-checked_in { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
        .status-checked_out { background: var(--gray-100); color: var(--gray-600); }

        .booking-meta {
            display: flex;
            gap: 2rem;
            font-size: 0.875rem;
            color: var(--gray-600);
        }

        .booking-meta span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--gray-500);
        }

        .empty-state i {
            font-size: 4rem;
            opacity: 0.3;
            margin-bottom: 1rem;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        @media (max-width: 768px) {
            .booking-card { flex-direction: column; }
            .booking-meta { flex-direction: column; gap: 0.5rem; }
            .nav-links { display: none; }
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
                <a href="{{ route('user.dashboard') }}">Dashboard</a>
                <a href="{{ route('public.hostels.browse') }}">Find Accommodation</a>
                <a href="{{ route('user.bookings') }}" class="active">My Bookings</a>
                <a href="{{ route('complaints.my-complaints') }}">My Complaints</a>
            </div>

            <div class="nav-user">
                <a href="{{ route('user.profile') }}" class="btn btn-outline">
                    <i class="fas fa-user"></i> Profile
                </a>
            </div>
        </div>
    </nav>

    <div class="main-container">
        <div class="page-header">
            <h1 class="page-title">My Bookings</h1>
            <a href="{{ route('public.hostels.browse') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Booking
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                @if($bookings->count() > 0)
                <div class="booking-list">
                    @foreach($bookings as $booking)
                    <div class="booking-card">
                        <div class="booking-icon">
                            <i class="fas fa-bed"></i>
                        </div>
                        <div class="booking-content">
                            <div class="booking-header">
                                <div>
                                    <div class="booking-title">Room {{ $booking->room->room_number ?? 'N/A' }}</div>
                                    <div class="booking-subtitle">{{ $booking->room->hostel->name ?? 'Unknown Hostel' }}</div>
                                </div>
                                <span class="status-badge status-{{ $booking->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </div>
                            <div class="booking-meta">
                                <span>
                                    <i class="fas fa-calendar"></i>
                                    {{ $booking->check_in ? $booking->check_in->format('M d, Y') : 'N/A' }}
                                </span>
                                <span>
                                    <i class="fas fa-clock"></i>
                                    Booked {{ $booking->created_at->diffForHumans() }}
                                </span>
                                @if($booking->total_amount)
                                <span>
                                    <i class="fas fa-money-bill"></i>
                                    ₵{{ number_format($booking->total_amount, 2) }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="pagination">
                    {{ $bookings->links() }}
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h3>No Bookings Yet</h3>
                    <p>You haven't made any bookings yet. Start exploring hostels!</p>
                    <a href="{{ route('public.hostels.browse') }}" class="btn btn-primary" style="margin-top: 1rem;">
                        <i class="fas fa-search"></i> Find Accommodation
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>

