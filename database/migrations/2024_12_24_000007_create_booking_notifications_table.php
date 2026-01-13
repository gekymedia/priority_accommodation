<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_notifications', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('booking_request_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Recipient
            
            $table->enum('type', [
                'booking_request',       // New booking request for owner
                'payment_received',      // Payment confirmation
                'confirmation_required', // Owner needs to confirm
                'booking_confirmed',     // Booking confirmed
                'booking_rejected',      // Booking rejected
                'booking_timeout',       // Confirmation timed out
                'roommate_joined',       // New roommate in room
                'payment_transferred',   // Money transferred to hostel
                'reminder',              // General reminder
            ]);
            
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data
            
            // Delivery channels
            $table->boolean('send_push')->default(true);
            $table->boolean('send_sms')->default(false);
            $table->boolean('send_email')->default(true);
            
            // Delivery status
            $table->boolean('push_sent')->default(false);
            $table->boolean('sms_sent')->default(false);
            $table->boolean('email_sent')->default(false);
            $table->timestamp('push_sent_at')->nullable();
            $table->timestamp('sms_sent_at')->nullable();
            $table->timestamp('email_sent_at')->nullable();
            
            // Read status
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            
            // Action tracking
            $table->string('action_url')->nullable();
            $table->boolean('action_taken')->default(false);
            $table->timestamp('action_taken_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'is_read']);
            $table->index(['type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_notifications');
    }
};

