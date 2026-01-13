<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->date('check_in'); // Keep as date for simplicity
            $table->date('check_out'); // Keep as date for simplicity
            $table->integer('semesters')->default(1); // From first migration
            $table->decimal('total_amount', 10, 2);
            $table->decimal('advance_paid', 10, 2)->default(0); // From second migration
            $table->text('special_requirements')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'])->default('pending');
            $table->timestamp('actual_check_in')->nullable(); // From first migration
            $table->timestamp('actual_check_out')->nullable(); // From first migration
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index(['status', 'check_in']);
            $table->index(['room_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};