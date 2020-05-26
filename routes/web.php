<?php
Route::post('/lti3', 'Lti3Controller@index')->name('Lti3.index');
Route::post('/launch', 'Lti3Controller@launch')->name('Lti3.launch');

Route::post('/', 'LtiController@index')->name('lti.index');

Route::group(['prefix' => 'statistics'], function() {
    Route::get('/{courseId}', 'StatisticsController@webindex');
});

Route::view('/cookiecheck', 'cookiecheck.cookiecheck');
Route::view('/cookiecheckcomplete', 'cookiecheck.cookiecheckcomplete');

Route::get('/', 'MainController@index')->name('main.index');
Route::get('/logout', 'MainController@logout')->name('main.logout');
Route::get('/page/logout', 'MainController@pageLogout')->name('main.pageLogout');
Route::get('/minegrupper', 'MainController@myGroups')->name('main.mygroups');

Route::post('/worker', 'WorkerController@store')->name('worker.store');
Route::get('/worker', 'WorkerController@index')->name('worker.index');
