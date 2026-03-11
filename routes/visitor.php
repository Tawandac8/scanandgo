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

    //edit visitor
    Route::post('/visitor/edit/{id}',[VisitorController::class,'editVisitor'])->name('visitor.edit');

    //update visitor
    Route::post('/visitor/update/{id}',[VisitorController::class,'updateVisitor'])->name('visitor.update');

    //view visitor
    Route::post('/visitor/view/{id}',[VisitorController::class,'viewVisitor'])->name('visitor.view');//ajax

    //visitor search
    Route::post('/visitor/search',[VisitorController::class,'searchVisitor'])->name('visitor.search');

    //visitor name search
    Route::get('/visitor/name/search',[VisitorController::class,'searchVisitorName'])->name('visitor.name.search');

    //self-service portal
    Route::get('/self-service', [VisitorController::class, 'selfService'])->name('self-service');
    Route::post('/self-service/scan', [VisitorController::class, 'selfServiceScan'])->name('self-service.scan');

});

Route::group(['middleware' => ['role:admin|super-admin']], function () { 
    //delete all visitors of an event
    Route::get('/delete/event/visitors/{event}',[VisitorController::class,'deleteEventVisitors'])->name('delete.event.visitors');
});