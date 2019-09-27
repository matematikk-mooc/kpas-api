<?php

Route::post('/', 'LtiController@index')->name('lti.index');

Route::get('/cookiecheck', function () {
    return view('cookiecheck.cookiecheck');
});
Route::get('/cookiecheckcomplete', function () {
    return view('cookiecheck.cookiecheckcomplete');
});


Route::get('/', 'MainController@index')->name('main.index');
Route::get('/logout', 'MainController@logout')->name('main.logout');
Route::get('/page/logout', 'MainController@pageLogout')->name('main.pageLogout');
Route::get('/minegrupper', 'MainController@myGroups')->name('main.mygroups');

Route::post('/worker', 'WorkerController@store')->name('worker.store');
Route::get('/worker', 'WorkerController@index')->name('worker.index');
