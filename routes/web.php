<?php

Route::get('/', 'MainController@index')->name('main.index');
Route::get('/logout', 'MainController@destroy')->name('main.logout');
