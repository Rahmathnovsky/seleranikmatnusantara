<?php
$logoPath = 'c:\laragon\www\seleranikmatnusantara\public\images\logo-dark.png';
$faviconPath = 'c:\laragon\www\seleranikmatnusantara\public\favicon.png';

if (!file_exists($logoPath)) {
    die("Error: Logo not found.\n");
}

$img = imagecreatefrompng($logoPath);
$w = imagesx($img);
$h = imagesy($img);

// We want to find the bounding box of the letter "S" on the left.
// Let's scan from X=0 to X=350 to find the boundaries of the "S".
// Let's find the min/max X and Y where pixels are black (non-transparent).
$minX = $w; $maxX = 0;
$minY = $h; $maxY = 0;

for ($x = 0; $x < 300; $x++) {
    for ($y = 0; $y < $h; $y++) {
        $rgb = imagecolorat($img, $x, $y);
        $alpha = ($rgb >> 24) & 0x7F;
        if ($alpha < 100) { // Solid pixel
            if ($x < $minX) $minX = $x;
            if ($x > $maxX) $maxX = $x;
            if ($y < $minY) $minY = $y;
            if ($y > $maxY) $maxY = $y;
        }
    }
}

echo "Detected S bounding box: X: $minX to $maxX, Y: $minY to $maxY\n";

// Let's crop a square area. We want it to be square and have some padding.
$sW = $maxX - $minX;
$sH = $maxY - $minY;
$size = max($sW, $sH);

// Create a square canvas of size + padding
$padding = 30;
$favSize = $size + $padding * 2;

$canvas = imagecreatetruecolor($favSize, $favSize);
// Make background transparent
imagesavealpha($canvas, true);
$trans = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
imagefill($canvas, 0, 0, $trans);

// Center the cropped S inside the canvas
$destX = $padding + (int)(($size - $sW) / 2);
$destY = $padding + (int)(($size - $sH) / 2);

imagecopy($canvas, $img, $destX, $destY, $minX, $minY, $sW, $sH);

// Save as 64x64 PNG favicon
$finalFavicon = imagecreatetruecolor(64, 64);
imagesavealpha($finalFavicon, true);
imagefill($finalFavicon, 0, 0, $trans);
imagecopyresampled($finalFavicon, $canvas, 0, 0, 0, 0, 64, 64, $favSize, $favSize);

imagepng($finalFavicon, $faviconPath);

imagedestroy($img);
imagedestroy($canvas);
imagedestroy($finalFavicon);

echo "Successfully created cropped S favicon at: $faviconPath\n";
