<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if columns already exist before adding
            if (!Schema::hasColumn('users', 'student_id_number')) {
                $table->string('student_id_number')->nullable();
            }
            if (!Schema::hasColumn('users', 'programme')) {
                $table->string('programme')->nullable();
            }
            if (!Schema::hasColumn('users', 'level')) {
                $table->string('level')->nullable();
            }
            if (!Schema::hasColumn('users', 'is_cug_verified')) {
                $table->boolean('is_cug_verified')->default(false);
            }
            if (!Schema::hasColumn('users', 'cug_verified_at')) {
                $table->timestamp('cug_verified_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'profile_complete')) {
                $table->boolean('profile_complete')->default(false);
            }
            if (!Schema::hasColumn('users', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable();
            }
            if (!Schema::hasColumn('users', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable();
            }
            if (!Schema::hasColumn('users', 'is_hostel_owner')) {
                $table->boolean('is_hostel_owner')->default(false);
            }
            if (!Schema::hasColumn('users', 'receive_booking_notifications')) {
                $table->boolean('receive_booking_notifications')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'student_id_number', 'programme', 'level', 'is_cug_verified', 'cug_verified_at',
                'profile_complete', 'emergency_contact_name', 'emergency_contact_phone',
                'is_hostel_owner', 'receive_booking_notifications'
            ]);
        });
    }
};

