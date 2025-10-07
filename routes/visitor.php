<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisitorController;


Route::middleware('auth')->group(function () {
    //all events
    Route::get('/visitors/events',[VisitorController::class,'visitorsEvents'])->name('events.visitors');

    Route::get('/business-visitors/{event}',[VisitorController::class,'index'])->name('business.visitors');

    //visitor badge printing
    Route::post('/visitor/print/{id}',[VisitorController::class,'print'])->name('visitor.print');//ajax

    //add visitor
    Route::post('/visitor/add',[VisitorController::class,'addVisitor'])->name('visitor.add');

    //view visitor
    Route::post('/visitor/view/{id}',[VisitorController::class,'viewVisitor'])->name('visitor.view');//ajax

    //visitor search
    Route::post('/visitor/search',[VisitorController::class,'searchVisitor'])->name('visitor.search');

    //visitor name search
    Route::get('/visitor/name/search',[VisitorController::class,'searchVisitorName'])->name('visitor.name.search');

});

Route::group(['middleware' => ['role:admin|super-admin']], function () { 
    //delete all visitors of an event
    Route::get('/delete/event/visitors/{event}',[VisitorController::class,'deleteEventVisitors'])->name('delete.event.visitors');
});