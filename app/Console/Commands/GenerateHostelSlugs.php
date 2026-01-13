<?php

namespace App\Console\Commands;

use App\Models\Hostel;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateHostelSlugs extends Command
{
    protected $signature = 'hostels:generate-slugs';
    protected $description = 'Generate slugs for hostels that do not have one';

    public function handle()
    {
        $hostels = Hostel::whereNull('slug')->orWhere('slug', '')->get();
        
        $count = 0;
        foreach ($hostels as $hostel) {
            $slug = Str::slug($hostel->name);
            $originalSlug = $slug;
            $counter = 1;
            
            while (Hostel::where('slug', $slug)->where('id', '!=', $hostel->id)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }
            
            $hostel->slug = $slug;
            $hostel->save();
            $count++;
            
            $this->info("Generated slug '{$slug}' for hostel: {$hostel->name}");
        }
        
        $this->info("Done! Generated slugs for {$count} hostels.");
        
        return Command::SUCCESS;
    }
}

