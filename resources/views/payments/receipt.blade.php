<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt - {{ $payment->receipt_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }
        .receipt-title {
            font-size: 20px;
            margin: 10px 0;
        }
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .detail-section {
            margin-bottom: 20px;
        }
        .detail-label {
            font-weight: bold;
            color: #666;
            margin-bottom: 5px;
        }
        .detail-value {
            font-size: 14px;
        }
        .amount-box {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .amount {
            font-size: 28px;
            font-weight: bold;
            color: #059669;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-failed { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">Priority Accommodations</div>
        <div style="color: #666; margin-bottom: 10px;">Hostel Management System</div>
        <div class="receipt-title">PAYMENT RECEIPT</div>
        <div style="color: #666;">Receipt #: {{ $payment->receipt_number }}</div>
    </div>

    <div class="details-grid">
        <div>
            <div class="detail-section">
                <div class="detail-label">PAYMENT INFORMATION</div>
                <div class="detail-value">Date: {{ $payment->formatted_payment_date }}</div>
                <div class="detail-value">Method: {{ $payment->payment_method_text }}</div>
                <div class="detail-value">Type: {{ $payment->type_text }}</div>
                <div class="detail-value">
                    Status: 
                    <span class="status-badge status-{{ $payment->status }}">
                        {{ $payment->status_text }}
                    </span>
                </div>
                @if($payment->transaction_id)
                <div class="detail-value">Transaction ID: {{ $payment->transaction_id }}</div>
                @endif
            </div>
        </div>
        
        <div>
            <div class="detail-section">
                <div class="detail-label">STUDENT INFORMATION</div>
                <div class="detail-value">Name: {{ $payment->student->name }}</div>
                <div class="detail-value">Student ID: {{ $payment->student->student_id }}</div>
                <div class="detail-value">Email: {{ $payment->student->email }}</div>
                <div class="detail-value">Phone: {{ $payment->student->phone }}</div>
            </div>

            @if($payment->booking)
            <div class="detail-section">
                <div class="detail-label">BOOKING INFORMATION</div>
                <div class="detail-value">Room: {{ $payment->booking->room->room_number }}</div>
                <div class="detail-value">Hostel: {{ $payment->booking->room->hostel->name ?? 'N/A' }}</div>
                <div class="detail-value">Period: {{ $payment->booking->formatted_check_in }} to {{ $payment->booking->formatted_check_out }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="amount-box">
        <div style="color: #666; margin-bottom: 10px;">AMOUNT PAID</div>
        <div class="amount">{{ $payment->formatted_amount }}</div>
    </div>

    @if($payment->description)
    <div class="detail-section">
        <div class="detail-label">DESCRIPTION</div>
        <div class="detail-value">{{ $payment->description }}</div>
    </div>
    @endif

    <div class="footer">
        <div>Thank you for your payment!</div>
        <div>Priority Accommodations - Making Student Living Better</div>
        <div>Generated on: {{ now()->format('M d, Y h:i A') }}</div>
        <div style="margin-top: 10px;">
            This is a computer-generated receipt. No signature required.
        </div>
    </div>
</body>
</html>