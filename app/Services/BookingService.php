<?php

namespace App\Services;

use App\Models\BookingRequest;
use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomOccupant;
use App\Models\Student;
use App\Models\PaymentSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BookingService
{
    protected PaymentService $paymentService;
    protected NotificationService $notificationService;
    protected PaymentSettings $settings;

    public function __construct(PaymentService $paymentService, NotificationService $notificationService)
    {
        $this->paymentService = $paymentService;
        $this->notificationService = $notificationService;
        $this->settings = PaymentSettings::first() ?? new PaymentSettings();
    }

    /**
     * Create a new booking request
     */
    public function createBookingRequest(array $data): BookingRequest
    {
        $room = Room::with('hostel')->findOrFail($data['room_id']);
        $hostel = $room->hostel;

        // Calculate pricing with commission
        $pricing = $room->calculatePriceWithCommission();
        $bedsRequested = $data['beds_requested'] ?? 1;
        
        // For shared rooms, calculate per-bed price
        if ($room->capacity > 1 && $room->allow_partial_booking) {
            $pricePerBed = $pricing['total_price'] / $room->capacity;
            $totalPrice = $pricePerBed * $bedsRequested;
            $basePrice = ($pricing['base_price'] / $room->capacity) * $bedsRequested;
            $commissionAmount = ($pricing['commission_amount'] / $room->capacity) * $bedsRequested;
        } else {
            $totalPrice = $pricing['total_price'];
            $basePrice = $pricing['base_price'];
            $commissionAmount = $pricing['commission_amount'];
        }

        $request = BookingRequest::create([
            'student_id' => $data['student_id'],
            'room_id' => $room->id,
            'hostel_id' => $hostel->id,
            'beds_requested' => $bedsRequested,
            'check_in_date' => $data['check_in_date'],
            'check_out_date' => $data['check_out_date'],
            'academic_year' => $data['academic_year'],
            'semester' => $data['semester'],
            'base_price' => $basePrice,
            'commission_amount' => $commissionAmount,
            'total_amount' => round($totalPrice, 2),
            'status' => 'pending_payment',
            'expires_at' => now()->addHours(24), // Request expires in 24 hours if not paid
        ]);

        return $request;
    }

    /**
     * Process payment completion and start confirmation workflow
     */
    public function handlePaymentCompleted(BookingRequest $request, array $paymentData): void
    {
        DB::transaction(function () use ($request, $paymentData) {
            // Update request status
            $request->update([
                'status' => 'awaiting_confirmation',
                'payment_completed_at' => now(),
                'confirmation_sent_at' => now(),
                'confirmation_deadline' => now()->addMinutes($this->settings->confirmation_timeout_minutes),
                'payment_response' => $paymentData,
            ]);

            // Notify hostel owner for confirmation
            $this->notificationService->notifyHostelOwnerOfBooking($request);

            // Notify admin of payment
            if ($this->settings->notify_admin_on_payment) {
                $this->notificationService->notifyAdminOfPayment($request);
            }
        });
    }

    /**
     * Confirm a booking request (called by hostel owner)
     */
    public function confirmBooking(BookingRequest $request, ?int $userId = null): Booking
    {
        return DB::transaction(function () use ($request, $userId) {
            // Mark request as confirmed
            $request->update([
                'status' => 'confirmed',
                'owner_responded_at' => now(),
                'responded_by' => $userId,
            ]);

            // Create the actual booking
            $booking = $this->createBookingFromRequest($request);

            // Add occupant to room
            $this->addOccupantToRoom($booking);

            // Notify student of confirmation
            $this->notificationService->notifyStudentOfConfirmation($request, true);

            // Notify roommates if any
            $this->notificationService->notifyRoommatesOfNewOccupant($booking);

            return $booking;
        });
    }

    /**
     * Reject a booking request (room unavailable)
     */
    public function rejectBooking(BookingRequest $request, string $reason, ?int $userId = null): void
    {
        DB::transaction(function () use ($request, $reason, $userId) {
            $request->update([
                'status' => 'rejected',
                'rejection_reason' => $reason,
                'owner_responded_at' => now(),
                'responded_by' => $userId,
            ]);

            // Notify student
            $this->notificationService->notifyStudentOfConfirmation($request, false);

            // TODO: Initiate refund process
        });
    }

    /**
     * Handle timeout (no response from hostel owner)
     */
    public function handleConfirmationTimeout(BookingRequest $request): void
    {
        DB::transaction(function () use ($request) {
            if ($this->settings->auto_confirm_if_no_response) {
                // Auto-confirm the booking
                $this->confirmBooking($request);
            } else {
                // Mark as timed out (rejected)
                $request->update([
                    'status' => 'timeout',
                    'rejection_reason' => 'Hostel owner did not respond within the confirmation window.',
                ]);

                // Notify student
                $this->notificationService->notifyStudentOfConfirmation($request, false);

                // TODO: Initiate refund process
            }
        });
    }

    /**
     * Create booking from confirmed request
     */
    protected function createBookingFromRequest(BookingRequest $request): Booking
    {
        $student = $request->student;

        return Booking::create([
            'booking_request_id' => $request->id,
            'student_id' => $request->student_id,
            'user_id' => $student->user_id ?? null,
            'room_id' => $request->room_id,
            'hostel_id' => $request->hostel_id,
            'check_in' => $request->check_in_date,
            'check_out' => $request->check_out_date,
            'beds_booked' => $request->beds_requested,
            'academic_year' => $request->academic_year,
            'semester_period' => $request->semester,
            'base_price' => $request->base_price,
            'commission_amount' => $request->commission_amount,
            'total_amount' => $request->total_amount,
            'amount_to_hostel' => $request->base_price,
            'payment_status' => 'paid',
            'amount_paid' => $request->total_amount,
            'payment_provider' => $request->payment_provider,
            'payment_reference' => $request->payment_reference,
            'booking_source' => 'platform',
            'status' => Booking::STATUS_CONFIRMED,
            'confirmed_at' => now(),
        ]);
    }

    /**
     * Add occupant to room after booking
     */
    protected function addOccupantToRoom(Booking $booking): RoomOccupant
    {
        $student = $booking->student;
        $room = $booking->room;

        // Find the next available bed number
        $takenBeds = $room->activeOccupants()->pluck('bed_number')->filter()->toArray();
        $bedNumber = 1;
        while (in_array($bedNumber, $takenBeds) && $bedNumber <= $room->capacity) {
            $bedNumber++;
        }

        $occupant = RoomOccupant::create([
            'room_id' => $room->id,
            'student_id' => $booking->student_id,
            'booking_id' => $booking->id,
            'name' => $student->user?->name,
            'phone' => $student->user?->phone,
            'email' => $student->user?->email,
            'student_id_number' => $student->user?->student_id_number,
            'profile_picture' => $student->user?->profile_picture,
            'bed_number' => $bedNumber,
            'move_in_date' => $booking->check_in,
            'move_out_date' => $booking->check_out,
            'academic_year' => $booking->academic_year,
            'semester' => $booking->semester_period,
            'status' => 'active',
            'is_system_booking' => true,
            'visible_to_roommates' => true,
        ]);

        // Update room occupancy count
        $room->updateOccupancyCount();

        return $occupant;
    }

    /**
     * Manually add occupant (by admin/hostel owner for walk-in bookings)
     */
    public function addManualOccupant(Room $room, array $data, int $addedBy): RoomOccupant
    {
        // Find the next available bed number
        $takenBeds = $room->activeOccupants()->pluck('bed_number')->filter()->toArray();
        $bedNumber = $data['bed_number'] ?? 1;
        if (in_array($bedNumber, $takenBeds)) {
            while (in_array($bedNumber, $takenBeds) && $bedNumber <= $room->capacity) {
                $bedNumber++;
            }
        }

        $occupant = RoomOccupant::create([
            'room_id' => $room->id,
            'student_id' => $data['student_id'] ?? null,
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'student_id_number' => $data['student_id_number'] ?? null,
            'bed_number' => $bedNumber,
            'move_in_date' => $data['move_in_date'] ?? now(),
            'move_out_date' => $data['move_out_date'] ?? null,
            'academic_year' => $data['academic_year'] ?? null,
            'semester' => $data['semester'] ?? null,
            'status' => 'active',
            'is_system_booking' => false,
            'visible_to_roommates' => $data['visible_to_roommates'] ?? true,
            'added_by' => $addedBy,
            'notes' => $data['notes'] ?? null,
        ]);

        // Update room occupancy count
        $room->updateOccupancyCount();

        return $occupant;
    }

    /**
     * Check out an occupant
     */
    public function checkOutOccupant(RoomOccupant $occupant): void
    {
        $occupant->update([
            'status' => 'checked_out',
            'move_out_date' => now(),
        ]);

        // Update room occupancy count
        $occupant->room->updateOccupancyCount();
    }

    /**
     * Get roommates for a student
     */
    public function getRoommatesForStudent(Student $student): array
    {
        // Find the student's active room occupancy
        $myOccupancy = RoomOccupant::where('student_id', $student->id)
            ->where('status', 'active')
            ->first();

        if (!$myOccupancy) {
            return [];
        }

        // Get other occupants in the same room
        return $myOccupancy->room->activeOccupants()
            ->where('id', '!=', $myOccupancy->id)
            ->where('visible_to_roommates', true)
            ->get()
            ->map(fn($occupant) => $occupant->roommate_visible_info)
            ->toArray();
    }

    /**
     * Check for expired confirmation deadlines
     */
    public function processExpiredConfirmations(): int
    {
        $expiredRequests = BookingRequest::where('status', 'awaiting_confirmation')
            ->where('confirmation_deadline', '<=', now())
            ->get();

        foreach ($expiredRequests as $request) {
            $this->handleConfirmationTimeout($request);
        }

        return $expiredRequests->count();
    }
}

