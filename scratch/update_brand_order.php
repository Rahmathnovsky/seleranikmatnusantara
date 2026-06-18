<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$newOrder = [
    'shem-ramen'          => 1,
    'shem-sushi'          => 2,
    'shem-ramen-x-sushi'  => 3,
    'shem-signature'      => 4,
    'gokuro'              => 5,
    'bakoel-bamboe'       => 6,
];

foreach ($newOrder as $slug => $order) {
    $updated = DB::table('brands')
        ->where('slug', $slug)
        ->update(['sort_order' => $order, 'updated_at' => now()]);
        
    if ($updated) {
        echo "Updated brand '$slug' to sort_order = $order\n";
    } else {
        echo "Failed or no change for brand '$slug'\n";
    }
}

echo "Sorting completed.\n";
