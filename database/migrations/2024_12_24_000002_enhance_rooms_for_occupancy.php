<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Ensure capacity supports 1-6 persons
            // capacity already exists, but we'll add more fields
            
            // Occupancy tracking
            $table->integer('current_occupants')->default(0)->after('capacity');
            $table->integer('beds_available')->default(0)->after('current_occupants');
            
            // Pricing with commission
            $table->decimal('base_price', 10, 2)->default(0)->after('price_per_semester'); // Original hostel price
            $table->decimal('commission_amount', 10, 2)->default(0)->after('base_price'); // Platform commission
            // price_per_semester will be the final price (base + commission)
            
            // Room type for bed arrangement
            $table->string('bed_type')->default('single')->after('type'); // single, bunk, double
            $table->integer('beds_count')->default(1)->after('bed_type');
            
            // Booking settings
            $table->boolean('allow_partial_booking')->default(true)->after('beds_count'); // Allow booking individual beds
            $table->boolean('instant_booking')->default(false)->after('allow_partial_booking'); // Skip owner confirmation
            
            // Availability period
            $table->string('academic_year')->nullable()->after('instant_booking');
            $table->string('semester')->nullable()->after('academic_year');
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn([
                'current_occupants', 'beds_available', 'base_price', 'commission_amount',
                'bed_type', 'beds_count', 'allow_partial_booking', 'instant_booking',
                'academic_year', 'semester'
            ]);
        });
    }
};

