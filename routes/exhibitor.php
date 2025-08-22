<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExhibitorController;

Route::group(['middleware' => ['role:admin|super-admin|badges-office']], function () { 
    //exhibitor list
    Route::get('/exhibitors',[ExhibitorController::class,'index'])->name('exhibitors.index');

});