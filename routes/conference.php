<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubEventController;

Route::group(['middleware' => ['role:admin|super-admin|badges-office']], function () {
    //mains events
    Route::get('/events/conference',[SubEventController::class,'events'])->name('conference.main.events');
    //conference
    Route::get('/concurrent-events/{event}',[SubEventController::class,'index'])->name('concurrent.events');

    //delegates
    Route::get('/delegates/conference/{event}',[SubEventController::class,'show'])->name('event.conference.delegates');
});
