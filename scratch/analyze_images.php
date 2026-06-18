<?php
$brainDir = 'C:\Users\rahma\.gemini\antigravity-ide\brain\0e46c2ce-e858-45aa-9212-ca66177c269b';
$files = [
    'media__1781693425947.png',
    'media__1781693426021.png',
    'media__1781693426271.png',
    'media__1781693426337.png',
    'media__1781693426354.png',
];

foreach ($files as $file) {
    $path = $brainDir . DIRECTORY_SEPARATOR . $file;
    if (!file_exists($path)) {
        echo "File not found: $path\n";
        continue;
    }
    $size = filesize($path);
    $img = imagecreatefrompng($path);
    if (!$img) {
        echo "Could not load image: $file\n";
        continue;
    }
    $w = imagesx($img);
    $h = imagesy($img);

    // Sample pixels to calculate average color and detect dominant colors
    $rTotal = 0; $gTotal = 0; $bTotal = 0; $sampled = 0;
    $redPixels = 0; $goldPixels = 0; $brownPixels = 0; $orangePixels = 0;

    for ($x = 0; $x < $w; $x += 10) {
        for ($y = 0; $y < $h; $y += 10) {
            $rgb = imagecolorat($img, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;
            $alpha = ($rgb >> 24) & 0x7F;

            // Skip transparent or near-white background
            if ($alpha > 100 || ($r > 240 && $g > 240 && $b > 240)) {
                continue;
            }

            $rTotal += $r;
            $gTotal += $g;
            $bTotal += $b;
            $sampled++;

            // Detect colors
            if ($r > 180 && $g < 50 && $b < 50) {
                $redPixels++;
            }
            if ($r > 180 && $g > 140 && $b < 100) {
                // Gold / Orange
                if ($g > 160) $goldPixels++;
                else $orangePixels++;
            }
            if ($r > 80 && $r < 150 && $g > 50 && $g < 100 && $b < 50) {
                $brownPixels++;
            }
        }
    }

    $avgR = $sampled ? round($rTotal / $sampled) : 0;
    $avgG = $sampled ? round($gTotal / $sampled) : 0;
    $avgB = $sampled ? round($bTotal / $sampled) : 0;

    echo "File: $file | Size: " . round($size / 1024) . " KB | Res: {$w}x{$h}\n";
    echo "  Average Color: R:$avgR G:$avgG B:$avgB\n";
    echo "  Red pixels: $redPixels | Gold: $goldPixels | Orange: $orangePixels | Brown: $brownPixels\n\n";
}
