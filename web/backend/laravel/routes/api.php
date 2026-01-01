<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/status', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'NETES backend',
        'env' => app()->environment(),
        'time' => now()->toIso8601String(),
    ]);
});

Route::get('/python', function () {
    $output = shell_exec('python3 /app/web/backend/laravel/routes/core/adapters/auto.py 2>&1');
    return response()->json([
        'language' => 'Python',
        'output' => $output
    ]);
});

Route::get('/rust', function () {
    $output = shell_exec('rustc /app/web/backend/laravel/routes/core/engine/netes_engine.rs 2>&1 && /app/web/backend/laravel/routes/target/debug/netes-engine');
    return response()->json([
        'language' => 'Rust',
        'output' => $output
    ]);
});

Route::get('/java', function () {
    $output = shell_exec('javac /app/web/backend/laravel/routes/gui/java/NetesGUI.java 2>&1 && java /app/web/backend/laravel/routes/gui/java/NetesGUI 2>&1');
    return response()->json([
        'language' => 'Java',
        'output' => $output
    ]);
});

Route::options('{any}', function () {
    return response()->json([], 200, [
        'Access-Control-Allow-Origin' => 'https://netes.vercel.app',
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With',
        'Access-Control-Max-Age' => '86400'
    ]);
})->where('any', '.*');
