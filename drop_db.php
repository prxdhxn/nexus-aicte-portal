<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    DB::connection('mongodb')->getMongoClient()->selectDatabase(env('DB_DATABASE'))->drop();
    echo "Database dropped successfully.\n";
} catch (\Exception $e) {
    echo "Error dropping database: " . $e->getMessage() . "\n";
}
