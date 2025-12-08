<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Create user
    $user = \App\Models\User::create([
        'name' => 'Test Member',
        'email' => 'testmember@gmail.com',
        'password' => bcrypt('password123'),
        'role' => 'member'
    ]);
    
    echo "✓ User created successfully!\n";
    echo "  ID: " . $user->id . "\n";
    echo "  Name: " . $user->name . "\n";
    echo "  Email: " . $user->email . "\n";
    echo "  Role: " . $user->role . "\n\n";
    
    // Dispatch the welcome email job
    \App\Jobs\SendWelcomeEmail::dispatch($user);
    
    echo "✓ Welcome email job dispatched to Redis queue!\n\n";
    echo "Run: php artisan queue:work redis --tries=3 --timeout=60 --verbose\n";
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
