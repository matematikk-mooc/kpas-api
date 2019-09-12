<?php

Route::post('/', 'LtiController@checkAuthorization')->name('lti.index');

Route::get('/', 'MainController@index')->name('main.index');
Route::get('/logout', 'MainController@logout')->name('main.logout');
Route::get('/page/logout', 'MainController@pageLogout')->name('main.pageLogout');
Route::get('/minegrupper', 'MainController@myGroups')->name('main.mygroups');

Route::post('/worker', 'WorkerController@store')->name('worker.store');
Route::get('/worker', 'WorkerController@index')->name('worker.index');
