<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            
            // Hubtel Configuration
            $table->boolean('hubtel_enabled')->default(false);
            $table->string('hubtel_client_id')->nullable();
            $table->string('hubtel_client_secret')->nullable();
            $table->string('hubtel_merchant_account_number')->nullable();
            $table->string('hubtel_api_key')->nullable();
            $table->enum('hubtel_mode', ['sandbox', 'live'])->default('sandbox');
            
            // Paystack Configuration
            $table->boolean('paystack_enabled')->default(false);
            $table->string('paystack_public_key')->nullable();
            $table->string('paystack_secret_key')->nullable();
            $table->enum('paystack_mode', ['test', 'live'])->default('test');
            
            // General Settings
            $table->string('currency')->default('GHS');
            $table->string('currency_symbol')->default('₵');
            $table->decimal('minimum_deposit_percentage', 5, 2)->default(50.00); // Min % required to book
            $table->boolean('allow_partial_payment')->default(true);
            
            // Booking confirmation settings
            $table->integer('confirmation_timeout_minutes')->default(3); // Time hostel owner has to confirm
            $table->boolean('auto_confirm_if_no_response')->default(false); // Auto-confirm or auto-reject
            
            // Commission settings
            $table->decimal('default_commission_percentage', 5, 2)->default(10.00);
            
            // Notification settings
            $table->boolean('notify_admin_on_payment')->default(true);
            $table->boolean('notify_hostel_owner_on_booking')->default(true);
            $table->boolean('notify_student_on_confirmation')->default(true);
            
            // SMS Gateway for notifications
            $table->string('sms_gateway')->nullable(); // hubtel_sms, arkesel, etc.
            $table->string('sms_api_key')->nullable();
            $table->string('sms_sender_id')->nullable();
            
            $table->timestamps();
        });

        // Insert default settings
        DB::table('payment_settings')->insert([
            'hubtel_enabled' => false,
            'paystack_enabled' => false,
            'currency' => 'GHS',
            'currency_symbol' => '₵',
            'confirmation_timeout_minutes' => 3,
            'default_commission_percentage' => 10.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};

