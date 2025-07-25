<?php

use App\Http\Controllers\Api\TagController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('tags')->name('tags.')->middleware('permission:view tags')->group(function () {
        Route::get('/', [TagController::class, 'index'])->name('index');

        Route::middleware('permission:manage tags')->group(function () {
            Route::get('/tag-name-is-unique/{title}', [TagController::class, 'isUnique'])->name('isUnique');
            Route::post('/store', [TagController::class, 'store'])->name('store');
            Route::delete('/', [TagController::class, 'destroy'])->name('destroy');
            Route::post('/restores', [TagController::class, 'restores'])->name('restores');
        });


    });
});
