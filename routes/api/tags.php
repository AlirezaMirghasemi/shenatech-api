<?php

use App\Http\Controllers\Api\TagController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:web')->group(function () {
    Route::prefix('tags')->name('tags.')->middleware('permission:view tags')->group(function () {
        Route::get('/', [TagController::class, 'index'])->name('index');


 });
});
