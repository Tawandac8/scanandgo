<?php

use App\Http\Controllers\IndirectExhibitorController;
use App\Http\Controllers\IndirectExhibitorBadgeController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['role:admin|super-admin|badges-office']], function () {
    Route::get('/exhibitor/{exhibitor}/indirect', [IndirectExhibitorController::class, 'index'])->name('exhibitor.indirect.index');
    Route::get('/exhibitor/{exhibitor}/indirect/badges', [IndirectExhibitorBadgeController::class, 'index'])->name('exhibitor.indirect.badges.index');
    Route::get('/indirect-exhibitor/view-badge/{badge}', [IndirectExhibitorBadgeController::class, 'show']);
    Route::get('/indirect-exhibitor/print-badge/{badge}', [IndirectExhibitorBadgeController::class, 'print']);
    Route::get('/indirect-exhibitor/update-serial-number/{badge}', [IndirectExhibitorBadgeController::class, 'updateSerialNumber']);
});
