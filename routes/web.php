<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});

// Secure endpoint to run database migrations on Vercel
Route::get('/migrate', function () {
    $token = request('token');
    $secret = env('MIGRATE_SECRET', 'panha2026');

    if ($token !== $secret) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized: Invalid token. Pass ?token=panha2026',
        ], 403);
    }

    try {
        Artisan::call('migrate', ['--force' => true]);
        $output = Artisan::output();

        return response()->json([
            'status' => 'success',
            'message' => 'Migrations executed successfully!',
            'output' => explode("\n", trim($output)),
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
});
