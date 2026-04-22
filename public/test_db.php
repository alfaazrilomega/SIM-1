<?php
// Tampilkan semua error
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Load framework
define('FCPATH', realpath(__DIR__) . DIRECTORY_SEPARATOR);
chdir(__DIR__ . '/..');
$pathsConfig = 'app/Config/Paths.php';
require realpath($pathsConfig) ?: $pathsConfig;
$paths = new Config\Paths();
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
require realpath($bootstrap) ?: $bootstrap;

$db = \Config\Database::connect();
try {
    $rows = $db->query("SELECT * FROM import_log LIMIT 1")->getResultArray();
    echo "Query OK. Rows: " . count($rows);
} catch (\Throwable $e) {
    echo "Query Error: " . $e->getMessage();
}
