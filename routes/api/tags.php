<?php

use App\Http\Controllers\Api\TagController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:web')->group(function () {
    Route::prefix('tags')->name('tags.')->middleware('permission:view tags')->group(function () {
        Route::get('/', [TagController::class, 'index'])->name('index');
    });
    Route::prefix('tags')->name('tags.')->middleware('permission:manage tags')->group(function () {
        Route::post('/store', [TagController::class, 'store'])->name('store');
        Route::get('/tag-name-is-unique/{title}', [TagController::class, 'isUnique'])->name('isUnique');
    });
});
