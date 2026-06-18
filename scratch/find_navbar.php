<?php
$lines = file('public/css/app.css');
foreach ($lines as $line => $content) {
    if (strpos(strtolower($content), 'hero') !== false) {
        echo ($line + 1) . ": " . trim($content) . "\n";
    }
}
