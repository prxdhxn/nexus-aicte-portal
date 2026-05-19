<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    echo "<h1>CRITICAL ERROR: vendor/autoload.php is missing!</h1><p>Vercel did not run 'composer install'.</p>";
    exit;
}

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    echo "<h1>Fatal Exception: " . get_class($e) . "</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

