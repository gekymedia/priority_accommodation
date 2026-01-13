<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Priority Accommodations')</title>

    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#006b3f">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <!-- Styles -->
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
            --danger: #ce1126;
            --warning: #fcd116;
            --info: #0dcaf0;
            --light: #f8f9fa;
            --dark: #1a202c;
            --gray: #6c757d;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --border-color: #e5e7eb;
            --border-radius: 0.75rem;
            --border-radius-sm: 0.5rem;
            --border-radius-lg: 1rem;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --transition: all 0.3s ease;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --header-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--gray-50);
            color: var(--gray-900);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Ghana Flag Stripe at Top */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary) 33.33%, var(--accent) 33.33%, var(--accent) 66.66%, var(--primary) 66.66%);
            z-index: 10000;
        }

        /* Layout Structure */
        .app-layout {
            display: flex;
            min-height: 100vh;
            padding-top: 4px;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%);
            box-shadow: var(--shadow-lg);
            transition: var(--transition);
            position: fixed;
            left: 0;
            top: 4px;
            height: calc(100vh - 4px);
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: var(--header-height);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-brand i {
            font-size: 1.5rem;
            color: var(--accent);
        }

        .sidebar.collapsed .sidebar-brand span {
            display: none;
        }

        .sidebar-toggle {
            background: rgba(255,255,255,0.1);
            border: none;
            color: white;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-toggle:hover {
            background: rgba(255,255,255,0.2);
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            margin-bottom: 1.5rem;
        }

        .nav-section-title {
            padding: 0.75rem 1.25rem;
            font-size: 0.7rem;
            font-weight: 700;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar.collapsed .nav-section-title {
            display: none;
        }

        .nav-links {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.25rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 0;
            transition: var(--transition);
            border-left: 3px solid transparent;
            white-space: nowrap;
            overflow: hidden;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: var(--accent);
        }

        .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
            border-left-color: var(--accent);
            font-weight: 600;
        }

        .nav-link i {
            width: 1.5rem;
            text-align: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.875rem;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: auto;
        }

        .sidebar-footer-text {
            color: rgba(255,255,255,0.5);
            font-size: 0.75rem;
            text-align: center;
        }

        .sidebar-footer-text .star {
            color: var(--accent);
        }

        /* Main Content Area */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: var(--transition);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Top Bar */
        .top-bar {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 4px;
            z-index: 999;
        }

        .top-bar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--gray-600);
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: var(--border-radius-sm);
        }

        .mobile-menu-btn:hover {
            background: var(--gray-100);
            color: var(--primary);
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        .page-title-highlight {
            color: var(--primary);
        }

        /* Ghana Badge in Top Bar */
        .ghana-indicator {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--primary-light);
            padding: 0.4rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--primary);
        }

        .ghana-indicator i {
            color: var(--accent);
        }

        /* User Dropdown */
        .user-dropdown {
            position: relative;
        }

        .user-trigger {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.75rem;
            border: none;
            background: none;
            color: var(--gray-700);
            cursor: pointer;
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
        }

        .user-trigger:hover {
            background: var(--gray-100);
        }

        .user-avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            position: relative;
        }

        .user-avatar::after {
            content: '★';
            position: absolute;
            bottom: -2px;
            right: -2px;
            font-size: 0.65rem;
            color: var(--accent);
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }

        .user-info {
            text-align: left;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--gray-800);
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--primary);
            font-weight: 500;
        }

        .dropdown-icon {
            transition: transform 0.3s ease;
            color: var(--gray-500);
        }

        .user-dropdown.open .dropdown-icon {
            transform: rotate(180deg);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            width: 16rem;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            padding: 0.5rem;
            margin-top: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-0.5rem);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .user-dropdown.open .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--gray-700);
            text-decoration: none;
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .dropdown-item:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .dropdown-item i {
            width: 1rem;
            text-align: center;
        }

        .dropdown-divider {
            height: 1px;
            background: var(--border-color);
            margin: 0.5rem 0;
        }

        /* Content Area */
        .content-area {
            flex: 1;
            padding: 2rem 1.5rem;
            background: var(--gray-50);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.875rem;
            line-height: 1;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0 4px 14px rgba(0, 107, 63, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 107, 63, 0.4);
        }

        .btn-secondary {
            background: white;
            color: var(--gray-700);
            border: 2px solid var(--gray-200);
        }

        .btn-secondary:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
        }

        .btn-accent {
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: var(--dark);
            box-shadow: 0 4px 14px rgba(252, 209, 22, 0.3);
        }

        .btn-accent:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(252, 209, 22, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger), #a30d1e);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(206, 17, 38, 0.3);
        }

        /* Card Styles */
        .card {
            background: white;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            transition: var(--transition);
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--gray-50);
        }

        .card-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-title i {
            color: var(--primary);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: var(--border-radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 0.5rem;
            color: var(--gray-900);
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--gray-600);
            font-weight: 500;
        }

        .stat-icon {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .stat-icon.green {
            background: linear-gradient(135deg, rgba(0, 107, 63, 0.1), rgba(0, 107, 63, 0.2));
            color: var(--primary);
        }

        .stat-icon.gold {
            background: linear-gradient(135deg, rgba(252, 209, 22, 0.2), rgba(252, 209, 22, 0.3));
            color: var(--accent-dark);
        }

        .stat-icon.blue {
            background: linear-gradient(135deg, rgba(13, 202, 240, 0.1), rgba(13, 202, 240, 0.2));
            color: var(--info);
        }

        .stat-icon.orange {
            background: linear-gradient(135deg, rgba(252, 209, 22, 0.2), rgba(245, 158, 11, 0.3));
            color: #d97706;
        }

        .stat-icon.red {
            background: linear-gradient(135deg, rgba(206, 17, 38, 0.1), rgba(206, 17, 38, 0.2));
            color: var(--danger);
        }

        /* Table Styles */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: var(--gray-50);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid var(--border-color);
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .table tbody tr:hover {
            background: var(--gray-50);
        }

        /* Status Badges */
        .status {
            padding: 0.4rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .status.green {
            background: rgba(0, 107, 63, 0.1);
            color: var(--primary);
        }

        .status.gold, .status.orange {
            background: rgba(252, 209, 22, 0.2);
            color: #92400e;
        }

        .status.blue {
            background: rgba(13, 202, 240, 0.1);
            color: #0891b2;
        }

        .status.red {
            background: rgba(206, 17, 38, 0.1);
            color: var(--danger);
        }

        .status.gray {
            background: rgba(107, 114, 128, 0.1);
            color: var(--gray-600);
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            background: white;
            transition: var(--transition);
            font-size: 0.9rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(0, 107, 63, 0.1);
        }

        .form-error {
            color: var(--danger);
            font-size: 0.8rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        /* Alert Styles */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            font-size: 0.9rem;
        }

        .alert-success {
            background: rgba(0, 107, 63, 0.1);
            border: 1px solid rgba(0, 107, 63, 0.2);
            color: var(--primary);
        }

        .alert-warning {
            background: rgba(252, 209, 22, 0.15);
            border: 1px solid rgba(252, 209, 22, 0.3);
            color: #92400e;
        }

        .alert-danger {
            background: rgba(206, 17, 38, 0.1);
            border: 1px solid rgba(206, 17, 38, 0.2);
            color: var(--danger);
        }

        /* Utility Classes */
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .hidden { display: none; }
        .block { display: block; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .justify-center { justify-content: center; }
        .gap-2 { gap: 0.5rem; }
        .gap-4 { gap: 1rem; }
        .gap-6 { gap: 1.5rem; }
        .space-y-4 > * + * { margin-top: 1rem; }
        .space-y-6 > * + * { margin-top: 1.5rem; }
        .space-x-4 > * + * { margin-left: 1rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mt-4 { margin-top: 1rem; }
        .mt-6 { margin-top: 1.5rem; }
        .py-8 { padding-top: 2rem; padding-bottom: 2rem; }
        .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
        .rounded-full { border-radius: 9999px; }
        .font-bold { font-weight: 700; }
        .font-semibold { font-weight: 600; }
        .text-sm { font-size: 0.875rem; }
        .text-xs { font-size: 0.75rem; }
        .text-lg { font-size: 1.125rem; }
        .text-xl { font-size: 1.25rem; }
        .text-gray-500 { color: var(--gray-500); }
        .text-gray-600 { color: var(--gray-600); }
        .text-primary { color: var(--primary); }
        .bg-white { background: white; }
        .w-full { width: 100%; }

        .content-grid {
            display: grid;
            gap: 1.5rem;
        }

        @media (min-width: 1024px) {
            .content-grid {
                grid-template-columns: 2fr 1fr;
            }
        }

        .grid {
            display: grid;
        }

        .grid-cols-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        @media (min-width: 768px) {
            .md\:grid-cols-4 {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* Animation */
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0 !important;
            }

            .mobile-menu-btn {
                display: block;
            }

            .ghana-indicator {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .user-info {
                display: none;
            }

            .top-bar {
                padding: 0 1rem;
            }

            .content-area {
                padding: 1.5rem 1rem;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            }
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Table Responsive */
        .table-responsive {
            overflow-x: auto;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="app-layout">
        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                    <i class="fas fa-building"></i>
                    <span>Priority Accommodations</span>
                </a>
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>

            <nav class="sidebar-nav">
                <!-- Main Navigation Section -->
                <div class="nav-section">
                    <h3 class="nav-section-title">Main Menu</h3>
                    <ul class="nav-links">
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.hostels.index') }}" class="nav-link {{ request()->routeIs('admin.hostels.*') ? 'active' : '' }}">
                                <i class="fas fa-hotel"></i>
                                <span>Hostels</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.rooms.index') }}" class="nav-link {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
                                <i class="fas fa-door-closed"></i>
                                <span>Rooms</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                                <i class="fas fa-calendar-check"></i>
                                <span>Bookings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.students.index') }}" class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                                <i class="fas fa-user-graduate"></i>
                                <span>Students</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                                <i class="fas fa-credit-card"></i>
                                <span>Payments</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Management Section -->
                <div class="nav-section">
                    <h3 class="nav-section-title">Management</h3>
                    <ul class="nav-links">
                        <li class="nav-item">
                            <a href="{{ route('admin.complaints.index') }}" class="nav-link {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Complaints</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                                <i class="fas fa-chart-bar"></i>
                                <span>Reports</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.audit-logs.index') }}" class="nav-link {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
                                <i class="fas fa-history"></i>
                                <span>Audit Logs</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Settings Section -->
                <div class="nav-section">
                    <h3 class="nav-section-title">Settings</h3>
                    <ul class="nav-links">
                        <li class="nav-item">
                            <a href="{{ route('admin.profile.edit') }}" class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                                <i class="fas fa-user-cog"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                                <i class="fas fa-cogs"></i>
                                <span>System Settings</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="sidebar-footer">
                <p class="sidebar-footer-text">
                    <span class="star">★</span> Made in Ghana <span class="star">★</span>
                </p>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <header class="top-bar">
                <div class="top-bar-left">
                    <button class="mobile-menu-btn" id="mobileMenuBtn">
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="ghana-indicator">
                        <i class="fas fa-star"></i>
                        Ghana
                    </span>
                </div>

                <!-- User Dropdown -->
                <div class="user-dropdown" id="userDropdown">
                    <button class="user-trigger" id="userTrigger">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="user-info">
                            <div class="user-name">{{ Auth::user()->name }}</div>
                            <div class="user-role">Administrator</div>
                        </div>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </button>
                    
                    <div class="dropdown-menu">
                        <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">
                            <i class="fas fa-user-edit"></i>
                            My Profile
                        </a>
                        <a href="{{ route('admin.settings') }}" class="dropdown-item">
                            <i class="fas fa-cogs"></i>
                            Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="dropdown-item" style="color: var(--danger);">
                                <i class="fas fa-sign-out-alt"></i>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="content-area fade-in">
                <div class="container">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const userDropdown = document.getElementById('userDropdown');
            const userTrigger = document.getElementById('userTrigger');

            // Desktop sidebar toggle
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    const icon = this.querySelector('i');
                    if (sidebar.classList.contains('collapsed')) {
                        icon.className = 'fas fa-chevron-right';
                    } else {
                        icon.className = 'fas fa-chevron-left';
                    }
                });
            }

            // Mobile menu toggle
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    sidebar.classList.add('mobile-open');
                    sidebarOverlay.classList.add('active');
                });
            }

            // Close sidebar when clicking on overlay
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('mobile-open');
                    this.classList.remove('active');
                });
            }

            // User dropdown toggle
            if (userTrigger && userDropdown) {
                userTrigger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdown.classList.toggle('open');
                });
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', function() {
                if (userDropdown) userDropdown.classList.remove('open');
            });

            // Prevent dropdown close when clicking inside dropdown
            if (userDropdown) {
                userDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            // Close mobile sidebar when clicking on a link
            const mobileNavLinks = document.querySelectorAll('.nav-link');
            mobileNavLinks.forEach(link => {
                link.addEventListener('click', function() {
                    sidebar.classList.remove('mobile-open');
                    sidebarOverlay.classList.remove('active');
                });
            });
        });
    </script>
    @stack('scripts')

    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('ServiceWorker registration successful');
                    })
                    .catch(err => {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
        }
    </script>
</body>
</html>
