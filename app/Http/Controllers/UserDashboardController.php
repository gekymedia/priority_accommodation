<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Get user's bookings
        $myBookings = Booking::where(function ($query) use ($user) {
                // Check if booking is linked to user's email in student
                $query->whereHas('student', function ($q) use ($user) {
                    $q->where('email', $user->email);
                });
            })
            ->orWhere(function ($query) use ($user) {
                // Or if user has a student relationship
                if ($user->student) {
                    $query->where('student_id', $user->student->id);
                }
            })
            ->with(['room.hostel', 'student'])
            ->latest()
            ->take(5)
            ->get();

        // Get current active booking
        $currentBooking = Booking::where(function ($query) use ($user) {
                $query->whereHas('student', function ($q) use ($user) {
                    $q->where('email', $user->email);
                });
            })
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->with(['room.hostel'])
            ->first();

        // Get featured hostels for browsing
        $featuredHostels = Hostel::where('is_active', true)
            ->with(['rooms' => function ($query) {
                $query->where('status', 'available')->take(3);
            }])
            ->take(4)
            ->get();

        // Stats
        $totalBookings = Booking::whereHas('student', function ($q) use ($user) {
            $q->where('email', $user->email);
        })->count();

        $pendingBookings = Booking::whereHas('student', function ($q) use ($user) {
            $q->where('email', $user->email);
        })->where('status', 'pending')->count();

        return view('user.dashboard', compact(
            'user',
            'myBookings',
            'currentBooking',
            'featuredHostels',
            'totalBookings',
            'pendingBookings'
        ));
    }

    /**
     * Show user's booking history.
     */
    public function bookings()
    {
        $user = Auth::user();

        $bookings = Booking::where(function ($query) use ($user) {
                $query->whereHas('student', function ($q) use ($user) {
                    $q->where('email', $user->email);
                });
            })
            ->with(['room.hostel', 'student', 'payments'])
            ->latest()
            ->paginate(10);

        return view('user.bookings', compact('bookings'));
    }

    /**
     * Show user's profile.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    /**
     * Update user's profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $validated['profile_picture'] = $path;
        }

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }
}

