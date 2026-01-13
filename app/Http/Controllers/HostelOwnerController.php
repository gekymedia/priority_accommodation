<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\RoomOccupant;
use App\Models\Room;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostelOwnerController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Show pending booking requests for hostel owner
     */
    public function bookingRequests()
    {
        $user = Auth::user();
        
        // Get hostels owned by this user
        $hostelIds = $user->hostels()->pluck('id');
        
        $pendingRequests = BookingRequest::whereIn('hostel_id', $hostelIds)
            ->where('status', 'awaiting_confirmation')
            ->with(['student.user', 'room', 'hostel'])
            ->orderBy('confirmation_deadline')
            ->get();

        $recentRequests = BookingRequest::whereIn('hostel_id', $hostelIds)
            ->whereIn('status', ['confirmed', 'rejected', 'timeout'])
            ->with(['student.user', 'room', 'hostel'])
            ->orderBy('updated_at', 'desc')
            ->take(20)
            ->get();

        return view('hostel-owner.booking-requests', [
            'pendingRequests' => $pendingRequests,
            'recentRequests' => $recentRequests,
        ]);
    }

    /**
     * Confirm a booking request
     */
    public function confirmRequest(BookingRequest $request)
    {
        $user = Auth::user();
        
        // Verify ownership
        if ($request->hostel->owner_id !== $user->id) {
            return back()->with('error', 'You are not authorized to manage this booking.');
        }

        if (!$request->isAwaitingConfirmation()) {
            return back()->with('error', 'This booking request can no longer be confirmed.');
        }

        try {
            $this->bookingService->confirmBooking($request, $user->id);
            return back()->with('success', 'Booking confirmed successfully! The student has been notified.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to confirm booking: ' . $e->getMessage());
        }
    }

    /**
     * Reject a booking request
     */
    public function rejectRequest(Request $httpRequest, BookingRequest $request)
    {
        $user = Auth::user();
        
        // Verify ownership
        if ($request->hostel->owner_id !== $user->id) {
            return back()->with('error', 'You are not authorized to manage this booking.');
        }

        if (!$request->isAwaitingConfirmation()) {
            return back()->with('error', 'This booking request can no longer be rejected.');
        }

        $httpRequest->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        try {
            $this->bookingService->rejectBooking($request, $httpRequest->rejection_reason, $user->id);
            return back()->with('success', 'Booking rejected. The student will receive a refund.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject booking: ' . $e->getMessage());
        }
    }

    /**
     * Show room occupants management
     */
    public function roomOccupants(Room $room)
    {
        $user = Auth::user();
        
        // Verify ownership
        if ($room->hostel->owner_id !== $user->id && !$user->role === 'admin') {
            return back()->with('error', 'You are not authorized to manage this room.');
        }

        $room->load(['occupants.student.user', 'hostel']);

        return view('hostel-owner.room-occupants', [
            'room' => $room,
        ]);
    }

    /**
     * Add manual occupant (walk-in booking)
     */
    public function addOccupant(Request $request, Room $room)
    {
        $user = Auth::user();
        
        // Verify ownership
        if ($room->hostel->owner_id !== $user->id && !$user->role === 'admin') {
            return back()->with('error', 'You are not authorized to manage this room.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'student_id_number' => 'nullable|string|max:50',
            'bed_number' => 'nullable|integer|min:1|max:' . $room->capacity,
            'move_in_date' => 'nullable|date',
            'move_out_date' => 'nullable|date|after:move_in_date',
            'academic_year' => 'nullable|string',
            'semester' => 'nullable|string',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $this->bookingService->addManualOccupant($room, $request->all(), $user->id);
            return back()->with('success', 'Occupant added successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to add occupant: ' . $e->getMessage());
        }
    }

    /**
     * Remove/checkout an occupant
     */
    public function removeOccupant(RoomOccupant $occupant)
    {
        $user = Auth::user();
        
        // Verify ownership
        if ($occupant->room->hostel->owner_id !== $user->id && !$user->role === 'admin') {
            return back()->with('error', 'You are not authorized to manage this occupant.');
        }

        try {
            $this->bookingService->checkOutOccupant($occupant);
            return back()->with('success', 'Occupant checked out successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to check out occupant: ' . $e->getMessage());
        }
    }
}

