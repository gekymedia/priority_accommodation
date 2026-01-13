<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Add university column
            if (!Schema::hasColumn('students', 'university')) {
                $table->string('university')->nullable()->after('student_id');
            }
            
            // Add other missing columns from your Student model
            if (!Schema::hasColumn('students', 'course')) {
                $table->string('course')->nullable()->after('university');
            }
            
            if (!Schema::hasColumn('students', 'year_of_study')) {
                $table->integer('year_of_study')->nullable()->after('course');
            }
            
            if (!Schema::hasColumn('students', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('year_of_study');
            }
            
            if (!Schema::hasColumn('students', 'address')) {
                $table->text('address')->nullable()->after('date_of_birth');
            }
            
            if (!Schema::hasColumn('students', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable()->after('address');
            }
            
            if (!Schema::hasColumn('students', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            }
            
            if (!Schema::hasColumn('students', 'id_proof')) {
                $table->string('id_proof')->nullable()->after('emergency_contact_phone');
            }
            
            if (!Schema::hasColumn('students', 'photo')) {
                $table->string('photo')->nullable()->after('id_proof');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Remove the columns if rolling back
            $columns = [
                'university', 'course', 'year_of_study', 'date_of_birth', 
                'address', 'emergency_contact_name', 'emergency_contact_phone', 
                'id_proof', 'photo'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('students', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};