<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // If year_level exists but year_of_study doesn't, rename it
            if (Schema::hasColumn('students', 'year_level') && !Schema::hasColumn('students', 'year_of_study')) {
                $table->renameColumn('year_level', 'year_of_study');
            }
            
            // If emergency_contact exists but emergency_contact_name doesn't, rename it
            if (Schema::hasColumn('students', 'emergency_contact') && !Schema::hasColumn('students', 'emergency_contact_name')) {
                $table->renameColumn('emergency_contact', 'emergency_contact_name');
            }
            
            // If emergency_phone exists but emergency_contact_phone doesn't, rename it
            if (Schema::hasColumn('students', 'emergency_phone') && !Schema::hasColumn('students', 'emergency_contact_phone')) {
                $table->renameColumn('emergency_phone', 'emergency_contact_phone');
            }
            
            // Make sure all required columns exist with proper nullability
            if (!Schema::hasColumn('students', 'year_of_study')) {
                $table->integer('year_of_study')->nullable()->after('course');
            }
            
            if (!Schema::hasColumn('students', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable()->after('address');
            }
            
            if (!Schema::hasColumn('students', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            }
        });
    }

    public function down()
    {
        // You can optionally add rollback logic here
    }
};