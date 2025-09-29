<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExhibitorController;
use App\Http\Controllers\ExhibitorBadgeController;

Route::group(['middleware' => ['role:admin|super-admin|badges-office']], function () {
    //get events
    Route::get('/events',[ExhibitorController::class,'events'])->name('events.exhibitor.list');
    //exhibitor list
    Route::get('/{event}/exhibitors',[ExhibitorController::class,'index'])->name('exhibitors.index');
    //exhibitor badges
    Route::get('/{exhibitor}/badges',[ExhibitorBadgeController::class,'index'])->name('exhibitor.badges.index');
    //show badge details and print
    Route::get('/exhibitor/badge/{badge}',[ExhibitorBadgeController::class,'show']);
    //print badge
    Route::get('/exhibitor/badge/print/{badge}',[ExhibitorBadgeController::class,'print']);

    //add badge
    Route::post('/exhibitor/badge/add/{exhibitor}',[ExhibitorBadgeController::class,'store'])->name('exhibitor.badge.add');

});
