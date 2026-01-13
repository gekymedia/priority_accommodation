<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Accommodation - Priority Accommodations</title>
    
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
            --gray-300: #d1d5db;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --radius: 0.75rem;
            --radius-lg: 1rem;
            --shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--gray-50);
            color: var(--dark);
            line-height: 1.6;
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

        .nav-brand img {
            height: 40px;
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

        .btn-primary:hover { 
            background: var(--primary-dark); 
            color: white !important;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 3rem 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary) 33.33%, var(--accent) 33.33%, var(--accent) 66.66%, var(--primary) 66.66%);
        }

        .hero-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .hero h1 {
            color: white;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .hero p {
            color: rgba(255,255,255,0.9);
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        /* Search & Filters */
        .search-box {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 1rem;
            box-shadow: var(--shadow-lg);
        }

        .search-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .search-group select,
        .search-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius);
            font-size: 1rem;
            font-family: inherit;
            transition: border-color 0.2s;
        }

        .search-group select:focus,
        .search-group input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .search-box .btn {
            align-self: end;
            padding: 0.875rem 2rem;
        }

        /* Content */
        .content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        .results-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .results-count {
            color: var(--gray-600);
        }

        .results-count strong {
            color: var(--dark);
        }

        .sort-select {
            padding: 0.5rem 1rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius);
            font-family: inherit;
            cursor: pointer;
        }

        /* Hostel Grid */
        .hostels-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 1.5rem;
        }

        .hostel-card {
            background: white;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s;
        }

        .hostel-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
        }

        .hostel-image {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .hostel-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .hostel-card:hover .hostel-image img {
            transform: scale(1.05);
        }

        .hostel-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: var(--primary);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .hostel-badge.girls { background: #ec4899; }
        .hostel-badge.boys { background: #3b82f6; }

        .hostel-distance {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 0.35rem 0.75rem;
            border-radius: var(--radius);
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .hostel-content {
            padding: 1.25rem;
        }

        .hostel-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .hostel-name a {
            color: inherit;
            text-decoration: none;
        }

        .hostel-name a:hover { color: var(--primary); }

        .hostel-address {
            color: var(--gray-500);
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            margin-bottom: 1rem;
        }

        .hostel-stats {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .stat {
            flex: 1;
            text-align: center;
        }

        .stat-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary);
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        .hostel-price {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
        }

        .price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
        }

        .price span {
            font-size: 0.875rem;
            font-weight: 400;
            color: var(--gray-500);
        }

        .hostel-amenities {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .amenity-tag {
            background: var(--gray-100);
            color: var(--gray-600);
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius);
            font-size: 0.75rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--gray-500);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .pagination a, .pagination span {
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            text-decoration: none;
            font-weight: 500;
        }

        .pagination a {
            background: white;
            color: var(--gray-700);
            border: 1px solid var(--gray-200);
        }

        .pagination a:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .pagination .active {
            background: var(--primary);
            color: white;
        }

        @media (max-width: 768px) {
            .search-box {
                grid-template-columns: 1fr;
            }
            
            .hero h1 { font-size: 1.75rem; }
            
            .hostels-grid {
                grid-template-columns: 1fr;
            }

            .nav-links { display: none; }
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
                <a href="{{ route('public.hostels.browse') }}" class="highlight">
                    <i class="fas fa-search"></i> Browse Hostels
                </a>
                <a href="{{ url('/') }}#features">Why Choose Us</a>
                <a href="{{ url('/') }}#how-it-works">How It Works</a>
                <a href="{{ url('/') }}#testimonials">Reviews</a>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-th-large"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
                @endauth
            </div>

            <button class="mobile-menu-btn" style="display: none;">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <div class="hero-container">
            <h1>Find Your Perfect Accommodation</h1>
            <p>Browse hostels near CUG campus. Book and pay securely online.</p>

            <form action="{{ route('public.hostels.browse') }}" method="GET" class="search-box">
                <div class="search-group">
                    <label>Hostel Type</label>
                    <select name="type">
                        <option value="">All Types</option>
                        <option value="boys" {{ request('type') === 'boys' ? 'selected' : '' }}>Boys Only</option>
                        <option value="girls" {{ request('type') === 'girls' ? 'selected' : '' }}>Girls Only</option>
                        <option value="mixed" {{ request('type') === 'mixed' ? 'selected' : '' }}>Mixed</option>
                    </select>
                </div>
                <div class="search-group">
                    <label>Max Walking Time</label>
                    <select name="max_walking_time">
                        <option value="">Any Distance</option>
                        <option value="5" {{ request('max_walking_time') == '5' ? 'selected' : '' }}>5 min walk</option>
                        <option value="10" {{ request('max_walking_time') == '10' ? 'selected' : '' }}>10 min walk</option>
                        <option value="15" {{ request('max_walking_time') == '15' ? 'selected' : '' }}>15 min walk</option>
                        <option value="20" {{ request('max_walking_time') == '20' ? 'selected' : '' }}>20 min walk</option>
                        <option value="30" {{ request('max_walking_time') == '30' ? 'selected' : '' }}>30 min walk</option>
                    </select>
                </div>
                <div class="search-group">
                    <label>Max Price (GHS)</label>
                    <input type="number" name="max_price" placeholder="e.g., 2000" value="{{ request('max_price') }}">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
        </div>
    </section>

    <!-- Content -->
    <main class="content">
        <div class="results-header">
            <div class="results-count">
                Showing <strong>{{ $hostels->total() }}</strong> hostels near CUG Campus
            </div>
            <select class="sort-select" onchange="window.location.href=this.value">
                <option value="{{ route('public.hostels.browse', array_merge(request()->query(), ['sort' => 'distance'])) }}" {{ request('sort', 'distance') === 'distance' ? 'selected' : '' }}>Sort by: Nearest</option>
                <option value="{{ route('public.hostels.browse', array_merge(request()->query(), ['sort' => 'price_low'])) }}" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Sort by: Price (Low to High)</option>
                <option value="{{ route('public.hostels.browse', array_merge(request()->query(), ['sort' => 'price_high'])) }}" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Sort by: Price (High to Low)</option>
                <option value="{{ route('public.hostels.browse', array_merge(request()->query(), ['sort' => 'name'])) }}" {{ request('sort') === 'name' ? 'selected' : '' }}>Sort by: Name</option>
            </select>
        </div>

        @if($hostels->count() > 0)
            <div class="hostels-grid">
                @foreach($hostels as $hostel)
                    <div class="hostel-card">
                        <div class="hostel-image">
                            <img src="{{ $hostel->cover_image_url }}" alt="{{ $hostel->name }}">
                            <span class="hostel-badge {{ $hostel->hostel_type }}">
                                {{ ucfirst($hostel->hostel_type) }}
                            </span>
                            @if($hostel->walking_time_minutes || $hostel->distance_km)
                                <span class="hostel-distance">
                                    <i class="fas fa-walking"></i>
                                    {{ $hostel->distance_display }}
                                </span>
                            @endif
                        </div>
                        <div class="hostel-content">
                            <h3 class="hostel-name">
                                <a href="{{ route('public.hostels.show', $hostel) }}">{{ $hostel->name }}</a>
                            </h3>
                            <div class="hostel-address">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $hostel->address }}
                            </div>
                            <div class="hostel-stats">
                                <div class="stat">
                                    <div class="stat-value">{{ $hostel->rooms->count() }}</div>
                                    <div class="stat-label">Rooms</div>
                                </div>
                                <div class="stat">
                                    <div class="stat-value">{{ $hostel->beds_available }}</div>
                                    <div class="stat-label">Beds Available</div>
                                </div>
                            </div>
                            <div class="hostel-price">
                                @php
                                    $minPrice = $hostel->rooms->min('price_per_semester');
                                @endphp
                                <div class="price">
                                    ₵{{ number_format($minPrice ?? 0) }} <span>/semester</span>
                                </div>
                                <a href="{{ route('public.hostels.show', $hostel) }}" class="btn btn-primary btn-sm">
                                    View Details
                                </a>
                            </div>
                            @if($hostel->amenities && count($hostel->amenities) > 0)
                                <div class="hostel-amenities">
                                    @foreach(array_slice($hostel->amenities, 0, 4) as $amenity)
                                        <span class="amenity-tag">{{ $amenity }}</span>
                                    @endforeach
                                    @if(count($hostel->amenities) > 4)
                                        <span class="amenity-tag">+{{ count($hostel->amenities) - 4 }} more</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination">
                {{ $hostels->withQueryString()->links('pagination::simple-tailwind') }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <h3>No hostels found</h3>
                <p>Try adjusting your filters or check back later for new listings.</p>
            </div>
        @endif
    </main>
</body>
</html>

