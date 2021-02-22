<?php

    Route::group([],function (){
        Route::post('/search', ['Modules\Content\Controllers\ContentController', 'search'])->name('content.search');
        Route::post('/store/{id?}', ['Modules\Content\Controllers\ContentController', 'store'])->name('content.store');
        Route::match(['get','post'],'/edit/{id?}', ['Modules\Content\Controllers\ContentController', 'edit'])->name('content.edit');
        Route::post('/delete/{id?}', ['Modules\Content\Controllers\ContentController', 'delete'])->name('content.delete');
        Route::match(['post'],'/import', ['Modules\Content\Controllers\ContentController', 'import'])->name('content.import');
        Route::match(['post'],'/export', ['Modules\Content\Controllers\ContentController', 'export'])->name('content.export');
    });