<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::group(['middleware' => ['role:admin|super-admin']], function () { 
    //users
    Route::get('/admin/all-users',[UserController::class,'index'])->name('admin.all-users');

    Route::post('/admin/user/add',[UserController::class,'store'])->name('users.store');
 });