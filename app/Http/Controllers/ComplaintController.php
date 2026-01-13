<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Student;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Hostel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    /**
     * Display a listing of complaints (admin view)
     */
    public function index(Request $request)
    {
        $query = Complaint::with(['student', 'booking', 'room', 'hostel', 'assignedTo', 'resolvedBy']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('subject', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%")
                  ->orWhereHas('student', function($sq) use ($request) {
                      $sq->where('name', 'like', "%{$request->search}%")
                         ->orWhere('email', 'like', "%{$request->search}%");
                  });
            });
        }

        $complaints = $query->latest()->paginate(20);

        $stats = [
            'total' => Complaint::count(),
            'pending' => Complaint::where('status', Complaint::STATUS_PENDING)->count(),
            'in_progress' => Complaint::where('status', Complaint::STATUS_IN_PROGRESS)->count(),
            'resolved' => Complaint::resolved()->count(),
            'urgent' => Complaint::urgent()->open()->count(),
        ];

        return view('complaints.index', compact('complaints', 'stats'));
    }

    /**
     * Show the form for creating a new complaint (student view)
     */
    public function create()
    {
        $student = Auth::user()->student ?? Student::where('email', Auth::user()->email)->first();
        
        if (!$student) {
            return redirect()->route('dashboard')
                ->with('error', 'Student profile not found. Please contact support.');
        }

        // Get student's current booking
        $currentBooking = $student->currentBooking;
        $bookings = $student->bookings()->with('room.hostel')->latest()->get();

        return view('complaints.create', compact('student', 'currentBooking', 'bookings'));
    }

    /**
     * Store a newly created complaint
     */
    public function store(Request $request)
    {
        $student = Auth::user()->student ?? Student::where('email', Auth::user()->email)->first();
        
        if (!$student) {
            return redirect()->route('dashboard')
                ->with('error', 'Student profile not found.');
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'category' => 'required|in:' . implode(',', array_keys(Complaint::getCategories())),
            'priority' => 'required|in:' . implode(',', array_keys(Complaint::getPriorities())),
            'booking_id' => 'nullable|exists:bookings,id',
            'room_id' => 'nullable|exists:rooms,id',
            'hostel_id' => 'nullable|exists:hostels,id',
        ]);

        // Auto-fill booking/room/hostel if not provided but student has current booking
        if (!$validated['booking_id'] && !$validated['room_id']) {
            $currentBooking = $student->currentBooking;
            if ($currentBooking) {
                $validated['booking_id'] = $currentBooking->id;
                $validated['room_id'] = $currentBooking->room_id;
                $validated['hostel_id'] = $currentBooking->hostel_id;
            }
        }

        $validated['student_id'] = $student->id;

        $complaint = Complaint::create($validated);

        return redirect()->route('complaints.show', $complaint)
            ->with('success', 'Complaint submitted successfully. We will review it shortly.');
    }

    /**
     * Display the specified complaint
     */
    public function show(Complaint $complaint)
    {
        $complaint->load(['student', 'booking', 'room', 'hostel', 'assignedTo', 'resolvedBy']);

        // Check if user can view this complaint
        $user = Auth::user();
        $student = $user->student ?? Student::where('email', $user->email)->first();
        
        // Admin can view all, students can only view their own
        $isAdmin = $user->hasRole('admin') || $user->hasRole('super_admin') || ($user->is_admin ?? false);
        if (!$isAdmin && $complaint->student_id !== ($student?->id)) {
            abort(403, 'Unauthorized access.');
        }

        return view('complaints.show', compact('complaint'));
    }

    /**
     * Show the form for editing the specified complaint (admin only)
     */
    public function edit(Complaint $complaint)
    {
        $complaint->load(['student', 'booking', 'room', 'hostel']);
        $admins = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['admin', 'super_admin']);
        })->orWhere('is_admin', true)->get();

        return view('complaints.edit', compact('complaint', 'admins'));
    }

    /**
     * Update the specified complaint (admin only)
     */
    public function update(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Complaint::getStatuses())),
            'priority' => 'required|in:' . implode(',', array_keys(Complaint::getPriorities())),
            'assigned_to' => 'nullable|exists:users,id',
            'admin_response' => 'nullable|string',
        ]);

        // Handle status changes
        if ($validated['status'] === Complaint::STATUS_IN_PROGRESS) {
            $complaint->markAsInProgress($validated['assigned_to'] ?? null);
        } elseif ($validated['status'] === Complaint::STATUS_RESOLVED) {
            if (!$validated['admin_response']) {
                return back()->withErrors(['admin_response' => 'Response is required when resolving a complaint.']);
            }
            $complaint->markAsResolved($validated['admin_response'], Auth::id());
        } elseif ($validated['status'] === Complaint::STATUS_CLOSED) {
            $complaint->markAsClosed();
        } elseif ($validated['status'] === Complaint::STATUS_REJECTED) {
            if (!$validated['admin_response']) {
                return back()->withErrors(['admin_response' => 'Reason is required when rejecting a complaint.']);
            }
            $complaint->reject($validated['admin_response']);
        } else {
            $complaint->update($validated);
        }

        return redirect()->route('complaints.show', $complaint)
            ->with('success', 'Complaint updated successfully.');
    }

    /**
     * Remove the specified complaint
     */
    public function destroy(Complaint $complaint)
    {
        $complaint->delete();

        return redirect()->route('complaints.index')
            ->with('success', 'Complaint deleted successfully.');
    }

    /**
     * Student's complaint history
     */
    public function myComplaints()
    {
        $student = Auth::user()->student ?? Student::where('email', Auth::user()->email)->first();
        
        if (!$student) {
            return redirect()->route('dashboard')
                ->with('error', 'Student profile not found.');
        }

        $complaints = Complaint::where('student_id', $student->id)
            ->with(['booking', 'room', 'hostel'])
            ->latest()
            ->paginate(15);

        return view('complaints.my-complaints', compact('complaints'));
    }
}
