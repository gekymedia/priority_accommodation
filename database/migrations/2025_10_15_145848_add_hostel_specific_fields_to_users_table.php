<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add only the missing hostel-specific fields
            $table->string('hostel_name')->nullable()->after('phone');
            $table->string('hostel_type')->nullable()->after('hostel_name');
            $table->integer('capacity')->nullable()->after('hostel_type');
            $table->string('country')->nullable()->after('capacity');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['hostel_name', 'hostel_type', 'capacity', 'country']);
        });
    }
};