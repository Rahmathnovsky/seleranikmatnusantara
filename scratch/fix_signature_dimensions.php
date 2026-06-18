<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$signaturePath = storage_path('app/public/brands/logos/shem-signature.png');

if (!file_exists($signaturePath)) {
    die("Error: Shem Signature logo file not found at $signaturePath\n");
}

// Load original image
$orig = imagecreatefrompng($signaturePath);
$origW = imagesx($orig);
$origH = imagesy($orig);

echo "Original Dimensions: {$origW}x{$origH}\n";

// Create 1024x1024 square canvas
$canvas = imagecreatetruecolor(1024, 1024);

// Fill with white background
$white = imagecolorallocate($canvas, 255, 255, 255);
imagefill($canvas, 0, 0, $white);

// Calculate centered position
// If we want it to fit perfectly, since original height is 1024, we copy it directly centered horizontally.
// We can also scale it down slightly if the logo circle is too close to the top/bottom.
// Let's copy it directly first:
$destW = $origW;
$destH = $origH;
$destX = (int)((1024 - $destW) / 2);
$destY = 0;

imagecopyresampled($canvas, $orig, $destX, $destY, 0, 0, $destW, $destH, $origW, $origH);

// Save back
imagepng($canvas, $signaturePath);

imagedestroy($canvas);
imagedestroy($orig);

echo "Successfully resized Shem Signature logo to 1024x1024.\n";
