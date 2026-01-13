<?php

namespace App\Services;

use App\Models\BookingRequest;
use App\Models\Booking;
use App\Models\BookingNotification;
use App\Models\User;
use App\Models\PaymentSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    protected PaymentSettings $settings;

    public function __construct()
    {
        $this->settings = PaymentSettings::first() ?? new PaymentSettings();
    }

    /**
     * Notify hostel owner of new booking request
     */
    public function notifyHostelOwnerOfBooking(BookingRequest $request): void
    {
        $hostel = $request->hostel;
        $owner = $hostel->owner;

        if (!$owner || !$owner->receive_booking_notifications) {
            return;
        }

        $notification = BookingNotification::create([
            'booking_request_id' => $request->id,
            'user_id' => $owner->id,
            'type' => 'confirmation_required',
            'title' => '🔔 New Booking Request - Action Required',
            'message' => "A student wants to book Room {$request->room->room_number}. " .
                "Amount: GHS {$request->total_amount}. " .
                "You have {$this->settings->confirmation_timeout_minutes} minutes to confirm or reject.",
            'data' => [
                'room_number' => $request->room->room_number,
                'student_name' => $request->student->user->name ?? 'Student',
                'amount' => $request->total_amount,
                'deadline' => $request->confirmation_deadline->toIso8601String(),
            ],
            'send_sms' => true,
            'send_email' => true,
            'action_url' => route('hostel.booking-requests.show', $request->id),
        ]);

        // Send SMS notification
        if ($owner->phone) {
            $this->sendSms(
                $owner->phone,
                "URGENT: New booking for Room {$request->room->room_number}. " .
                "GHS {$request->total_amount}. Reply within {$this->settings->confirmation_timeout_minutes} mins. " .
                "Open app to confirm/reject."
            );
            $notification->update(['sms_sent' => true, 'sms_sent_at' => now()]);
        }

        // Send Email notification
        if ($owner->email) {
            $this->sendEmail($owner->email, $notification);
            $notification->update(['email_sent' => true, 'email_sent_at' => now()]);
        }

        // Mark request as owner notified
        $request->update(['owner_notified' => true]);
    }

    /**
     * Notify admin of payment received
     */
    public function notifyAdminOfPayment(BookingRequest $request): void
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            BookingNotification::create([
                'booking_request_id' => $request->id,
                'user_id' => $admin->id,
                'type' => 'payment_received',
                'title' => '💰 Payment Received',
                'message' => "Payment of GHS {$request->total_amount} received for " .
                    "{$request->hostel->name}, Room {$request->room->room_number}. " .
                    "Commission: GHS {$request->commission_amount}.",
                'data' => [
                    'hostel' => $request->hostel->name,
                    'room' => $request->room->room_number,
                    'total' => $request->total_amount,
                    'commission' => $request->commission_amount,
                ],
                'send_email' => true,
            ]);
        }

        $request->update(['admin_notified' => true]);
    }

    /**
     * Notify student of booking confirmation
     */
    public function notifyStudentOfConfirmation(BookingRequest $request, bool $confirmed): void
    {
        $student = $request->student;
        $user = $student->user ?? null;

        if (!$user) {
            return;
        }

        if ($confirmed) {
            $notification = BookingNotification::create([
                'booking_request_id' => $request->id,
                'user_id' => $user->id,
                'type' => 'booking_confirmed',
                'title' => '✅ Booking Confirmed!',
                'message' => "Your room at {$request->hostel->name} has been confirmed! " .
                    "Room {$request->room->room_number} is now reserved for you.",
                'data' => [
                    'hostel' => $request->hostel->name,
                    'room' => $request->room->room_number,
                    'check_in' => $request->check_in_date->format('M d, Y'),
                ],
                'send_sms' => true,
                'send_email' => true,
            ]);
        } else {
            $notification = BookingNotification::create([
                'booking_request_id' => $request->id,
                'user_id' => $user->id,
                'type' => 'booking_rejected',
                'title' => '❌ Booking Not Available',
                'message' => "Unfortunately, Room {$request->room->room_number} at {$request->hostel->name} " .
                    "is no longer available. Your payment will be refunded within 24-48 hours. " .
                    "Reason: {$request->rejection_reason}",
                'data' => [
                    'hostel' => $request->hostel->name,
                    'room' => $request->room->room_number,
                    'reason' => $request->rejection_reason,
                ],
                'send_sms' => true,
                'send_email' => true,
            ]);
        }

        // Send SMS
        if ($user->phone) {
            $smsMessage = $confirmed
                ? "✅ Your booking at {$request->hostel->name} is confirmed! Room {$request->room->room_number}."
                : "❌ Room unavailable at {$request->hostel->name}. Refund processing. Check app for details.";
            
            $this->sendSms($user->phone, $smsMessage);
            $notification->update(['sms_sent' => true, 'sms_sent_at' => now()]);
        }

        $request->update(['student_notified' => true]);
    }

    /**
     * Notify roommates of new occupant
     */
    public function notifyRoommatesOfNewOccupant(Booking $booking): void
    {
        $room = $booking->room;
        $roommates = $room->occupants()
            ->where('student_id', '!=', $booking->student_id)
            ->where('status', 'active')
            ->where('visible_to_roommates', true)
            ->get();

        foreach ($roommates as $roommate) {
            if (!$roommate->student?->user) {
                continue;
            }

            BookingNotification::create([
                'booking_id' => $booking->id,
                'user_id' => $roommate->student->user->id,
                'type' => 'roommate_joined',
                'title' => '👋 New Roommate',
                'message' => "You have a new roommate in Room {$room->room_number}! " .
                    "Check your app to see who's sharing your room.",
                'data' => [
                    'room' => $room->room_number,
                ],
                'send_push' => true,
            ]);
        }
    }

    /**
     * Send SMS via configured gateway
     */
    protected function sendSms(string $phone, string $message): bool
    {
        if (!$this->settings->sms_gateway || !$this->settings->sms_api_key) {
            Log::info('SMS not configured', ['phone' => $phone, 'message' => $message]);
            return false;
        }

        try {
            // Format phone number for Ghana
            $phone = $this->formatGhanaPhone($phone);

            switch ($this->settings->sms_gateway) {
                case 'hubtel_sms':
                    return $this->sendHubtelSms($phone, $message);
                case 'arkesel':
                    return $this->sendArkeselSms($phone, $message);
                default:
                    Log::warning('Unknown SMS gateway', ['gateway' => $this->settings->sms_gateway]);
                    return false;
            }
        } catch (\Exception $e) {
            Log::error('SMS sending failed', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send SMS via Hubtel
     */
    protected function sendHubtelSms(string $phone, string $message): bool
    {
        $response = Http::withBasicAuth(
            $this->settings->hubtel_client_id,
            $this->settings->hubtel_client_secret
        )->post('https://smsc.hubtel.com/v1/messages/send', [
            'from' => $this->settings->sms_sender_id ?? 'PriorityAcc',
            'to' => $phone,
            'content' => $message,
        ]);

        return $response->successful();
    }

    /**
     * Send SMS via Arkesel
     */
    protected function sendArkeselSms(string $phone, string $message): bool
    {
        $response = Http::withHeaders([
            'api-key' => $this->settings->sms_api_key,
        ])->post('https://sms.arkesel.com/api/v2/sms/send', [
            'sender' => $this->settings->sms_sender_id ?? 'PriorityAcc',
            'recipients' => [$phone],
            'message' => $message,
        ]);

        return $response->successful();
    }

    /**
     * Format Ghana phone number
     */
    protected function formatGhanaPhone(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If starts with 0, replace with 233
        if (str_starts_with($phone, '0')) {
            $phone = '233' . substr($phone, 1);
        }

        // If doesn't start with 233, add it
        if (!str_starts_with($phone, '233')) {
            $phone = '233' . $phone;
        }

        return $phone;
    }

    /**
     * Send email notification
     */
    protected function sendEmail(string $email, BookingNotification $notification): bool
    {
        // For now, we'll use Laravel's mail system
        // This can be enhanced with proper email templates
        try {
            Mail::raw($notification->message, function ($message) use ($email, $notification) {
                $message->to($email)
                    ->subject($notification->title);
            });
            return true;
        } catch (\Exception $e) {
            Log::error('Email sending failed', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}

