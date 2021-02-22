<?php
    Route::group([],function (){
        Route::post('/select2', ['Modules\Contract\Controllers\ContractController', 'select2'])->name('contract.select2');
        Route::post('/search', ['Modules\Contract\Controllers\ContractController', 'search'])->name('contract.search');
        Route::match(['get','post'],'/edit/{id?}', ['Modules\Contract\Controllers\ContractController', 'edit'])->name('contract.edit');
        Route::post('/store/{id?}', ['Modules\Contract\Controllers\ContractController', 'store'])->name('contract.store');
        Route::post('/delete/{id?}', ['Modules\Contract\Controllers\ContractController', 'delete'])->name('contract.delete');
        Route::match(['post'],'/export', ['Modules\Contract\Controllers\ContractController', 'export'])->name('contract.export');
        Route::match(['post'],'/import', ['Modules\Contract\Controllers\ContractController', 'import'])->name('contract.import');
    });
