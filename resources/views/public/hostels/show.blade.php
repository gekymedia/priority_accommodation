<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $hostel->name }} - Priority Accommodations</title>
    
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

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-links a {
            color: var(--gray-600);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
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

        .btn-primary { background: var(--primary); color: var(--accent) !important; }
        .btn-primary:hover { background: var(--primary-dark); color: white !important; }
        .btn-outline { background: transparent; border: 2px solid var(--gray-300); color: var(--gray-700); }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
        .btn-secondary { background: var(--gray-100); color: var(--gray-700); }
        .btn-lg { padding: 1rem 2rem; font-size: 1rem; }

        /* Breadcrumb */
        .breadcrumb {
            background: white;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .breadcrumb-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        .breadcrumb a {
            color: var(--primary);
            text-decoration: none;
        }

        .breadcrumb a:hover { text-decoration: underline; }

        /* Gallery */
        .gallery {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1.5rem;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            grid-template-rows: 200px 200px;
            gap: 0.5rem;
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .gallery-main {
            grid-row: span 2;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .gallery-item img:hover { opacity: 0.9; }

        .gallery-more {
            position: relative;
            cursor: pointer;
        }

        .gallery-more::after {
            content: 'View All Photos';
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.5);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Content */
        .content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem 3rem;
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 2rem;
        }

        /* Main Info */
        .main-info {
            background: white;
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow);
        }

        .hostel-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .hostel-title h1 {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .hostel-location {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--gray-500);
        }

        .hostel-badges {
            display: flex;
            gap: 0.5rem;
        }

        .badge {
            padding: 0.35rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-type { background: var(--primary-light); color: var(--primary); }
        .badge-distance { background: var(--gray-100); color: var(--gray-600); }

        .section {
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i { color: var(--primary); }

        .description {
            color: var(--gray-600);
            line-height: 1.8;
        }

        /* Amenities Grid */
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .amenity-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: var(--gray-50);
            border-radius: var(--radius);
        }

        .amenity-item i {
            color: var(--primary);
            width: 20px;
        }

        /* Map */
        .map-container {
            height: 250px;
            background: var(--gray-200);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Rules */
        .rules-list {
            list-style: none;
        }

        .rules-list li {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--gray-100);
            color: var(--gray-600);
        }

        .rules-list li i {
            color: var(--secondary);
            margin-top: 0.2rem;
        }

        /* Rooms Section */
        .rooms-section {
            margin-top: 2rem;
        }

        .rooms-grid {
            display: grid;
            gap: 1rem;
        }

        .room-card {
            display: grid;
            grid-template-columns: 150px 1fr auto;
            gap: 1.5rem;
            background: white;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            transition: all 0.2s;
        }

        .room-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
        }

        .room-image {
            width: 150px;
            height: 120px;
            border-radius: var(--radius);
            overflow: hidden;
        }

        .room-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .room-info h3 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .room-meta {
            display: flex;
            gap: 1rem;
            color: var(--gray-500);
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
        }

        .room-meta span {
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .room-features {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .room-feature {
            background: var(--gray-100);
            color: var(--gray-600);
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius);
            font-size: 0.75rem;
        }

        .room-booking {
            text-align: right;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .room-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .room-price span {
            font-size: 0.875rem;
            font-weight: 400;
            color: var(--gray-500);
        }

        .room-availability {
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
        }

        .room-availability.available { color: var(--primary); }
        .room-availability.limited { color: var(--accent); }
        .room-availability.full { color: var(--secondary); }

        /* Sidebar */
        .sidebar {
            position: sticky;
            top: 80px;
            height: fit-content;
        }

        .booking-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-lg);
            margin-bottom: 1rem;
        }

        .booking-price {
            text-align: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .booking-price .from {
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        .booking-price .price {
            font-size: 2rem;
            font-weight: 800;
            color: var(--dark);
        }

        .booking-price .period {
            color: var(--gray-500);
        }

        .booking-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .booking-stat {
            text-align: center;
            padding: 1rem;
            background: var(--gray-50);
            border-radius: var(--radius);
        }

        .booking-stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }

        .booking-stat-label {
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        .contact-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow);
        }

        .contact-card h3 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 0;
            color: var(--gray-600);
        }

        .contact-item i {
            color: var(--primary);
            width: 20px;
        }

        .contact-item a {
            color: var(--primary);
            text-decoration: none;
        }

        @media (max-width: 1024px) {
            .content {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
            }

            .gallery-grid {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: 200px 150px;
            }

            .gallery-main {
                grid-column: span 2;
                grid-row: span 1;
            }
        }

        @media (max-width: 768px) {
            .room-card {
                grid-template-columns: 1fr;
            }

            .room-image {
                width: 100%;
                height: 180px;
            }

            .room-booking {
                text-align: left;
            }

            .amenities-grid {
                grid-template-columns: 1fr 1fr;
            }
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
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="breadcrumb-container">
            <a href="{{ route('public.hostels.browse') }}">Hostels</a>
            <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
            <span>{{ $hostel->name }}</span>
        </div>
    </div>

    <!-- Gallery -->
    <div class="gallery">
        <div class="gallery-grid">
            <div class="gallery-item gallery-main">
                <img src="{{ $hostel->cover_image_url }}" alt="{{ $hostel->name }}">
            </div>
            @foreach(array_slice($hostel->image_urls, 0, 3) as $index => $image)
                <div class="gallery-item {{ $index === 2 && count($hostel->image_urls) > 4 ? 'gallery-more' : '' }}">
                    <img src="{{ $image }}" alt="{{ $hostel->name }} - Image {{ $index + 2 }}">
                </div>
            @endforeach
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="main-info">
            <div class="hostel-header">
                <div class="hostel-title">
                    <h1>{{ $hostel->name }}</h1>
                    <div class="hostel-location">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $hostel->address }}
                    </div>
                </div>
                <div class="hostel-badges">
                    <span class="badge badge-type">{{ ucfirst($hostel->hostel_type) }}</span>
                    @if($hostel->walking_time_minutes)
                        <span class="badge badge-distance">
                            <i class="fas fa-walking"></i> {{ $hostel->walking_time_minutes }} min walk
                        </span>
                    @endif
                </div>
            </div>

            <div class="section">
                <h2 class="section-title"><i class="fas fa-info-circle"></i> About This Hostel</h2>
                <p class="description">{{ $hostel->description ?? 'No description available.' }}</p>
            </div>

            @if($hostel->amenities && count($hostel->amenities) > 0)
                <div class="section">
                    <h2 class="section-title"><i class="fas fa-check-circle"></i> Amenities</h2>
                    <div class="amenities-grid">
                        @foreach($hostel->amenities as $amenity)
                            <div class="amenity-item">
                                <i class="fas fa-check"></i>
                                <span>{{ $amenity }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($hostel->google_maps_url || ($hostel->latitude && $hostel->longitude))
                <div class="section">
                    <h2 class="section-title"><i class="fas fa-map"></i> Location</h2>
                    <div class="map-container">
                        @if($hostel->latitude && $hostel->longitude)
                            <iframe 
                                src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google_maps.api_key', 'YOUR_API_KEY') }}&q={{ $hostel->latitude }},{{ $hostel->longitude }}"
                                allowfullscreen>
                            </iframe>
                        @else
                            <iframe 
                                src="{{ $hostel->google_maps_url }}"
                                allowfullscreen>
                            </iframe>
                        @endif
                    </div>
                </div>
            @endif

            @if($hostel->rules)
                <div class="section">
                    <h2 class="section-title"><i class="fas fa-clipboard-list"></i> Hostel Rules</h2>
                    <ul class="rules-list">
                        @foreach(explode("\n", $hostel->rules) as $rule)
                            @if(trim($rule))
                                <li>
                                    <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                                    <span>{{ trim($rule) }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Rooms Section -->
            <div class="rooms-section">
                <h2 class="section-title"><i class="fas fa-bed"></i> Available Rooms</h2>
                <div class="rooms-grid">
                    @forelse($hostel->rooms as $room)
                        <div class="room-card">
                            <div class="room-image">
                                @if($room->photos && count($room->photos) > 0)
                                    <img src="{{ asset('storage/' . $room->photos[0]) }}" alt="Room {{ $room->room_number }}">
                                @else
                                    <img src="{{ asset('images/room-placeholder.jpg') }}" alt="Room {{ $room->room_number }}">
                                @endif
                            </div>
                            <div class="room-info">
                                <h3>Room {{ $room->room_number }} - {{ ucfirst($room->type) }}</h3>
                                <div class="room-meta">
                                    <span><i class="fas fa-users"></i> {{ $room->capacity }} person(s)</span>
                                    <span><i class="fas fa-bed"></i> {{ $room->beds_available }} bed(s) available</span>
                                </div>
                                @if($room->features && count($room->features) > 0)
                                    <div class="room-features">
                                        @foreach(array_slice($room->features, 0, 4) as $feature)
                                            <span class="room-feature">{{ $feature }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="room-booking">
                                <div class="room-price">
                                    ₵{{ number_format($room->price_per_semester) }} <span>/semester</span>
                                </div>
                                <div class="room-availability {{ $room->beds_available > 1 ? 'available' : ($room->beds_available === 1 ? 'limited' : 'full') }}">
                                    @if($room->beds_available > 1)
                                        <i class="fas fa-check-circle"></i> {{ $room->beds_available }} beds available
                                    @elseif($room->beds_available === 1)
                                        <i class="fas fa-exclamation-circle"></i> Only 1 bed left!
                                    @else
                                        <i class="fas fa-times-circle"></i> Fully booked
                                    @endif
                                </div>
                                @if($room->beds_available > 0)
                                    <a href="{{ route('public.hostels.room', [$hostel, $room]) }}" class="btn btn-primary">
                                        Book Now
                                    </a>
                                @else
                                    <button class="btn btn-secondary" disabled>Not Available</button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p style="color: var(--gray-500); text-align: center; padding: 2rem;">No rooms available at the moment.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <div class="booking-card">
                <div class="booking-price">
                    <div class="from">Starting from</div>
                    <div class="price">₵{{ number_format($hostel->rooms->min('price_per_semester') ?? 0) }}</div>
                    <div class="period">per semester</div>
                </div>
                <div class="booking-stats">
                    <div class="booking-stat">
                        <div class="booking-stat-value">{{ $hostel->rooms->count() }}</div>
                        <div class="booking-stat-label">Total Rooms</div>
                    </div>
                    <div class="booking-stat">
                        <div class="booking-stat-value">{{ $hostel->beds_available }}</div>
                        <div class="booking-stat-label">Beds Available</div>
                    </div>
                </div>
                <a href="#rooms" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center;">
                    <i class="fas fa-bed"></i> View Rooms
                </a>
            </div>

            <div class="contact-card">
                <h3>Contact Information</h3>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <a href="tel:{{ $hostel->contact_phone }}">{{ $hostel->contact_phone }}</a>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:{{ $hostel->contact_email }}">{{ $hostel->contact_email }}</a>
                </div>
                @if($hostel->google_maps_url)
                    <div class="contact-item">
                        <i class="fas fa-directions"></i>
                        <a href="{{ $hostel->google_maps_url }}" target="_blank">Get Directions</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>

