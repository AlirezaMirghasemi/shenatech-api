<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require_once __DIR__ . '/api/users.php';
require_once __DIR__ . '/api/auth.php';
require_once __DIR__ . '/api/roles.php';
require_once __DIR__ . '/api/permissions.php';
require_once __DIR__ . '/api/tags.php';


Route::fallback(function () {
    return response()->json(['message' => 'Not Found.'], 404);
});
