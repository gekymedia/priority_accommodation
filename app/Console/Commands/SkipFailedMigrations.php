<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SkipFailedMigrations extends Command
{
    protected $signature = 'migrate:skip-failed';
    protected $description = 'Mark failed migrations as run';

    public function handle()
    {
        $failedMigrations = [
            '2025_10_07_121941_add_available_to_rooms_table',
            // Add other failed migration file names here
        ];

        foreach ($failedMigrations as $migration) {
            if (!DB::table('migrations')->where('migration', $migration)->exists()) {
                DB::table('migrations')->insert(['migration' => $migration, 'batch' => 1]);
                $this->info("Marked {$migration} as run");
            }
        }

        $this->info('All failed migrations have been marked as run');
    }
}