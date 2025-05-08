<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class CheckStorageSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-storage-setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if storage is set up correctly for profile images';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking storage setup for profile images...');
        
        // Check storage link
        $publicStoragePath = public_path('storage');
        if (!file_exists($publicStoragePath)) {
            $this->error('❌ Storage link not found at: ' . $publicStoragePath);
            $this->info('Creating storage link...');
            $this->call('storage:link');
        } else {
            $this->info('✅ Storage link exists at: ' . $publicStoragePath);
        }
        
        // Check profile_pictures directory
        $profilePicsDir = storage_path('app/public/profile_pictures');
        if (!File::exists($profilePicsDir)) {
            $this->error('❌ Profile pictures directory not found at: ' . $profilePicsDir);
            $this->info('Creating profile_pictures directory...');
            File::makeDirectory($profilePicsDir, 0755, true);
        } else {
            $this->info('✅ Profile pictures directory exists at: ' . $profilePicsDir);
        }
        
        // Check permissions
        $perms = substr(sprintf('%o', fileperms($profilePicsDir)), -4);
        $this->info('Current permissions on profile_pictures directory: ' . $perms);
        
        if (!is_writable($profilePicsDir)) {
            $this->error('❌ Profile pictures directory is not writable');
            if (function_exists('chmod')) {
                $this->info('Attempting to fix permissions...');
                chmod($profilePicsDir, 0755);
                $newPerms = substr(sprintf('%o', fileperms($profilePicsDir)), -4);
                $this->info('New permissions: ' . $newPerms);
            }
        } else {
            $this->info('✅ Profile pictures directory is writable');
        }
        
        // Check actual image files
        $this->info('Checking user profile images...');
        $users = User::whereNotNull('img')->get();
        $this->info('Found ' . $users->count() . ' users with profile images');
        
        $missing = 0;
        foreach ($users as $user) {
            $imagePath = storage_path('app/public/profile_pictures/' . $user->img);
            $publicImagePath = public_path('storage/profile_pictures/' . $user->img);
            
            if (!file_exists($imagePath)) {
                $this->error('❌ Image missing for user ' . $user->name . ' (ID: ' . $user->id . '): ' . $user->img);
                $missing++;
            } else {
                $this->line('✅ Image exists for user ' . $user->name . ': ' . $user->img);
            }
        }
        
        if ($missing > 0) {
            $this->error($missing . ' profile images are missing from storage');
        } else {
            $this->info('All profile images exist in storage');
        }
        
        // Test storage with a dummy file
        $this->info('Testing storage with a dummy file...');
        try {
            $testContent = 'Test file - ' . date('Y-m-d H:i:s');
            $fileName = 'test_' . time() . '.txt';
            $stored = Storage::disk('public')->put($fileName, $testContent);
            
            if ($stored) {
                $this->info('✅ Successfully wrote test file to public storage');
                
                // Check if file exists in storage
                if (Storage::disk('public')->exists($fileName)) {
                    $this->info('✅ Test file exists in storage');
                    
                    // Check if file is accessible via public URL
                    $publicPath = public_path('storage/' . $fileName);
                    if (file_exists($publicPath)) {
                        $this->info('✅ Test file exists in public path: ' . $publicPath);
                    } else {
                        $this->error('❌ Test file not found in public path: ' . $publicPath);
                    }
                    
                    // Clean up test file
                    Storage::disk('public')->delete($fileName);
                } else {
                    $this->error('❌ Test file not found in storage after writing');
                }
            } else {
                $this->error('❌ Failed to write test file to storage');
            }
        } catch (\Exception $e) {
            $this->error('❌ Error testing storage: ' . $e->getMessage());
        }
        
        $this->info('Storage setup check complete!');
        
        return Command::SUCCESS;
    }
} 