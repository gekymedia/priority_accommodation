<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // Remove old columns that are duplicated
            if (Schema::hasColumn('students', 'year_level')) {
                $table->dropColumn('year_level');
            }
            
            if (Schema::hasColumn('students', 'emergency_contact')) {
                $table->dropColumn('emergency_contact');
            }
            
            if (Schema::hasColumn('students', 'emergency_phone')) {
                $table->dropColumn('emergency_phone');
            }
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            // Re-add columns if rolling back
            if (!Schema::hasColumn('students', 'year_level')) {
                $table->integer('year_level')->nullable();
            }
            
            if (!Schema::hasColumn('students', 'emergency_contact')) {
                $table->string('emergency_contact')->nullable();
            }
            
            if (!Schema::hasColumn('students', 'emergency_phone')) {
                $table->string('emergency_phone')->nullable();
            }
        });
    }
};