<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Link to booking request
            $table->foreignId('booking_request_id')->nullable()->after('id')->constrained()->nullOnDelete();
            
            // Hostel relationship
            $table->foreignId('hostel_id')->nullable()->after('room_id')->constrained()->nullOnDelete();
            
            // User relationship (student account if registered)
            $table->foreignId('user_id')->nullable()->after('student_id')->constrained()->nullOnDelete();
            
            // Bed assignment for shared rooms
            $table->integer('bed_number')->nullable()->after('semesters');
            $table->integer('beds_booked')->default(1)->after('bed_number');
            
            // Academic period
            $table->string('academic_year')->nullable()->after('beds_booked');
            $table->string('semester_period')->nullable()->after('academic_year');
            
            // Pricing breakdown
            $table->decimal('base_price', 10, 2)->default(0)->after('total_amount');
            $table->decimal('commission_amount', 10, 2)->default(0)->after('base_price');
            $table->decimal('amount_to_hostel', 10, 2)->default(0)->after('commission_amount'); // Amount to transfer to hostel owner
            
            // Payment status
            $table->enum('payment_status', [
                'unpaid', 'partial', 'paid', 'refunded', 'transferred'
            ])->default('unpaid')->after('advance_paid');
            $table->decimal('amount_paid', 10, 2)->default(0)->after('payment_status');
            $table->string('payment_provider')->nullable()->after('amount_paid');
            $table->string('payment_reference')->nullable()->after('payment_provider');
            
            // Transfer to hostel owner
            $table->boolean('hostel_payment_transferred')->default(false)->after('payment_reference');
            $table->timestamp('hostel_payment_transferred_at')->nullable()->after('hostel_payment_transferred');
            $table->string('hostel_transfer_reference')->nullable()->after('hostel_payment_transferred_at');
            
            // Source tracking
            $table->enum('booking_source', ['platform', 'walk_in', 'phone', 'other'])->default('platform')->after('hostel_transfer_reference');
            
            // Confirmation
            $table->timestamp('confirmed_at')->nullable()->after('booking_source');
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['booking_request_id']);
            $table->dropForeign(['hostel_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['confirmed_by']);
            
            $table->dropColumn([
                'booking_request_id', 'hostel_id', 'user_id', 'bed_number', 'beds_booked',
                'academic_year', 'semester_period', 'base_price', 'commission_amount', 
                'amount_to_hostel', 'payment_status', 'amount_paid', 'payment_provider',
                'payment_reference', 'hostel_payment_transferred', 'hostel_payment_transferred_at',
                'hostel_transfer_reference', 'booking_source', 'confirmed_at', 'confirmed_by'
            ]);
        });
    }
};

