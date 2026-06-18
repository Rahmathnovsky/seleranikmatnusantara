<?php
$brainDir = 'C:\Users\rahma\.gemini\antigravity-ide\brain\0e46c2ce-e858-45aa-9212-ca66177c269b';
$files = [
    'media__1781693425947.png',
    'media__1781693426021.png',
    'media__1781693426337.png',
];

foreach ($files as $file) {
    $path = $brainDir . DIRECTORY_SEPARATOR . $file;
    $img = imagecreatefrompng($path);
    if (!$img) continue;

    $w = imagesx($img);
    $h = imagesy($img);

    echo "=== ASCII Preview for $file ($w x $h) ===\n";
    
    // We want to sample the text area near the bottom.
    // For a 1024x1024 image, the text "SHEM SUSHI", "SHEM RAMEN", "GOKURO" is usually at the bottom.
    // Let's sample Y from 650 to 950, and X from 200 to 824.
    // We'll scale down to 80x25 characters.
    
    $targetW = 80;
    $targetH = 25;
    
    $startY = (int)($h * 0.60);
    $endY = (int)($h * 0.95);
    $startX = (int)($w * 0.15);
    $endX = (int)($w * 0.85);

    $stepX = ($endX - $startX) / $targetW;
    $stepY = ($endY - $startY) / $targetH;

    for ($yIdx = 0; $yIdx < $targetH; $yIdx++) {
        $y = (int)($startY + $yIdx * $stepY);
        for ($xIdx = 0; $xIdx < $targetW; $xIdx++) {
            $x = (int)($startX + $xIdx * $stepX);
            
            $rgb = imagecolorat($img, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;
            $alpha = ($rgb >> 24) & 0x7F;

            // If transparent or white, it's background
            if ($alpha > 100 || ($r > 220 && $g > 220 && $b > 220)) {
                echo " ";
            } else {
                echo "#";
            }
        }
        echo "\n";
    }
    echo "\n\n";
}
