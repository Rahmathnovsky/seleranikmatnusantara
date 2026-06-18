<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

$brainDir = 'C:\Users\rahma\.gemini\antigravity-ide\brain\0e46c2ce-e858-45aa-9212-ca66177c269b';

// Ensure the storage directories exist
$targetSubDir = 'brands/logos';
Storage::disk('public')->makeDirectory($targetSubDir);
$targetDir = storage_path('app/public/' . $targetSubDir);

// Mapping of uploaded media files to brand slugs
$mapping = [
    'bakoel-bamboe' => 'media__1781693426271.png',
    'shem-ramen'    => 'media__1781693426337.png',
    'shem-sushi'    => 'media__1781693425947.png',
    'shem-signature'=> 'media__1781693426354.png',
    'gokuro'        => 'media__1781693426021.png',
];

// 1. Copy the 5 uploaded logos
foreach ($mapping as $slug => $fileName) {
    $srcPath = $brainDir . DIRECTORY_SEPARATOR . $fileName;
    $destFileName = $slug . '.png';
    $destPath = $targetDir . DIRECTORY_SEPARATOR . $destFileName;

    if (file_exists($srcPath)) {
        copy($srcPath, $destPath);
        echo "Copied $fileName to storage for brand '$slug'\n";
    } else {
        echo "Source file not found: $srcPath\n";
    }
}

// 2. Create the combined logo for 'shem-ramen-x-sushi'
$ramenPath = $targetDir . DIRECTORY_SEPARATOR . 'shem-ramen.png';
$sushiPath = $targetDir . DIRECTORY_SEPARATOR . 'shem-sushi.png';
$combinedPath = $targetDir . DIRECTORY_SEPARATOR . 'shem-ramen-x-sushi.png';

if (file_exists($ramenPath) && file_exists($sushiPath)) {
    // We'll create a 1024x1024 canvas
    $canvas = imagecreatetruecolor(1024, 1024);
    
    // Set background to white
    $white = imagecolorallocate($canvas, 255, 255, 255);
    imagefill($canvas, 0, 0, $white);

    // Load source images
    $ramenImg = imagecreatefrompng($ramenPath);
    $sushiImg = imagecreatefrompng($sushiPath);

    // We will place them side-by-side or vertically
    // Let's place them side-by-side: each logo scaled to 450x450, and centered vertically.
    // Left logo: X: 40, Y: 287
    // Right logo: X: 534, Y: 287
    // Middle: draw a clean 'x' or use text
    
    $logoSize = 440;
    
    // Copy and resize Shem Ramen to the left
    imagecopyresampled($canvas, $ramenImg, 40, 292, 0, 0, $logoSize, $logoSize, imagesx($ramenImg), imagesy($ramenImg));
    
    // Copy and resize Shem Sushi to the right
    imagecopyresampled($canvas, $sushiImg, 544, 292, 0, 0, $logoSize, $logoSize, imagesx($sushiImg), imagesy($sushiImg));

    // Optional: Draw a nice red 'x' in the center
    // Let's draw an 'x' using lines
    $red = imagecolorallocate($canvas, 237, 28, 36);
    // Draw thick 'X'
    for ($i = -4; $i <= 4; $i++) {
        imageline($canvas, 502 + $i, 492, 522 + $i, 532, $red);
        imageline($canvas, 522 + $i, 492, 502 + $i, 532, $red);
    }

    // Save the combined image
    imagepng($canvas, $combinedPath);
    
    // Clean up
    imagedestroy($canvas);
    imagedestroy($ramenImg);
    imagedestroy($sushiImg);
    
    echo "Generated combined logo for 'shem-ramen-x-sushi'\n";
} else {
    echo "Could not generate combined logo because source files were missing.\n";
}

// 3. Update database paths
$allSlugs = array_keys($mapping);
$allSlugs[] = 'shem-ramen-x-sushi';

foreach ($allSlugs as $slug) {
    $dbPath = $targetSubDir . '/' . $slug . '.png';
    $updated = DB::table('brands')
        ->where('slug', $slug)
        ->update([
            'logo' => $dbPath,
            'updated_at' => now(),
        ]);
    if ($updated) {
        echo "Updated database record for '$slug' with logo path: $dbPath\n";
    } else {
        echo "Failed to update database record for '$slug'\n";
    }
}
