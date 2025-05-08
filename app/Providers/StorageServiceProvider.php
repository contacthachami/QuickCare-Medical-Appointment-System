<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class StorageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Check if we're in console (for artisan commands)
        if ($this->app->runningInConsole()) {
            return;
        }
        
        // Ensure the public storage link exists
        $publicStoragePath = public_path('storage');
        
        if (!file_exists($publicStoragePath)) {
            try {
                // Create symlink in a more direct way if possible
                if (function_exists('symlink')) {
                    symlink(storage_path('app/public'), $publicStoragePath);
                } else {
                    // Try to use artisan command
                    \Illuminate\Support\Facades\Artisan::call('storage:link');
                }
            } catch (\Exception $e) {
                // Log error but don't crash
                \Illuminate\Support\Facades\Log::error('Failed to create storage link: ' . $e->getMessage());
            }
        }
        
        // Ensure profile_pictures directory exists
        $profilePicsDir = storage_path('app/public/profile_pictures');
        if (!File::exists($profilePicsDir)) {
            try {
                File::makeDirectory($profilePicsDir, 0755, true);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to create profile_pictures directory: ' . $e->getMessage());
            }
        }
    }
} 