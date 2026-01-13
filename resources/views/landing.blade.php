<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Priority Accommodations - Find Your Perfect Student Hostel Near CUG Campus</title>
    <meta name="description" content="Find verified student hostels and rooms near Catholic University of Ghana. Browse, compare prices, and book your ideal accommodation online.">
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #006b3f;
            --primary-dark: #005530;
            --primary-light: #e8f5f0;
            --secondary: #ce1126;
            --accent: #fcd116;
            --accent-dark: #d4a90a;
            --success: #10b981;
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
            --radius-xl: 1.5rem;
            --shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--gray-50);
            color: var(--dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Navigation */
        .nav {
            background: white;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--gray-200);
            transition: all 0.3s;
        }

        .nav.scrolled {
            box-shadow: var(--shadow-md);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0.875rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 800;
            font-size: 1.35rem;
            color: var(--primary);
            text-decoration: none;
        }

        .nav-brand i {
            font-size: 1.5rem;
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
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: var(--radius);
            cursor: pointer;
            transition: all 0.3s;
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
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-accent {
            background: var(--accent);
            color: var(--dark);
        }
        .btn-accent:hover { 
            background: var(--accent-dark); 
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
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

        .btn-white {
            background: white;
            color: var(--primary);
        }
        .btn-white:hover {
            background: var(--gray-100);
            transform: translateY(-2px);
        }

        .btn-lg {
            padding: 1rem 2rem;
            font-size: 1.05rem;
        }

        .btn-xl {
            padding: 1.25rem 2.5rem;
            font-size: 1.1rem;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 10rem 1.5rem 6rem;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--secondary) 33.33%, var(--accent) 33.33%, var(--accent) 66.66%, var(--primary) 66.66%);
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 50%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='rgba(255,255,255,0.05)' d='M0,96L48,112C96,128,192,160,288,186.7C384,213,480,235,576,213.3C672,192,768,128,864,117.3C960,107,1056,149,1152,165.3C1248,181,1344,171,1392,165.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E") no-repeat bottom;
            opacity: 0.3;
        }

        .hero-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            line-height: 1.15;
            margin-bottom: 1.5rem;
        }

        .hero-content h1 span {
            color: var(--accent);
        }

        .hero-content p {
            font-size: 1.2rem;
            color: rgba(255,255,255,0.9);
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .hero-stats {
            display: flex;
            gap: 2.5rem;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.2);
        }

        .hero-stat {
            text-align: center;
            color: white;
        }

        .hero-stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--accent);
        }

        .hero-stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .hero-image {
            position: relative;
        }

        .hero-image-main {
            width: 100%;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            transform: perspective(1000px) rotateY(-5deg);
            transition: transform 0.5s;
        }

        .hero-image:hover .hero-image-main {
            transform: perspective(1000px) rotateY(0deg);
        }

        .hero-badge {
            position: absolute;
            bottom: -20px;
            left: -20px;
            background: white;
            padding: 1rem 1.5rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .hero-badge-icon {
            width: 50px;
            height: 50px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.25rem;
        }

        .hero-badge-text strong {
            display: block;
            color: var(--dark);
            font-size: 1.1rem;
        }

        .hero-badge-text span {
            color: var(--gray-500);
            font-size: 0.85rem;
        }

        /* Features Section */
        .features {
            padding: 6rem 1.5rem;
            background: white;
        }

        .section-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 4rem;
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
            font-size: 0.85rem;
            border-radius: 2rem;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 1rem;
        }

        .section-subtitle {
            color: var(--gray-600);
            font-size: 1.1rem;
            line-height: 1.7;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .feature-card {
            background: var(--gray-50);
            border-radius: var(--radius-lg);
            padding: 2rem;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .feature-card:hover {
            background: white;
            border-color: var(--primary-light);
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .feature-card:nth-child(2) .feature-icon {
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: var(--dark);
        }

        .feature-card:nth-child(3) .feature-icon {
            background: linear-gradient(135deg, var(--secondary), #a50d1f);
        }

        .feature-card:nth-child(5) .feature-icon {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }

        .feature-card:nth-child(6) .feature-icon {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.75rem;
        }

        .feature-description {
            color: var(--gray-600);
            line-height: 1.7;
        }

        /* How It Works */
        .how-it-works {
            padding: 6rem 1.5rem;
            background: linear-gradient(180deg, var(--gray-50) 0%, white 100%);
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            position: relative;
        }

        .steps-grid::before {
            content: '';
            position: absolute;
            top: 50px;
            left: 15%;
            right: 15%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--accent), var(--secondary), var(--primary));
            z-index: 0;
        }

        .step-card {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .step-number {
            width: 100px;
            height: 100px;
            background: white;
            border: 4px solid var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
            box-shadow: var(--shadow-lg);
        }

        .step-card:nth-child(2) .step-number {
            border-color: var(--accent);
            color: var(--accent-dark);
        }

        .step-card:nth-child(3) .step-number {
            border-color: var(--secondary);
            color: var(--secondary);
        }

        .step-card:nth-child(4) .step-number {
            border-color: var(--primary);
            color: var(--primary);
        }

        .step-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .step-description {
            color: var(--gray-600);
            font-size: 0.95rem;
        }

        /* Stats Section */
        .stats {
            padding: 5rem 1.5rem;
            background: var(--primary);
            position: relative;
            overflow: hidden;
        }

        .stats::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary) 33.33%, var(--accent) 33.33%, var(--accent) 66.66%, var(--primary-dark) 66.66%);
        }

        .stats-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 3rem;
        }

        .stat-item {
            text-align: center;
            color: white;
        }

        .stat-value {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--accent);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* Testimonials */
        .testimonials {
            padding: 6rem 1.5rem;
            background: white;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .testimonial-card {
            background: var(--gray-50);
            border-radius: var(--radius-lg);
            padding: 2rem;
            position: relative;
        }

        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: 1rem;
            right: 1.5rem;
            font-size: 5rem;
            color: var(--primary);
            opacity: 0.1;
            font-family: Georgia, serif;
        }

        .testimonial-text {
            color: var(--gray-700);
            font-size: 1rem;
            line-height: 1.8;
            margin-bottom: 1.5rem;
            font-style: italic;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .testimonial-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .testimonial-name {
            font-weight: 700;
            color: var(--dark);
        }

        .testimonial-role {
            font-size: 0.85rem;
            color: var(--gray-500);
        }

        .testimonial-stars {
            color: var(--accent);
            margin-top: 0.25rem;
        }

        /* CTA Section */
        .cta {
            padding: 6rem 1.5rem;
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            text-align: center;
            position: relative;
        }

        .cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary) 33.33%, var(--accent) 33.33%, var(--accent) 66.66%, var(--primary-light) 66.66%);
        }

        .cta-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .cta h2 {
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1rem;
        }

        .cta p {
            font-size: 1.15rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 2rem;
        }

        .cta-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .cta-note {
            margin-top: 2rem;
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
        }

        .cta-note i {
            color: var(--accent);
            margin-right: 0.5rem;
        }

        /* Footer */
        .footer {
            background: var(--dark);
            color: white;
            padding: 4rem 1.5rem 2rem;
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            font-size: 1.25rem;
            color: white;
            margin-bottom: 1rem;
        }

        .footer-brand i {
            color: var(--accent);
        }

        .footer-description {
            color: var(--gray-400);
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        .footer-social {
            display: flex;
            gap: 1rem;
        }

        .footer-social a {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s;
        }

        .footer-social a:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }

        .footer-title {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            color: white;
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .footer-links a {
            color: var(--gray-400);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--accent);
        }

        .footer-bottom {
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--gray-500);
            font-size: 0.9rem;
        }

        .footer-bottom a {
            color: var(--gray-400);
            text-decoration: none;
        }

        .footer-bottom a:hover {
            color: var(--accent);
        }

        /* Mobile Menu */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--dark);
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .hero-container {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero-content h1 {
                font-size: 2.75rem;
            }

            .hero-actions {
                justify-content: center;
            }

            .hero-stats {
                justify-content: center;
            }

            .hero-image {
                display: none;
            }

            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .steps-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 3rem;
            }

            .steps-grid::before {
                display: none;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .testimonials-grid {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
            }

            .hero {
                padding: 8rem 1.5rem 4rem;
            }

            .hero-content h1 {
                font-size: 2.25rem;
            }

            .hero-stats {
                flex-wrap: wrap;
                gap: 1.5rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .steps-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
            }

            .stat-value {
                font-size: 2.5rem;
            }

            .cta h2 {
                font-size: 1.75rem;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease forwards;
        }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="nav" id="navbar">
        <div class="nav-container">
            <a href="{{ url('/') }}" class="nav-brand">
                <i class="fas fa-building"></i>
                Priority Accommodations
            </a>
            
            <div class="nav-links">
                <a href="{{ route('public.hostels.browse') }}" class="highlight">
                    <i class="fas fa-search"></i> Browse Hostels
                </a>
                <a href="#features">Why Choose Us</a>
                <a href="#how-it-works">How It Works</a>
                <a href="#testimonials">Reviews</a>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-th-large"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
                @endauth
            </div>

            <button class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content fade-in-up">
                <h1>
                    Find Your Perfect<br>
                    <span>Student Accommodation</span>
                </h1>
                <p>
                    Discover verified hostels near CUG campus. Browse rooms with photos, 
                    compare prices, check distances, and book your ideal accommodation 
                    online - all in one place.
                </p>
                
                <div class="hero-actions">
                    <a href="{{ route('public.hostels.browse') }}" class="btn btn-accent btn-xl">
                        <i class="fas fa-search"></i>
                        Browse Available Hostels
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-white btn-xl">
                            <i class="fas fa-user-plus"></i>
                            Create Account
                        </a>
                    @endguest
                </div>

                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="hero-stat-value">100+</div>
                        <div class="hero-stat-label">Verified Hostels</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-value">500+</div>
                        <div class="hero-stat-label">Available Rooms</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-value">1000+</div>
                        <div class="hero-stat-label">Happy Students</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-value">4.8★</div>
                        <div class="hero-stat-label">Average Rating</div>
                    </div>
                </div>
            </div>

            <div class="hero-image fade-in-up delay-2">
                <img src="https://images.unsplash.com/photo-1555854877-bab0e564b8d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                     alt="Student Hostel" 
                     class="hero-image-main">
                <div class="hero-badge">
                    <div class="hero-badge-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="hero-badge-text">
                        <strong>100% Verified</strong>
                        <span>All hostels vetted</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="section-container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-star"></i>
                    Why Students Choose Us
                </div>
                <h2 class="section-title">Everything You Need to Find Your Room</h2>
                <p class="section-subtitle">
                    We make finding student accommodation simple, safe, and stress-free. 
                    Here's what makes us different.
                </p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-search-location"></i>
                    </div>
                    <h3 class="feature-title">Easy Search & Filter</h3>
                    <p class="feature-description">
                        Find hostels by location, price range, room type, and amenities. 
                        See walking distance to campus at a glance.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="feature-title">Verified & Safe</h3>
                    <p class="feature-description">
                        Every hostel is personally verified by our team. We only partner 
                        with trusted accommodation providers.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3 class="feature-title">Secure Online Booking</h3>
                    <p class="feature-description">
                        Pay securely with Mobile Money or Card. Get instant confirmation 
                        and digital receipts.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3 class="feature-title">Prime Locations</h3>
                    <p class="feature-description">
                        All hostels within walking distance of CUG campus. See exact 
                        walking and driving times.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="feature-title">See Your Roommates</h3>
                    <p class="feature-description">
                        View who else has booked in your room. Connect with roommates 
                        before you even move in.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="feature-title">24/7 Support</h3>
                    <p class="feature-description">
                        Our team is always available to help with bookings, payments, 
                        or any questions you have.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works" id="how-it-works">
        <div class="section-container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-magic"></i>
                    Simple Process
                </div>
                <h2 class="section-title">Book Your Room in 4 Easy Steps</h2>
                <p class="section-subtitle">
                    Finding and booking your perfect accommodation has never been easier.
                </p>
            </div>

            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h3 class="step-title">Browse Hostels</h3>
                    <p class="step-description">Search and filter hostels by price, location, and amenities.</p>
                </div>

                <div class="step-card">
                    <div class="step-number">2</div>
                    <h3 class="step-title">Choose Your Room</h3>
                    <p class="step-description">View photos, features, and select the room that fits you.</p>
                </div>

                <div class="step-card">
                    <div class="step-number">3</div>
                    <h3 class="step-title">Pay Securely</h3>
                    <p class="step-description">Complete payment via Mobile Money or Card instantly.</p>
                </div>

                <div class="step-card">
                    <div class="step-number">4</div>
                    <h3 class="step-title">Move In!</h3>
                    <p class="step-description">Receive confirmation and move into your new home.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value">100+</div>
                <div class="stat-label">Verified Hostels</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">500+</div>
                <div class="stat-label">Available Rooms</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">1000+</div>
                <div class="stat-label">Happy Students</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">4.8★</div>
                <div class="stat-label">Average Rating</div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials" id="testimonials">
        <div class="section-container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-quote-left"></i>
                    Student Reviews
                </div>
                <h2 class="section-title">What Students Say About Us</h2>
                <p class="section-subtitle">
                    Hear from students who found their perfect accommodation through our platform.
                </p>
            </div>

            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <p class="testimonial-text">
                        "I found the perfect hostel just 5 minutes from my classes! The booking was 
                        seamless and I could see photos of everything before paying. Highly recommend!"
                    </p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">KM</div>
                        <div>
                            <div class="testimonial-name">Kofi Mensah</div>
                            <div class="testimonial-role">Level 300, Computer Science</div>
                            <div class="testimonial-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <p class="testimonial-text">
                        "As a freshman, I was worried about finding accommodation. This platform made 
                        it so easy! I even got to see who my roommates would be before moving in."
                    </p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">AA</div>
                        <div>
                            <div class="testimonial-name">Ama Asante</div>
                            <div class="testimonial-role">Level 100, Nursing</div>
                            <div class="testimonial-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <p class="testimonial-text">
                        "The mobile money payment was instant and I got my confirmation right away. 
                        No stress, no hassle. This is how accommodation booking should be!"
                    </p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">KO</div>
                        <div>
                            <div class="testimonial-name">Kwame Owusu</div>
                            <div class="testimonial-role">Level 200, Business Admin</div>
                            <div class="testimonial-stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta" id="cta">
        <div class="cta-container">
            <h2>Ready to Find Your Perfect Room?</h2>
            <p>
                Join thousands of students who found their ideal accommodation through 
                Priority Accommodations. Start browsing now!
            </p>
            
            <div class="cta-actions">
                <a href="{{ route('public.hostels.browse') }}" class="btn btn-accent btn-xl">
                    <i class="fas fa-search"></i>
                    Browse Hostels Now
                </a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-white btn-xl">
                        <i class="fas fa-user-plus"></i>
                        Create Free Account
                    </a>
                @endguest
            </div>

            <p class="cta-note">
                <i class="fas fa-check-circle"></i>
                Free to browse &nbsp; • &nbsp;
                <i class="fas fa-check-circle"></i>
                Secure payments &nbsp; • &nbsp;
                <i class="fas fa-check-circle"></i>
                Instant confirmation
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand">
                        <i class="fas fa-building"></i>
                        Priority Accommodations
                    </div>
                    <p class="footer-description">
                        The trusted platform for finding verified student hostels near CUG campus. 
                        Making accommodation search easy, safe, and affordable.
                    </p>
                    <div class="footer-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

                <div>
                    <h4 class="footer-title">Quick Links</h4>
                    <div class="footer-links">
                        <a href="{{ route('public.hostels.browse') }}">Browse Hostels</a>
                        <a href="#features">Why Choose Us</a>
                        <a href="#how-it-works">How It Works</a>
                        <a href="#testimonials">Reviews</a>
                    </div>
                </div>

                <div>
                    <h4 class="footer-title">Support</h4>
                    <div class="footer-links">
                        <a href="#">Help Center</a>
                        <a href="#">Contact Us</a>
                        <a href="#">FAQs</a>
                        @if(Route::has('complaints.create'))
                            <a href="{{ route('complaints.create') }}">Report Issue</a>
                        @endif
                    </div>
                </div>

                <div>
                    <h4 class="footer-title">Legal</h4>
                    <div class="footer-links">
                        <a href="{{ route('terms') }}">Terms of Service</a>
                        <a href="{{ route('privacy') }}">Privacy Policy</a>
                        <a href="#">Refund Policy</a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Priority Accommodations. All rights reserved.</p>
                <p>Made with <i class="fas fa-heart" style="color: var(--secondary);"></i> for CUG students</p>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
