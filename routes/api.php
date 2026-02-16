<?php

use App\Http\Controllers\APIController;
use Illuminate\Support\Facades\Route;

//api prefix
Route::prefix('api/v1')->group(function () {
    Route::get('/exhibitor-badges', [APIController::class, 'exhibitorBadges']);
});