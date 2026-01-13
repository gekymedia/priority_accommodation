<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hostel_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('room_number', 10)->unique();
            $table->enum('type', ['single', 'double', 'suite']);
            $table->integer('capacity');
            $table->decimal('price_per_semester', 10, 2);
            $table->text('description')->nullable();
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->boolean('available')->default(true);
            $table->string('video_url')->nullable();
            $table->json('photos')->nullable();
            $table->json('features')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rooms');
    }
};