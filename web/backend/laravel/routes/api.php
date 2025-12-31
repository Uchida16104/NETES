<?php

use Illuminate\Support\Facades\Route;

Route::get('/status', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'NETES backend',
        'env' => app()->environment(),
        'time' => now()->toIso8601String(),
    ]);
});
