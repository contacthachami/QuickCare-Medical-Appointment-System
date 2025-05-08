<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class EnsureStorageLinked extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ensure-storage-linked';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensures storage symlinks are properly established';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $publicStoragePath = public_path('storage');
        
        if (!file_exists($publicStoragePath)) {
            $this->info('Storage link not found. Creating it now...');
            $this->call('storage:link');
        } else {
            $this->info('Storage link already exists.');
        }
        
        // Create profile_pictures directory if it doesn't exist
        $profilePicsDir = storage_path('app/public/profile_pictures');
        if (!File::exists($profilePicsDir)) {
            File::makeDirectory($profilePicsDir, 0755, true);
            $this->info('Created profile_pictures directory.');
        } else {
            $this->info('profile_pictures directory already exists.');
        }
        
        // Ensure proper permissions
        if (function_exists('chmod')) {
            chmod($profilePicsDir, 0755);
            $this->info('Updated permissions on profile_pictures directory.');
        }
        
        $this->info('Storage configuration complete.');
        
        return Command::SUCCESS;
    }
} 