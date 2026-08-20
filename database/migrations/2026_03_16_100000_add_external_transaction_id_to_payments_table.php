<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'external_transaction_id')) {
                $table->string('external_transaction_id', 64)->nullable()->after('metadata')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'external_transaction_id')) {
                $table->dropIndex(['external_transaction_id']);
                $table->dropColumn('external_transaction_id');
            }
        });
    }
};
