<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_occupants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            
            // Can be linked to a student in the system OR manually added
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            
            // For occupants not in the system (manually added by admin/hostel owner)
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('student_id_number')->nullable(); // Their student ID if known
            $table->string('profile_picture')->nullable();
            
            // Occupancy details
            $table->integer('bed_number')->nullable(); // Which bed in the room (1-6)
            $table->date('move_in_date')->nullable();
            $table->date('move_out_date')->nullable();
            $table->string('academic_year')->nullable();
            $table->string('semester')->nullable();
            
            // Status
            $table->enum('status', ['active', 'checked_out', 'reserved'])->default('active');
            $table->boolean('is_system_booking')->default(false); // True if booked through platform
            $table->boolean('visible_to_roommates')->default(true); // Show to other roommates
            
            // Added by
            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['room_id', 'status']);
            $table->index(['student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_occupants');
    }
};

