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

Route::get('/python', function () {
    return shell_exec('python3 ' . base_path('core/adapters/auto.py') . ' 2>&1');
});

Route::get('/rust', function () {
    return shell_exec(base_path('core/engine/netes_engine') . ' 2>&1');
});

Route::get('/java', function () {
    return shell_exec('java -cp ' . base_path('gui/java') . ' NetesGUI 2>&1');
});
