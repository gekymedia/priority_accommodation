<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action'); // created, updated, deleted
            $table->string('entity'); // Model name (Booking, Room, Student, etc.)
            $table->unsignedBigInteger('entity_id'); // ID of the affected record
            $table->text('description')->nullable(); // Additional context
            $table->json('old_values')->nullable(); // Previous values (for updates)
            $table->json('new_values')->nullable(); // New values (for updates)
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['entity', 'entity_id']);
            $table->index('action');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};