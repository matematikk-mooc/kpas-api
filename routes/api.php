<?php

# get schools api

use Illuminate\Support\Arr;

Route::post('/institution', 'LtiController@institution_type');

use Illuminate\Support\Facades\Artisan;

Route::get('/run_scheduler', function () {

    Artisan::call('schedule:run');
    return 'OK';
});

Route::group(['prefix' => 'nsr'], function () {
    # Route::get('/counties', 'SchoolsController@counties');
    Route::get('/counties', 'SkolerController@all_fylke');
    # Route::get('/communities/{countyId}', 'SchoolsController@communities');
    Route::get('/communities/{fylkesnr}', 'SkolerController@kommuner');
    #Route::get('/schools/{communityId}', 'SchoolsController@schools');
    Route::get('/schools/{kommunenr}', 'SkolerController@skoler');
});
Route::get('kindergartens', 'SkolerController@all_barnehage');
Route::get('kindergartens/{kommunenr}', 'SkolerController@barnehager');

//
//Route::get('counties', 'SkolerController@all_fylke');
//Route::get('communities', 'SkolerController@all_kommune');
//Route::get('communities/{fylkesnr}', 'SkolerController@kommuner');
//Route::get('schools', 'SkolerController@all_skole');
//Route::get('schools/{kommunenr}', 'SkolerController@skoler');

# post new data to school api
Route::post('counties', 'SkolerController@store_fylke');
Route::post('communities', 'SkolerController@store_kommune');
Route::post('schools', 'SkolerController@store_skole');
Route::post('kindergartens', 'SkolerController@store_barnehage');


Route::get('user_activity', 'EnrollmentActivityController@index');
Route::get('user_activity/{course_id}', 'EnrollmentActivityController@show');
Route::post('user_activity', 'EnrollmentActivityController@store');
Route::delete('user_activity/{course_id}', 'EnrollmentActivityController@delete');


Route::group(['prefix' => 'group'], function () {
    Route::get('/user', 'GroupController@index')->middleware('lti');
    Route::post('/user', 'GroupController@store')->middleware('auth.dataporten');
    Route::get('/{groupId}/category', 'GroupCategoryController@index');
    Route::post('/user/bulk', 'GroupController@bulkStore')->middleware('lti');
});

Route::group(['prefix' => 'statistics'], function () {
    Route::get('/{courseId}', 'StatisticsController@index');
    Route::get('/{courseId}/count', 'StatisticsController@courseCount');
    Route::get('/groupCategory/{categoryId}', 'StatisticsController@groupCategory');
    Route::get('/groupCategory/{categoryId}/count', 'StatisticsController@groupCategoryCount');
});

Route::group(['prefix' => 'enrollment', 'middleware' => 'lti'], function () {
    Route::get('/', 'EnrollmentController@index');
    Route::post('/', 'EnrollmentController@store');
});


Route::group(['prefix' => 'faculties', 'middleware' => 'lti'], function () {
    Route::get('/', 'FacultyController@index');
});

Route::group(['prefix' => 'command'], function () {
    Route::get('migrate', 'CommandController@migrate');
});
