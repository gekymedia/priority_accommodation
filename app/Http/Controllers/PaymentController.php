<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use App\Models\Student;
use App\Services\PriorityBankIntegrationService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['student', 'booking']);

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('receipt_number', 'like', "%{$request->search}%")
                  ->orWhereHas('student', function($q) use ($request) {
                      $q->where('name', 'like', "%{$request->search}%")
                        ->orWhere('student_id', 'like', "%{$request->search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        $payments = $query->latest()->paginate(15);

        // Statistics
        $totalRevenue = Payment::where('status', Payment::STATUS_COMPLETED)->sum('amount');
        $monthlyRevenue = Payment::where('status', Payment::STATUS_COMPLETED)
                                ->whereMonth('payment_date', now()->month)
                                ->whereYear('payment_date', now()->year)
                                ->sum('amount');
        $pendingPayments = Payment::where('status', Payment::STATUS_PENDING)->count();
        $totalTransactions = Payment::count();

        return view('payments.index', compact(
            'payments',
            'totalRevenue',
            'monthlyRevenue',
            'pendingPayments',
            'totalTransactions'
        ));
    }

    public function create()
    {
        $students = Student::all();
        $bookings = Booking::active()->get();
        $paymentMethods = Payment::getPaymentMethods();
        $paymentTypes = Payment::getTypes();

        return view('payments.create', compact(
            'students',
            'bookings',
            'paymentMethods',
            'paymentTypes'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'nullable|exists:bookings,id',
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,upi,card',
            'type' => 'required|in:rent,security,maintenance,other',
            'status' => 'required|in:pending,completed,failed',
            'payment_date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'transaction_id' => 'nullable|string|max:100'
        ]);

        // Generate receipt number
        $validated['receipt_number'] = 'PAY-' . now()->format('YmdHis') . '-' . str_pad(Payment::count() + 1, 4, '0', STR_PAD_LEFT);

        $payment = Payment::create($validated);

        // Push to Priority Bank if payment is completed
        if ($payment->status === Payment::STATUS_COMPLETED) {
            try {
                $integrationService = new PriorityBankIntegrationService();
                $integrationService->pushPayment($payment);
            } catch (\Exception $e) {
                \Log::error('Failed to push payment to Priority Bank', [
                    'payment_id' => $payment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['student', 'booking.room']);
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $students = Student::all();
        $bookings = Booking::active()->get();
        $paymentMethods = Payment::getPaymentMethods();
        $paymentTypes = Payment::getTypes();

        return view('payments.edit', compact(
            'payment',
            'students',
            'bookings',
            'paymentMethods',
            'paymentTypes'
        ));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'booking_id' => 'nullable|exists:bookings,id',
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,upi,card',
            'type' => 'required|in:rent,security,maintenance,other',
            'status' => 'required|in:pending,completed,failed',
            'payment_date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'transaction_id' => 'nullable|string|max:100'
        ]);

        $payment->update($validated);

        // Push to Priority Bank if payment status changed to completed
        if ($payment->status === Payment::STATUS_COMPLETED) {
            try {
                $integrationService = new PriorityBankIntegrationService();
                $integrationService->pushPayment($payment);
            } catch (\Exception $e) {
                \Log::error('Failed to push payment to Priority Bank', [
                    'payment_id' => $payment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment deleted successfully.');
    }

    public function receipt(Payment $payment)
    {
        $payment->load(['student', 'booking.room']);
        
        $pdf = Pdf::loadView('payments.receipt', compact('payment'));
        return $pdf->download('receipt-' . $payment->receipt_number . '.pdf');
    }

    public function markCompleted(Payment $payment)
    {
        $payment->update(['status' => Payment::STATUS_COMPLETED]);

        // Push to Priority Bank
        try {
            $integrationService = new PriorityBankIntegrationService();
            $integrationService->pushPayment($payment);
        } catch (\Exception $e) {
            \Log::error('Failed to push payment to Priority Bank', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()->back()
            ->with('success', 'Payment marked as completed.');
    }
}