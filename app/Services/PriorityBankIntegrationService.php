<?php

namespace App\Services;

use App\Models\Payment;
use App\Services\PriorityBankApiClient;
use Illuminate\Support\Facades\Log;

class PriorityBankIntegrationService
{
    protected PriorityBankApiClient $client;
    protected string $systemId = 'priority_accommodation';

    public function __construct()
    {
        $this->client = new PriorityBankApiClient(
            config('services.priority_bank.api_url'),
            config('services.priority_bank.api_token')
        );
    }

    /**
     * Push payment to Priority Bank
     * Rent and Security deposits → Income
     * Maintenance → Expense
     */
    public function pushPayment(Payment $payment): bool
    {
        if (!config('services.priority_bank.api_token')) {
            return false;
        }

        // Only push completed payments
        if ($payment->status !== Payment::STATUS_COMPLETED) {
            return false;
        }

        try {
            $extId = $payment->external_transaction_id;
            if (empty($extId)) {
                $extId = 'priority_accommodation_payment_' . $payment->id;
                $payment->external_transaction_id = $extId;
                $payment->saveQuietly();
            }

            $student = $payment->student;
            $booking = $payment->booking;
            $room = $booking?->room;
            $hostel = $booking?->hostel ?? $room?->hostel;

            // Determine if this is income or expense
            $isIncome = in_array($payment->type, [Payment::TYPE_RENT, Payment::TYPE_SECURITY]);
            $isExpense = $payment->type === Payment::TYPE_MAINTENANCE;

            if (!$isIncome && !$isExpense) {
                // Other types - push as income by default
                $isIncome = true;
            }

            $notes = "Payment #{$payment->receipt_number}";
            if ($student) {
                $notes .= " - Student: {$student->name}";
            }
            if ($room) {
                $notes .= " - Room: {$room->name}";
            }
            if ($booking) {
                $notes .= " - Booking #{$booking->id}";
            }
            if ($payment->description) {
                $notes .= " - {$payment->description}";
            }

            $metadata = [
                'payment_id' => $payment->id,
                'receipt_number' => $payment->receipt_number,
                'student_id' => $payment->student_id,
                'booking_id' => $payment->booking_id,
                'payment_type' => $payment->type,
                'payment_method' => $payment->payment_method,
                'transaction_id' => $payment->transaction_id,
            ];

            // Add broker model breakdown (Priority Accommodation is a broker, not owner)
            // When student pays, Priority Accommodation receives full amount (income)
            // Later, they pay hostel owner the base_price portion (expense - to be tracked separately)
            if ($booking) {
                $metadata['broker_model'] = true;
                $metadata['base_price'] = (float) ($booking->base_price ?? 0); // Amount to hostel owner
                $metadata['commission_amount'] = (float) ($booking->commission_amount ?? 0); // Priority Accommodation profit
                $metadata['total_amount'] = (float) ($booking->total_amount ?? $payment->amount); // Full payment received
                $metadata['hostel_id'] = $booking->hostel_id;
                $metadata['hostel_name'] = $hostel?->name;
                $metadata['profit_margin'] = $booking->commission_amount > 0 && $booking->total_amount > 0 
                    ? round(($booking->commission_amount / $booking->total_amount) * 100, 2) 
                    : 0;
            }

            if ($isExpense) {
                // Push maintenance as expense
                $result = $this->client->pushExpense(
                    systemId: $this->systemId,
                    externalTransactionId: $extId,
                    amount: (float) $payment->amount,
                    date: $payment->payment_date?->format('Y-m-d') ?? now()->format('Y-m-d'),
                    channel: $this->mapPaymentMethod($payment->payment_method),
                    options: [
                        'notes' => $notes,
                        'expense_category_name' => 'Maintenance',
                        'metadata' => $metadata,
                    ]
                );
            } else {
                // Push rent and security deposits as income
                $categoryName = $payment->type === Payment::TYPE_RENT ? 'Rent' : 'Security Deposits';
                
                $result = $this->client->pushIncome(
                    systemId: $this->systemId,
                    externalTransactionId: $extId,
                    amount: (float) $payment->amount,
                    date: $payment->payment_date?->format('Y-m-d') ?? now()->format('Y-m-d'),
                    channel: $this->mapPaymentMethod($payment->payment_method),
                    options: [
                        'notes' => $notes,
                        'income_category_name' => $categoryName,
                        'metadata' => $metadata,
                    ]
                );
            }

            return $result && $result['success'];
        } catch (\Exception $e) {
            Log::error('Exception pushing payment to Priority Bank', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Map payment method to channel
     */
    protected function mapPaymentMethod(?string $method): string
    {
        $mapping = [
            Payment::METHOD_CASH => 'cash',
            Payment::METHOD_BANK_TRANSFER => 'bank',
            Payment::METHOD_CARD => 'bank',
            Payment::METHOD_UPI => 'momo', // UPI similar to mobile money
        ];

        return $mapping[$method] ?? 'other';
    }
}

