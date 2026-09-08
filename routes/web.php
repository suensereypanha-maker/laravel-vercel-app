<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Models\Category;
use Illuminate\Http\Request;

Route::get('/', function () {
    $dbConnected = true;
    $dbError = null;

    try {
        $categories = Category::latest()->get();
    } catch (\Throwable $e) {
        $categories = collect();
        $dbConnected = false;
        $dbError = $e->getMessage();
    }

    return view('welcome', compact('categories', 'dbConnected', 'dbError'));
});

// Redirect GET /categories to homepage
Route::get('/categories', function () {
    return redirect('/');
});

Route::post('/categories', function (Request $request) {
    $request->validate([
        'name' => 'required|max:255',
    ]);

    try {
        Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect('/')->with('success', 'Category created successfully in TiDB MySQL!');
    } catch (\Throwable $e) {
        return redirect('/')->with('error', 'Database Error: ' . $e->getMessage());
    }
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
