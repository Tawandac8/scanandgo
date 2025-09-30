<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BadgeController;


Route::group(['middleware' => ['role:admin|super-admin|badges-office']], function () {
    //get events
    Route::get('/events/badges',[BadgeController::class,'events'])->name('other.events.badges');
    //badges
    Route::get('/{event}/badges',[BadgeController::class,'index'])->name('badges.index');
    //add badge
    Route::post('/badge/add',[BadgeController::class,'store'])->name('badge.add');
    //show badge details and print
    Route::get('/other/badge/{badge}',[BadgeController::class,'show']);
    //print badge
    Route::get('/other/badge/print/{badge}',[BadgeController::class,'print']);
});