<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubEventController;

Route::group(['middleware' => ['role:admin|super-admin']], function () { 
    //users
    Route::get('/admin/all-users',[UserController::class,'index'])->name('admin.all-users');
    //add user
    Route::post('/admin/user/add',[UserController::class,'store'])->name('users.store');
    //edit user
    Route::get('/admin/user/edit/{id}',[UserController::class,'edit'])->name('users.edit');
    //update user
    Route::post('/admin/user/update/{id}',[UserController::class,'update'])->name('users.update');
    //delete user
    Route::delete('/admin/user/delete/{id}',[UserController::class,'destroy'])->name('users.destroy');
    //delete sub event
    Route::get('/admin/sub-event/delete/{id}',[SubEventController::class,'destroy'])->name('delete.event.subevent');

    //delete delegates
    Route::get('/admin/sub-event-delegates/delete/{id}',[SubEventController::class,'destroyDelegates'])->name('delete.event.conference.delegates');
 });