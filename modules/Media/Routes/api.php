<?php
    Route::group(['middleware' => 'auth:api'],function (){
        Route::post('/uploads', ['Modules\Media\Controllers\MediaController', 'store'])->name('media.uploads');
        Route::post('/getLists', ['Modules\Media\Controllers\MediaController', 'getLists'])->name('media.getLists');
        Route::post('/getFilesInfo', ['Modules\Media\Controllers\MediaController', 'getFilesInfo'])->name('media.getFilesInfo');
        Route::post('/removeFiles', ['Modules\Media\Controllers\MediaController', 'removeFiles'])->name('media.removeFiles');
        Route::get('/preview/{id?}/{size?}',['\Modules\Media\Controllers\MediaController','preview'])->name('media.preview');
    });

