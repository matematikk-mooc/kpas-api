<?php

Route::group(['prefix' => 'group'], function() {
    Route::post('/user', 'GroupController@addUser')->middleware('auth.dataporten');
    Route::get('/{groupId}/category', 'GroupController@getCategory');
});
