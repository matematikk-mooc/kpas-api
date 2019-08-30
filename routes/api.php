<?php

Route::group(['prefix' => 'group'], function() {
    Route::post('/user', 'GroupController@addUser')->middleware('auth.dataporten');
    Route::get('/{groupId}/category', 'GroupController@categories');
});

Route::group(['prefix' => 'nsr'], function() {
    Route::get('/counties', 'SchoolsController@counties');
    Route::get('/communities/{countyId}', 'SchoolsController@communities');
    Route::get('/schools/{communityId}', 'SchoolsController@schools');
});

Route::group(['prefix' => 'enrollment'], function() {
    Route::post('/enroll', 'EnrollmentController@enrollUser')->middleware('lti');
});
