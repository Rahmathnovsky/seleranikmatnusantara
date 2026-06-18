<?php

// Ensure writable paths exist for serverless environment
$tmpDirs = ['/tmp/views', '/tmp/cache', '/tmp/sessions', '/tmp/framework/views'];
foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Create SQLite database if it doesn't exist
$dbPath = '/tmp/database.sqlite';
$shouldSeed = false;
if (!file_exists($dbPath) || filesize($dbPath) === 0) {
    touch($dbPath);
    $shouldSeed = true;
}

require __DIR__.'/../vendor/autoload.php';

/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

if ($shouldSeed) {
    try {
        // Boot application kernel
        $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();

        // Run migrations and seeders
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
    } catch (\Exception $e) {
        error_log('Error seeding SQLite database: ' . $e->getMessage());
    }
}

$app->handleRequest(\Illuminate\Http\Request::capture());
