<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HostelController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\PublicHostelController;
use App\Http\Controllers\HostelOwnerController;
use App\Http\Controllers\UserDashboardController;

// Landing Page Route
Route::get('/', function () {
    return view('landing');
});

// Legal Pages (Public)
Route::get('/terms', function () {
    return view('legal.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('legal.privacy');
})->name('privacy');

// ===== PUBLIC HOSTEL BROWSING & BOOKING ROUTES =====
Route::prefix('find-accommodation')->name('public.hostels.')->group(function () {
    Route::get('/', [PublicHostelController::class, 'index'])->name('browse');
    Route::get('/{hostel}', [PublicHostelController::class, 'show'])->name('show');
    Route::get('/{hostel}/room/{room}', [PublicHostelController::class, 'showRoom'])->name('room');
    
    // Booking routes (require auth)
    Route::middleware('auth')->group(function () {
        Route::get('/{hostel}/room/{room}/book', [PublicHostelController::class, 'startBooking'])->name('booking.start');
        Route::post('/{hostel}/room/{room}/book', [PublicHostelController::class, 'createBooking'])->name('booking.create');
    });
});

// Booking status page (public with token)
Route::get('/booking/status/{token}', [PublicHostelController::class, 'bookingStatus'])->name('public.bookings.status');

// Payment callbacks
Route::get('/payments/callback/hubtel', [PublicHostelController::class, 'paymentCallback'])
    ->defaults('provider', 'hubtel')
    ->name('payments.hubtel.callback');
Route::get('/payments/callback/paystack', [PublicHostelController::class, 'paymentCallback'])
    ->defaults('provider', 'paystack')
    ->name('payments.paystack.callback');
Route::get('/booking/{token}/cancelled', function ($token) {
    return redirect()->route('public.hostels.browse')->with('error', 'Payment was cancelled.');
})->name('public.bookings.payment.cancelled');

Route::get('auth/google/callback', [\App\Http\Controllers\Admin\GoogleAuthController::class, 'callback'])
    ->name('google-auth.callback.public');

// SSO Login Route (Public - no auth required)
Route::get('/sso/login', [\App\Http\Controllers\SsoController::class, 'login'])->name('sso.login');

// Authentication Routes
require __DIR__.'/auth.php';

// ===== USER ROUTES (Authenticated but not admin-specific) =====
Route::middleware('auth')->group(function () {
    // User Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/my-bookings', [UserDashboardController::class, 'bookings'])->name('user.bookings');
    Route::get('/my-profile', [UserDashboardController::class, 'profile'])->name('user.profile');
    Route::patch('/my-profile', [UserDashboardController::class, 'updateProfile'])->name('user.profile.update');

    // User Complaints (My Complaints)
    Route::get('/my-complaints', [\App\Http\Controllers\ComplaintController::class, 'myComplaints'])->name('complaints.my-complaints');
    Route::post('/complaints', [\App\Http\Controllers\ComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/complaints/create', [\App\Http\Controllers\ComplaintController::class, 'create'])->name('complaints.create');
    Route::get('/complaints/{complaint}', [\App\Http\Controllers\ComplaintController::class, 'show'])->name('complaints.show');

    // ===== HOSTEL OWNER ROUTES =====
    Route::prefix('hostel-owner')->name('hostel.')->group(function () {
        // Booking requests management
        Route::get('/booking-requests', [HostelOwnerController::class, 'bookingRequests'])->name('booking-requests');
        Route::post('/booking-requests/{request}/confirm', [HostelOwnerController::class, 'confirmRequest'])->name('booking-requests.confirm');
        Route::post('/booking-requests/{request}/reject', [HostelOwnerController::class, 'rejectRequest'])->name('booking-requests.reject');
        
        // Room occupants management
        Route::get('/rooms/{room}/occupants', [HostelOwnerController::class, 'roomOccupants'])->name('room-occupants');
        Route::post('/rooms/{room}/occupants', [HostelOwnerController::class, 'addOccupant'])->name('room-occupants.add');
        Route::delete('/occupants/{occupant}', [HostelOwnerController::class, 'removeOccupant'])->name('occupants.remove');
    });
});

// ===== ADMIN ROUTES - All prefixed with /admin =====
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/info', [ProfileController::class, 'updateProfile'])->name('profile.info.update');
    Route::patch('/profile/preferences', [ProfileController::class, 'updatePreferences'])->name('profile.preferences.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::patch('/profile/notifications', [ProfileController::class, 'updateNotifications'])->name('profile.notifications.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Hostel Management
    Route::resource('hostels', HostelController::class);
    Route::post('/hostels/{hostel}/toggle-status', [HostelController::class, 'toggleStatus'])->name('hostels.toggle-status');

    // Room Management
    Route::resource('rooms', RoomController::class);
    Route::patch('/rooms/{room}/status', [RoomController::class, 'updateStatus'])->name('rooms.status.update');
    
    // Booking Management
    Route::resource('bookings', BookingController::class);
    Route::post('/bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/checkin', [BookingController::class, 'checkIn'])->name('bookings.checkin');
    Route::post('/bookings/{booking}/checkout', [BookingController::class, 'checkOut'])->name('bookings.checkout');
    Route::get('/bookings/{booking}/invoice', [BookingController::class, 'invoice'])->name('bookings.invoice');
    
    // Student Management
    Route::resource('students', StudentController::class);
    Route::get('/students/{student}/history', [StudentController::class, 'bookingHistory'])->name('students.bookingHistory');
    
    // Payment Management
    Route::resource('payments', PaymentController::class);
    Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');
    Route::post('/payments/{payment}/mark-completed', [PaymentController::class, 'markCompleted'])->name('payments.mark-completed');
    Route::post('/payments/{payment}/sync', [PaymentController::class, 'sync'])->name('payments.sync');
    
    // Reports Routes
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('/reports/financial', [ReportsController::class, 'financialReport'])->name('reports.financial');
    Route::get('/reports/occupancy', [ReportsController::class, 'occupancyReport'])->name('reports.occupancy');
    Route::get('/reports/students', [ReportsController::class, 'studentReport'])->name('reports.students');
    Route::get('/reports/export', [ReportsController::class, 'exportReport'])->name('reports.export');
    
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::get('google/start', [\App\Http\Controllers\Admin\GoogleAuthController::class, 'start'])->name('google-auth.start');

    Route::prefix('backups')->name('backups.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\BackupsController::class, 'index'])->name('index');
        Route::get('/status', [\App\Http\Controllers\Admin\BackupsController::class, 'status'])->name('status');
    });

    // Audit Logs Routes
    Route::resource('audit-logs', AuditLogController::class)->only(['index', 'show']);

    // Admin Complaint Management
    Route::resource('complaints', \App\Http\Controllers\ComplaintController::class)->except(['create', 'store']);
});

// Smart redirect based on user role
Route::middleware('auth')->get('/redirect-dashboard', function () {
    $user = auth()->user();
    
    // Check if user is admin
    $isAdmin = $user->hasRole(['admin', 'super_admin']) || 
               in_array($user->role, ['admin', 'super_admin']);
    
    if ($isAdmin) {
        return redirect()->route('admin.dashboard');
    }
    
    return redirect()->route('user.dashboard');
})->name('dashboard');
