<?php
$dir = __DIR__ . '/writable/logs/';
$files = glob($dir . '*.php');
if (empty($files)) { echo "No logs"; exit; }
usort($files, function($a, $b) {
    return filemtime($b) - filemtime($a);
});
$content = file_get_contents($files[0]);
$lines = explode("\n", $content);
$recent = array_slice($lines, -30);
echo implode("\n", $recent);
