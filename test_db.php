<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $count = App\Models\User::count();
    echo "SUCCESS! User count: $count\n";
    
    $users = App\Models\User::all(['name', 'email', 'role']);
    foreach ($users as $user) {
        echo "  - {$user->name} ({$user->email}) [{$user->role}]\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Class: " . get_class($e) . "\n";
}
