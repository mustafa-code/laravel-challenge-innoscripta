<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\GeneralController;
use Illuminate\Support\Facades\Route;

Route::middleware(['verify.signature'])->group(function () {
    Route::get('/news', [ArticleController::class, 'index']);
    Route::get('/search', [ArticleController::class, 'search']);
});

Route::get('cron-job', [GeneralController::class, 'index']);
