@extends('layouts.app')

@section('title', 'Payment Details - Priority Accommodations')
@section('page-title', 'Payment Details')
@section('page-subtitle', 'View payment information')

@section('content')
<div class="fade-in">
    <div class="max-w-4xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-receipt text-purple-600 mr-2"></i>
                    Payment Details - #{{ $payment->receipt_number }}
                </h3>
                <div class="flex gap-2">
                    <a href="{{ route('admin.payments.edit', $payment) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-edit"></i>
                        Edit
                    </a>
                    <button class="btn btn-secondary btn-sm" onclick="printReceipt({{ $payment->id }})">
                        <i class="fas fa-print"></i>
                        Print Receipt
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Payment Information -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h4>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Receipt Number:</span>
                                <span class="font-medium">#{{ $payment->receipt_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Amount:</span>
                                <span class="font-medium text-green-600">{{ $payment->formatted_amount }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Method:</span>
                                <span class="font-medium">{{ $payment->payment_method_text }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Type:</span>
                                <span class="font-medium">{{ $payment->type_text }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="payment-status status-{{ $payment->status }}">
                                    {{ $payment->status_text }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Date:</span>
                                <span class="font-medium">{{ $payment->formatted_payment_date }}</span>
                            </div>
                            @if($payment->transaction_id)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Transaction ID:</span>
                                <span class="font-medium">{{ $payment->transaction_id }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Student Information -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Student Information</h4>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="student-avatar">
                                    {{ strtoupper(substr($payment->student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $payment->student->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $payment->student->student_id }}</div>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium">{{ $payment->student->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phone:</span>
                                <span class="font-medium">{{ $payment->student->phone }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">University:</span>
                                <span class="font-medium">{{ $payment->student->university }}</span>
                            </div>
                        </div>

                        <!-- Booking Information -->
                        @if($payment->booking)
                        <h4 class="text-lg font-semibold text-gray-900 mt-6 mb-4">Booking Information</h4>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Room:</span>
                                <span class="font-medium">Room {{ $payment->booking->room->room_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Hostel:</span>
                                <span class="font-medium">{{ $payment->booking->room->hostel->name ?? 'No Hostel' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Check-in:</span>
                                <span class="font-medium">{{ $payment->booking->formatted_check_in }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Check-out:</span>
                                <span class="font-medium">{{ $payment->booking->formatted_check_out }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                @if($payment->description)
                <div class="mt-8">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Description</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700">{{ $payment->description }}</p>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="form-actions mt-8">
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Back to Payments
                    </a>
                    @if($payment->status === 'pending')
                    <form action="{{ route('admin.payments.mark-completed', $payment) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i>
                            Mark as Completed
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .student-avatar {
        width: 3rem;
        height: 3rem;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .payment-status {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-completed {
        background-color: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .status-pending {
        background-color: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    .status-failed {
        background-color: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-start;
        padding-top: 1rem;
        border-top: 1px solid var(--gray-200);
    }

    .grid {
        display: grid;
    }

    .grid-cols-1 { grid-template-columns: repeat(1, 1fr); }
    .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }

    @media (min-width: 768px) {
        .md\:grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
    }

    .gap-8 { gap: 2rem; }
    .max-w-4xl { max-width: 56rem; }
    .mx-auto { margin-left: auto; margin-right: auto; }
    .space-y-4 > * + * { margin-top: 1rem; }
</style>

<script>
function printReceipt(paymentId) {
    window.open(`/payments/${paymentId}/receipt`, '_blank');
}
</script>
@endsection