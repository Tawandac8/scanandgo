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

    //delegate search
    Route::post('/delegates/search',[SubEventController::class,'search_delegates']);

    //export delegates
    Route::get('/delegates/export/{event}',[SubEventController::class,'export'])->name('delegates.export');

    //printed badges
    Route::get('/delegates/printed/{event}',[SubEventController::class,'printed'])->name('delegates.printed');
    //update serial number
    Route::get('/admin/delegate-badge/serial-number/{id}',[SubEventController::class,'updateSerialNumber'])->name('update.delegate.serial.number');
    //update badge name
    Route::post('/delegates/badge/update-name',[SubEventController::class,'updateBadgeName'])->name('update.delegate.badge.name');
});
