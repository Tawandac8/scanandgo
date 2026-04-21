<?php

use App\Http\Controllers\APIController;
use Illuminate\Support\Facades\Route;

//api prefix
Route::prefix('v1')->group(function () {
    Route::get('/exhibitor-badges', [APIController::class, 'exhibitorBadges']);
    Route::get('/delegate-badges/{event}', [APIController::class, 'delegateBadges']);
    Route::get('/visitor-badges/{event}', [APIController::class, 'visitorBadges']);
});