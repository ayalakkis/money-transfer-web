<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'app' => 'Money Transfer API',
        'version' => '1.0.0',
        'endpoints' => '/api/*',
        'health' => '/api/health',
        'documentation' => 'See API_DOCUMENTATION.md'
    ]);
});
