<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ReportController;

Route::group(['middleware' => ['role:admin|super-admin']], function () {
    Route::get('/reports/events',[ReportController::class,'events'])->name('reports.events');
    Route::get('/reports/{event}/export-all-badges',[ReportController::class,'exportAllBadges'])->name('reports.export_all_badges');
    Route::get('/reports/{event}',[ReportController::class,'report'])->name('reports.report');
});