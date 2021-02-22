<?php
        Route::post('/search', ['Modules\User\Controllers\UserController', 'search'])->name('user.search');
        Route::match(['get','post'],'/edit/{id?}', ['Modules\User\Controllers\UserController', 'edit'])->name('user.edit');
        Route::post('/store/{id?}', ['Modules\User\Controllers\UserController', 'store'])->name('user.store');
        Route::post('/delete/{id?}', ['Modules\User\Controllers\UserController', 'delete'])->name('user.delete');
        Route::group(['prefix' => 'role'], function () {
            Route::post('/select2', ['Modules\User\Controllers\RoleController', 'select2'])->name('user.role.select2');
            Route::post('/search', ['Modules\User\Controllers\RoleController', 'search'])->name('user.role.search');
            Route::match(['get','post'],'/edit/{id?}', ['Modules\User\Controllers\RoleController', 'edit'])->name('user.role.edit');
            Route::post('/store/{id?}', ['Modules\User\Controllers\RoleController', 'store'])->name('user.role.store');
        });

    Route::group(['prefix' => 'permission'], function () {
        Route::post('/all', ['Modules\User\Controllers\PermissionController', 'all'])->name('user.permission.all');
    });
