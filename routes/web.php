<?php

use Illuminate\Http\Request;

Route::get('/login', function () {
    return response()->json([
        'message' => 'Unauthenticated.'
    ], 401);
})->name('login');
