<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisitorController;


Route::middleware('auth')->group(function () {
    Route::get('/business-visitors',[VisitorController::class,'index'])->name('business.visitors');

    //visitor badge printing
    Route::post('/visitor/print/{id}',[VisitorController::class,'print'])->name('visitor.print');//ajax

    //add visitor
    Route::post('/visitor/add',[VisitorController::class,'addVisitor'])->name('visitor.add');

    //view visitor
    Route::post('/visitor/view/{id}',[VisitorController::class,'viewVisitor'])->name('visitor.view');//ajax

});