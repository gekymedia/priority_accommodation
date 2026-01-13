<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('room_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('hostel_id')->nullable()->constrained()->onDelete('set null');
            
            // Complaint details
            $table->string('subject');
            $table->text('description');
            $table->enum('category', [
                'maintenance',
                'noise',
                'security',
                'cleanliness',
                'utilities',
                'roommate',
                'other'
            ])->default('other');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            
            // Status tracking
            $table->enum('status', [
                'pending',
                'in_progress',
                'resolved',
                'closed',
                'rejected'
            ])->default('pending');
            
            // Admin response
            $table->text('admin_response')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->index('student_id');
            $table->index('status');
            $table->index('priority');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
