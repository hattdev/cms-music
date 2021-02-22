<?php

    use Illuminate\Support\Facades\Route;
    Route::get('/update_db', 'HomeController@updateDatabase')->name('updateDatabase')->middleware('auth');
    Route::get('/{any}', 'HomeController@index')->where('any', '.*')->name('home')->middleware('auth');
