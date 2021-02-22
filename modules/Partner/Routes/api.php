<?php

    Route::group([],function (){
        Route::post('/search', ['Modules\Partner\Controllers\PartnerController', 'search'])->name('partner.search');
        Route::post('/store/{id?}', ['Modules\Partner\Controllers\PartnerController', 'store'])->name('partner.store');
        Route::match(['get','post'],'/edit/{id?}', ['Modules\Partner\Controllers\PartnerController', 'edit'])->name('partner.edit');
        Route::post('/delete/{id?}', ['Modules\Partner\Controllers\PartnerController', 'delete'])->name('partner.delete');
        Route::match(['post'],'/import', ['Modules\Partner\Controllers\PartnerController', 'import'])->name('partner.import');
        Route::match(['post'],'/export', ['Modules\Partner\Controllers\PartnerController', 'export'])->name('partner.export');


    });