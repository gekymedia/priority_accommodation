<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Webhook Controller for receiving finance data from Priority Bank
 */
class PriorityBankWebhookController extends Controller
{
    /**
     * Handle income webhook from Priority Bank
     */
    public function handleIncome(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_type' => 'required|in:income',
            'priority_bank_transaction_id' => 'required|integer',
            'external_transaction_id' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'channel' => 'required|in:bank,momo,cash,other',
            'notes' => 'nullable|string',
            'category' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::warning('Priority Bank webhook validation failed', [
                'errors' => $validator->errors()->toArray(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $data = $validator->validated();

            // For Priority Accommodation, we don't create income records directly from webhooks
            // because income comes from payments (rent/security) which have specific structures.
            // We'll log it for reference.
            Log::info('Income received from Priority Bank webhook (logged only)', [
                'priority_bank_id' => $data['priority_bank_transaction_id'],
                'amount' => $data['amount'],
                'category' => $data['category'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Income logged (Priority Accommodation uses payment records, not direct income)',
            ]);

        } catch (\Exception $e) {
            Log::error('Exception handling Priority Bank income webhook', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process webhook',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    /**
     * Handle expense webhook from Priority Bank
     */
    public function handleExpense(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_type' => 'required|in:expense',
            'priority_bank_transaction_id' => 'required|integer',
            'external_transaction_id' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'channel' => 'required|in:bank,momo,cash,other',
            'notes' => 'nullable|string',
            'category' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::warning('Priority Bank webhook validation failed', [
                'errors' => $validator->errors()->toArray(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $data = $validator->validated();

            // Check if we already have this transaction
            $existing = Payment::where('description', 'LIKE', '%pb_' . $data['priority_bank_transaction_id'] . '%')
                ->where('type', Payment::TYPE_MAINTENANCE)
                ->first();
            
            if ($existing) {
                Log::info('Expense already exists from Priority Bank webhook', [
                    'payment_id' => $existing->id,
                    'priority_bank_id' => $data['priority_bank_transaction_id'],
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Expense already exists',
                    'data' => $existing,
                ]);
            }

            // Create maintenance payment record as expense
            // We need a booking_id, so we'll try to find an active booking or create a generic one
            // For webhook expenses, we'll create a payment with type 'maintenance' but without a specific booking
            // This is a limitation - ideally we'd need more context from Priority Bank
            
            // Find first active booking or use a placeholder approach
            $booking = Booking::where('status', Booking::STATUS_CHECKED_IN)->first();
            
            if (!$booking) {
                Log::warning('No active booking found for Priority Bank expense webhook', [
                    'priority_bank_id' => $data['priority_bank_transaction_id'],
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'No active booking found to associate expense with',
                ], 422);
            }

            $payment = Payment::create([
                'booking_id' => $booking->id,
                'student_id' => $booking->student_id,
                'receipt_number' => 'PB-EXP-' . now()->format('YmdHis') . '-' . $data['priority_bank_transaction_id'],
                'amount' => $data['amount'],
                'payment_method' => $this->mapChannelToPaymentMethod($data['channel']),
                'type' => Payment::TYPE_MAINTENANCE,
                'status' => Payment::STATUS_COMPLETED,
                'payment_date' => $data['date'],
                'description' => ($data['notes'] ?? "Expense from Priority Bank - {$data['category']}") . 
                              " [PB_ID: pb_{$data['priority_bank_transaction_id']}]",
            ]);

            Log::info('Expense received from Priority Bank webhook', [
                'payment_id' => $payment->id,
                'priority_bank_id' => $data['priority_bank_transaction_id'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Expense recorded successfully',
                'data' => $payment,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Exception handling Priority Bank expense webhook', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process webhook',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    /**
     * Map channel to payment method
     */
    protected function mapChannelToPaymentMethod(string $channel): string
    {
        $mapping = [
            'cash' => Payment::METHOD_CASH,
            'bank' => Payment::METHOD_BANK_TRANSFER,
            'momo' => Payment::METHOD_UPI,
            'other' => Payment::METHOD_CASH,
        ];

        return $mapping[$channel] ?? Payment::METHOD_CASH;
    }
}

