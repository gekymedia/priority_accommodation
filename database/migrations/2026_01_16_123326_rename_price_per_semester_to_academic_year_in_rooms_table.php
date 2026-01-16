<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL/MariaDB
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE rooms CHANGE price_per_semester price_per_academic_year DECIMAL(10,2) NOT NULL');
        } else {
            // For PostgreSQL, SQLite, etc.
            Schema::table('rooms', function (Blueprint $table) {
                $table->renameColumn('price_per_semester', 'price_per_academic_year');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For MySQL/MariaDB
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE rooms CHANGE price_per_academic_year price_per_semester DECIMAL(10,2) NOT NULL');
        } else {
            // For PostgreSQL, SQLite, etc.
            Schema::table('rooms', function (Blueprint $table) {
                $table->renameColumn('price_per_academic_year', 'price_per_semester');
            });
        }
    }
};
