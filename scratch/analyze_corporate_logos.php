<?php
$brainDir = 'C:\Users\rahma\.gemini\antigravity-ide\brain\0e46c2ce-e858-45aa-9212-ca66177c269b';
$files = [
    'media__1781694185680.png',
    'media__1781694185703.png',
];

foreach ($files as $file) {
    $path = $brainDir . DIRECTORY_SEPARATOR . $file;
    if (!file_exists($path)) {
        echo "File not found: $path\n";
        continue;
    }
    
    $img = imagecreatefrompng($path);
    if (!$img) {
        echo "Could not load image: $file\n";
        continue;
    }
    
    $w = imagesx($img);
    $h = imagesy($img);
    
    // Check some non-transparent pixels
    $whiteCount = 0;
    $blackCount = 0;
    
    for ($x = 0; $x < $w; $x += 5) {
        for ($y = 0; $y < $h; $y += 5) {
            $rgb = imagecolorat($img, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;
            $alpha = ($rgb >> 24) & 0x7F;
            
            // Skip transparent pixels
            if ($alpha > 100) continue;
            
            // If near white
            if ($r > 200 && $g > 200 && $b > 200) {
                $whiteCount++;
            }
            // If near black
            if ($r < 50 && $g < 50 && $b < 50) {
                $blackCount++;
            }
        }
    }
    
    echo "File: $file | Res: {$w}x{$h} | White Pixels: $whiteCount | Black Pixels: $blackCount\n";
    
    // Copy to public/images with descriptive name
    $publicDir = 'c:\laragon\www\seleranikmatnusantara\public\images';
    if ($blackCount > $whiteCount) {
        // This is the dark logo (black text)
        copy($path, $publicDir . DIRECTORY_SEPARATOR . 'logo-dark.png');
        echo "--> Copied as logo-dark.png (dark text/black logo)\n";
    } else {
        // This is the light logo (white text)
        copy($path, $publicDir . DIRECTORY_SEPARATOR . 'logo-light.png');
        echo "--> Copied as logo-light.png (light text/white logo)\n";
    }
}
