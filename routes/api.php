<?php

Route::group(['prefix' => 'group'], function() {
    Route::get('/user', 'GroupController@getUserGroups')->middleware('lti');
    Route::post('/user', 'GroupController@addUser')->middleware('auth.dataporten');
    Route::get('/{groupId}/category', 'GroupController@categories');
});

Route::group(['prefix' => 'nsr'], function() {
    Route::get('/counties', 'SchoolsController@counties');
    Route::get('/communities/{countyId}', 'SchoolsController@communities');
    Route::get('/schools/{communityId}', 'SchoolsController@schools');
});

Route::group(['prefix' => 'enrollment', 'middleware' => 'lti'], function() {
    Route::get('/', 'EnrollmentController@getEnrollments');
    Route::post('/enroll', 'EnrollmentController@enrollUser');
});
