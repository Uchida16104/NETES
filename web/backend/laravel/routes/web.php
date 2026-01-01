<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

const NETES_STATUS_FILE = '/tmp/netes/status.json';
const NETES_LOG_FILE    = '/tmp/netes/web.log';
const JAVA_SRC          = '../../../../gui/java/NetesGUI.java';

Route::get('/', function () {
    return view('index');
});

Route::post('/run-job', function(Request $request) {
    $lang = $request->input('language');

    $logFile = storage_path('logs/render.log');
    file_put_contents($logFile, "");

    $sampleLog = "[".date('H:i:s')."] Running $lang job...\n";
    file_put_contents($logFile, $sampleLog, FILE_APPEND);

    return response()->json(['status' => 'ok']);
});

Route::get('/logs', function () {
    $logFile = storage_path('logs/render.log');

    if (!file_exists($logFile) || filesize($logFile) === 0) {
        return "<pre class='text-sm bg-black p-3 rounded overflow-auto h-96'>No logs yet.</pre>";
    }

    $lines = file($logFile, FILE_IGNORE_NEW_LINES);
    $lastLines = array_slice($lines, -80);

    return "<pre class='text-sm bg-black p-3 rounded overflow-auto h-96'>"
        . implode("\n", $lastLines)
        . "</pre>";
});

Route::get('/status', function () {

    if (!file_exists(NETES_STATUS_FILE)) {
        return response()->json([
            'state'     => 'unknown',
            'timestamp' => null
        ]);
    }

    $json = json_decode(file_get_contents(NETES_STATUS_FILE), true);

    if (!is_array($json)) {
        return response()->json([
            'state'     => 'error',
            'timestamp' => null
        ]);
    }

    if (isset($json['timestamp']) && is_numeric($json['timestamp'])) {
        $json['timestamp'] = date(
            'Y-m-d H:i:s',
            (int) $json['timestamp']
        );
    }

    return response()->json($json);
});

Route::get('/java', function () {

    $start = microtime(true);

    $cmd = sprintf(
        'java %s 2>&1',
        escapeshellarg(JAVA_SRC)
    );

    $output  = trim(shell_exec($cmd));
    $elapsed = round((microtime(true) - $start) * 1000, 2);

    $line = sprintf(
        "[%s] /java %sms %s\n",
        date('Y-m-d H:i:s'),
        $elapsed,
        $output === '' ? '[no output]' : $output
    );

    file_put_contents(NETES_LOG_FILE, $line, FILE_APPEND | LOCK_EX);

    return response($output, 200, ['Content-Type' => 'text/plain']);
});


Route::post('/render-webhook', function (Request $request) {
    $log = $request->input('log');
    if ($log) {
        file_put_contents(storage_path('logs/render.log'), $log . "\n", FILE_APPEND);
    }
    return response()->json(['status' => 'ok']);
});
