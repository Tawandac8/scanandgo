<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExhibitorController;

Route::group(['middleware' => ['role:admin|super-admin|badges-office']], function () {
    //get events
    Route::get('/events',[ExhibitorController::class,'events'])->name('events.exhibitor.list');
    //exhibitor list
    Route::get('/{event}/exhibitors',[ExhibitorController::class,'index'])->name('exhibitors.index');

});
