<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Booking requests - temporary holding table during the 3-minute confirmation window
        Schema::create('booking_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('request_token')->unique(); // For tracking the request
            
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hostel_id')->constrained()->cascadeOnDelete();
            
            // Booking details
            $table->integer('beds_requested')->default(1);
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->string('academic_year');
            $table->string('semester');
            
            // Pricing snapshot (in case prices change)
            $table->decimal('base_price', 10, 2);
            $table->decimal('commission_amount', 10, 2);
            $table->decimal('total_amount', 10, 2);
            
            // Status
            $table->enum('status', [
                'pending_payment',      // Waiting for student to pay
                'payment_processing',   // Payment in progress
                'awaiting_confirmation', // Paid, waiting for hostel owner confirmation
                'confirmed',            // Hostel owner confirmed
                'rejected',             // Hostel owner rejected (room unavailable)
                'timeout',              // No response from hostel owner
                'cancelled',            // Student cancelled
                'expired'               // Request expired before payment
            ])->default('pending_payment');
            
            // Timing
            $table->timestamp('payment_initiated_at')->nullable();
            $table->timestamp('payment_completed_at')->nullable();
            $table->timestamp('confirmation_sent_at')->nullable(); // When we notified hostel owner
            $table->timestamp('confirmation_deadline')->nullable(); // 3 min from payment
            $table->timestamp('owner_responded_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // Overall request expiry
            
            // Owner response
            $table->text('rejection_reason')->nullable();
            $table->foreignId('responded_by')->nullable()->constrained('users')->nullOnDelete();
            
            // Payment reference
            $table->string('payment_reference')->nullable();
            $table->string('payment_provider')->nullable(); // hubtel or paystack
            $table->json('payment_response')->nullable();
            
            // Notification tracking
            $table->boolean('owner_notified')->default(false);
            $table->boolean('student_notified')->default(false);
            $table->boolean('admin_notified')->default(false);
            
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'confirmation_deadline']);
            $table->index(['student_id', 'status']);
            $table->index(['hostel_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_requests');
    }
};

