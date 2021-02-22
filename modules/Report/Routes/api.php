<?php
    Route::group(['prefix' => 'invoice'],function (){
        Route::post('/search', ['Modules\Report\Controllers\InvoiceController', 'search'])->name('invoice.search');
        Route::post('/store/{id?}', ['Modules\Report\Controllers\InvoiceController', 'store'])->name('invoice.store');
        Route::match(['get','post'],'/edit/{id?}', ['Modules\Report\Controllers\InvoiceController', 'edit'])->name('invoice.edit');
        Route::post('/delete/{id?}', ['Modules\Report\Controllers\InvoiceController', 'delete'])->name('invoice.delete');
        Route::match(['post'],'/export', ['Modules\Report\Controllers\InvoiceController', 'export'])->name('invoice.export');
        Route::match(['post'],'/import', ['Modules\Report\Controllers\InvoiceController', 'import'])->name('invoice.import');

    });