<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hostels', function (Blueprint $table) {
            // Owner relationship (hostel owner/manager who has agreement with platform)
            $table->foreignId('owner_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            
            // Media
            $table->json('images')->nullable()->after('description');
            $table->json('videos')->nullable()->after('images');
            $table->string('cover_image')->nullable()->after('videos');
            
            // Location
            $table->decimal('latitude', 10, 8)->nullable()->after('address');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('google_maps_url')->nullable()->after('longitude');
            
            // Distance to campus
            $table->integer('walking_time_minutes')->nullable()->after('google_maps_url'); // Walking time to campus
            $table->integer('driving_time_minutes')->nullable()->after('walking_time_minutes'); // Driving time to campus
            $table->decimal('distance_km', 5, 2)->nullable()->after('driving_time_minutes'); // Distance in km
            
            // Partnership & Commission
            $table->boolean('has_partnership_agreement')->default(false)->after('is_active');
            $table->date('partnership_start_date')->nullable()->after('has_partnership_agreement');
            $table->date('partnership_end_date')->nullable()->after('partnership_start_date');
            $table->decimal('commission_percentage', 5, 2)->default(10.00)->after('partnership_end_date'); // Platform commission %
            
            // Additional info
            $table->string('hostel_type')->default('mixed')->after('commission_percentage'); // boys, girls, mixed
            $table->integer('total_capacity')->default(0)->after('hostel_type');
            $table->text('rules')->nullable()->after('total_capacity');
            $table->json('nearby_landmarks')->nullable()->after('rules');
        });
    }

    public function down(): void
    {
        Schema::table('hostels', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
            $table->dropColumn([
                'owner_id', 'images', 'videos', 'cover_image',
                'latitude', 'longitude', 'google_maps_url',
                'walking_time_minutes', 'driving_time_minutes', 'distance_km',
                'has_partnership_agreement', 'partnership_start_date', 'partnership_end_date',
                'commission_percentage', 'hostel_type', 'total_capacity', 'rules', 'nearby_landmarks'
            ]);
        });
    }
};

