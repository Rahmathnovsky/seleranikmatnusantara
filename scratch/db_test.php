<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$jobs = DB::table('career_jobs')->get();
foreach ($jobs as $job) {
    echo "ID: {$job->id} | Title: {$job->title} | Brand ID: {$job->brand_id} | Location: {$job->location}\n";
}
