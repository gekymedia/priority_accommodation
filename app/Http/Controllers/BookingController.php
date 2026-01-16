<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['room.hostel', 'student']);

        // Search
        if ($request->has('search') && $request->search) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by room
        if ($request->has('room_id') && $request->room_id) {
            $query->where('room_id', $request->room_id);
        }

        $bookings = $query->latest()->paginate(15);

        // Booking statistics
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $checkedInBookings = Booking::where('status', 'checked_in')->count();

        $rooms = Room::available()->get();
        $bookingStatuses = Booking::getStatuses();

        return view('bookings.index', compact(
            'bookings',
            'totalBookings',
            'pendingBookings',
            'confirmedBookings',
            'checkedInBookings',
            'rooms',
            'bookingStatuses'
        ));
    }

    public function create(Request $request)
    {
        $rooms = Room::available()->with('hostel')->get();
        $students = Student::doesntHave('currentBooking')->get();
        $bookingStatuses = Booking::getStatuses();

        $selectedRoom = null;
        if ($request->has('room_id')) {
            $selectedRoom = Room::find($request->room_id);
        }

        return view('bookings.create', compact(
            'rooms',
            'students',
            'bookingStatuses',
            'selectedRoom'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
            'total_amount' => 'required|numeric|min:0',
            'advance_paid' => 'nullable|numeric|min:0',
            'special_requirements' => 'nullable|string|max:500'
        ]);

        // Check if room is available for the selected dates
        $existingBooking = Booking::where('room_id', $validated['room_id'])
            ->where('status', '!=', 'cancelled')
            ->where(function($query) use ($validated) {
                $query->whereBetween('check_in', [$validated['check_in'], $validated['check_out']])
                      ->orWhereBetween('check_out', [$validated['check_in'], $validated['check_out']])
                      ->orWhere(function($q) use ($validated) {
                          $q->where('check_in', '<=', $validated['check_in'])
                            ->where('check_out', '>=', $validated['check_out']);
                      });
            })->exists();

        if ($existingBooking) {
            return redirect()->back()
                ->with('error', 'The selected room is not available for the chosen dates.')
                ->withInput();
        }

        // Check if student already has an active booking
        $activeStudentBooking = Booking::where('student_id', $validated['student_id'])
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->exists();

        if ($activeStudentBooking) {
            return redirect()->back()
                ->with('error', 'This student already has an active booking.')
                ->withInput();
        }

        $booking = Booking::create($validated);

        // Update room status if booking is confirmed or checked in
        if (in_array($validated['status'], ['confirmed', 'checked_in'])) {
            $booking->room->update(['status' => 'occupied']);
        }

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Booking created successfully.');
    }

    public function show(Booking $booking)
    {
        $booking->load(['room.hostel', 'student', 'payments']);
        
        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $rooms = Room::with('hostel')->get();
        $students = Student::all();
        $bookingStatuses = Booking::getStatuses();

        return view('bookings.edit', compact(
            'booking',
            'rooms',
            'students',
            'bookingStatuses'
        ));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
            'total_amount' => 'required|numeric|min:0',
            'advance_paid' => 'nullable|numeric|min:0',
            'special_requirements' => 'nullable|string|max:500'
        ]);

        // Check for booking conflicts (excluding current booking)
        if ($booking->room_id != $validated['room_id'] || 
            $booking->check_in != $validated['check_in'] || 
            $booking->check_out != $validated['check_out']) {
            
            $existingBooking = Booking::where('room_id', $validated['room_id'])
                ->where('id', '!=', $booking->id)
                ->where('status', '!=', 'cancelled')
                ->where(function($query) use ($validated) {
                    $query->whereBetween('check_in', [$validated['check_in'], $validated['check_out']])
                          ->orWhereBetween('check_out', [$validated['check_in'], $validated['check_out']])
                          ->orWhere(function($q) use ($validated) {
                              $q->where('check_in', '<=', $validated['check_in'])
                                ->where('check_out', '>=', $validated['check_out']);
                          });
                })->exists();

            if ($existingBooking) {
                return redirect()->back()
                    ->with('error', 'The selected room is not available for the chosen dates.')
                    ->withInput();
            }
        }

        $oldStatus = $booking->status;
        $oldRoomId = $booking->room_id;

        $booking->update($validated);

        // Handle room status changes
        if ($oldRoomId != $validated['room_id']) {
            // Free up old room if it's not occupied by other bookings
            $oldRoom = Room::find($oldRoomId);
            $hasOtherBookings = Booking::where('room_id', $oldRoomId)
                ->where('id', '!=', $booking->id)
                ->whereIn('status', ['confirmed', 'checked_in'])
                ->exists();
            
            if (!$hasOtherBookings) {
                $oldRoom->update(['status' => 'available']);
            }

            // Update new room status
            if (in_array($validated['status'], ['confirmed', 'checked_in'])) {
                Room::find($validated['room_id'])->update(['status' => 'occupied']);
            }
        } else {
            // Same room, update status based on booking status
            $room = $booking->room;
            if (in_array($validated['status'], ['confirmed', 'checked_in'])) {
                $room->update(['status' => 'occupied']);
            } elseif (in_array($validated['status'], ['checked_out', 'cancelled']) && 
                     in_array($oldStatus, ['confirmed', 'checked_in'])) {
                // Check if there are other active bookings for this room
                $hasOtherBookings = Booking::where('room_id', $room->id)
                    ->where('id', '!=', $booking->id)
                    ->whereIn('status', ['confirmed', 'checked_in'])
                    ->exists();
                
                if (!$hasOtherBookings) {
                    $room->update(['status' => 'available']);
                }
            }
        }

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Booking updated successfully.');
    }

    public function destroy(Booking $booking)
    {
        $room = $booking->room;
        
        $booking->delete();

        // Update room status if needed
        if (in_array($booking->status, ['confirmed', 'checked_in'])) {
            $hasOtherBookings = Booking::where('room_id', $room->id)
                ->whereIn('status', ['confirmed', 'checked_in'])
                ->exists();
            
            if (!$hasOtherBookings) {
                $room->update(['status' => 'available']);
            }
        }

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }

    public function confirm(Booking $booking)
    {
        if ($booking->status != 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending bookings can be confirmed.');
        }

        DB::transaction(function() use ($booking) {
            $booking->update(['status' => 'confirmed']);
            $booking->room->update(['status' => 'occupied']);
        });

        return redirect()->back()
            ->with('success', 'Booking confirmed successfully.');
    }

    public function checkIn(Booking $booking)
    {
        if ($booking->status != 'confirmed') {
            return redirect()->back()
                ->with('error', 'Only confirmed bookings can be checked in.');
        }

        $booking->update(['status' => 'checked_in']);

        return redirect()->back()
            ->with('success', 'Student checked in successfully.');
    }

    public function checkOut(Booking $booking)
    {
        if ($booking->status != 'checked_in') {
            return redirect()->back()
                ->with('error', 'Only checked-in bookings can be checked out.');
        }

        DB::transaction(function() use ($booking) {
            $booking->update(['status' => 'checked_out']);
            
            // Check if there are other active bookings for this room
            $hasOtherBookings = Booking::where('room_id', $booking->room_id)
                ->where('id', '!=', $booking->id)
                ->whereIn('status', ['confirmed', 'checked_in'])
                ->exists();
            
            if (!$hasOtherBookings) {
                $booking->room->update(['status' => 'available']);
            }
        });

        return redirect()->back()
            ->with('success', 'Student checked out successfully.');
    }

    public function invoice(Booking $booking)
    {
        $booking->load(['room.hostel', 'student', 'payments']);
        
        return view('bookings.invoice', compact('booking'));
    }
}