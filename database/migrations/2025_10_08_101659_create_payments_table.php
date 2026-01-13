<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('receipt_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'bank_transfer', 'upi', 'card']);
            $table->enum('type', ['rent', 'security', 'maintenance', 'other']);
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->dateTime('payment_date'); // Use dateTime instead of date
            $table->text('description')->nullable();
            $table->string('transaction_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['payment_date', 'status']);
            $table->index(['student_id', 'status']);
            $table->index('receipt_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};