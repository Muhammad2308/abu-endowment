<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupStorage extends Command
{
    protected $signature   = 'app:setup-storage';
    protected $description = 'Create required storage directories, fix permissions, and link public storage';

    public function handle(): int
    {
        $base = base_path();

        $dirs = [
            storage_path('app/public/projects/icons'),
            storage_path('app/public/projects/photos'),
            storage_path('app/livewire-tmp'),
            storage_path('framework/views'),
            storage_path('framework/cache/data'),
            storage_path('framework/sessions'),
            storage_path('logs'),
            base_path('bootstrap/cache'),
        ];

        $this->info('Creating directories...');
        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                if (mkdir($dir, 0775, true)) {
                    $this->line("  created: $dir");
                } else {
                    $this->error("  FAILED:  $dir");
                }
            } else {
                $this->line("  exists:  $dir");
            }
        }

        // Storage link
        $this->info('Linking public/storage...');
        $link   = public_path('storage');
        $target = storage_path('app/public');
        if (is_link($link)) {
            $this->line('  symlink already exists');
        } elseif (file_exists($link)) {
            $this->warn('  public/storage exists but is not a symlink — remove it manually then re-run');
        } else {
            $this->call('storage:link');
        }

        // Write-test
        $this->info('Write test...');
        $testFile = storage_path('app/public/projects/icons/.keep');
        if (file_put_contents($testFile, '') !== false) {
            $this->info('  Write test passed.');
        } else {
            $this->error('  Write test FAILED — check directory ownership/permissions (chown -R www-data:www-data storage bootstrap/cache)');
            return self::FAILURE;
        }

        // Clear caches
        $this->info('Clearing caches...');
        $this->call('config:clear');
        $this->call('cache:clear');
        $this->call('view:clear');
        $this->call('route:clear');

        $this->info('');
        $this->info('Done. If running on Linux, also run:');
        $this->line('  sudo chown -R www-data:www-data storage bootstrap/cache');
        $this->line('  sudo chmod -R 775 storage bootstrap/cache');

        return self::SUCCESS;
    }
}
