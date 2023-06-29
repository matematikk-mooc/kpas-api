<?php

# get schools api
use App\Http\Controllers\MergeUserController;

Route::post('institution', 'Lti3Controller@institution')->middleware('lti');

Route::get('settings', 'Lti3Controller@kpas_settings')->middleware('lti');

Route::group(['prefix' => 'diploma'], function () {
    Route::get('/pdf', 'Lti3Controller@diplomaPdf')->middleware('lti');;
    Route::get('/logolist', 'DiplomaController@logolist');
});

Route::post('/run_scheduler', 'CommandController@run_scheduler')->middleware('token_auth');

Route::group(['prefix' => 'nsr'], function () {
    Route::get('/counties', 'SkolerController@all_fylke');
    Route::get('/schools', 'SkolerController@all_skole');
    Route::get('/counties/{fylkesnr}', 'SkolerController@fylke');
    Route::get('/communities/{kommunenr}', 'SkolerController@kommune');
    Route::get('/counties/{fylkesnr}/communities', 'SkolerController@kommuner');
    Route::get('/counties/{fylkesnr}/schools', 'SkolerController@skoler_by_county');
    Route::get('/communities/{kommunenr}/schools', 'SkolerController@skoler_by_community');
});

Route::get('kindergartens', 'SkolerController@all_barnehage');
Route::get('kindergartens/{kommunenr}', 'SkolerController@barnehager');

Route::get('user_activity', 'EnrollmentActivityController@index');
Route::get('user_activity/{course_id}', 'EnrollmentActivityController@show');
Route::post('user_activity', 'EnrollmentActivityController@store')->middleware('token_auth');

Route::group(['prefix' => 'group'], function () {
    Route::get('/user', 'GroupController@index')->middleware('lti');
    Route::post('/user', 'GroupController@store')->middleware('auth.dataporten');
    Route::get('/all', 'GroupController@getStoredGroups');
    Route::get('/{groupId}/category', 'GroupCategoryController@index');
    Route::post('/user/bulk', 'GroupController@bulkStore')->middleware('lti');
    Route::get('/{groupId}/count', 'GroupController@getStudentCount');
});

Route::group(['prefix' => 'course'], function () {
    Route::get('/{courseId}/groups', 'GroupController@getCourseGroups')->middleware('lti');
    Route::get('/{courseId}/category/{categoryId}/groups', 'GroupController@getCourseGroupsByCategory')->middleware('lti');
    Route::get('/{courseId}/modules', 'ModuleController@moduleStatistics');
    Route::get('/{courseId}/modules/count', 'ModuleController@moduleStatisticsCount');

});

Route::group(['prefix' => 'survey'], function() {
    Route::post('/create', 'SurveyController@create')->middleware('lti');
    Route::post('/{surveyId}/submission/create', 'SurveyController@createUserSubmission')->middleware('lti');
    Route::delete('/{surveyId}/submission/delete', 'SurveyController@deleteUserSubmission')->middleware('lti');
    Route::get('/{surveyId}/user/{userId}', 'SurveyController@getUserSubmission')->middleware('lti');
    Route::get('/course/{courseId}', 'SurveyController@getCourseSurveys')->middleware('lti');
});

Route::group(['prefix' => 'statistics'], function () {
    Route::get('/{courseId}', 'StatisticsController@index');
    Route::get('/{courseId}/count', 'StatisticsController@courseCount');
    Route::get('/{courseId}/user_activity', 'StatisticsController@userActivity');
    Route::get('/groupCategory/{categoryId}', 'StatisticsController@groupCategory');
    Route::get('/groupCategory/{categoryId}/count', 'StatisticsController@groupCategoryCount');
});

Route::group(['prefix' => 'vimeo'], function () {
    Route::get('/{vimeoId}', 'VimeoController@index');
    Route::get('/{vimeoId}/reset', 'VimeoController@reset');
});

Route::get('kpasinfo', 'KpasInfoController@index');

Route::group(['prefix' => 'enrollment', 'middleware' => 'lti'], function () {
    Route::get('/', 'EnrollmentController@index');
    Route::post('/', 'EnrollmentController@store');
});

Route::group(['prefix' => 'faculties', 'middleware' => 'lti'], function () {
    Route::get('/', 'FacultyController@index');
});

Route::prefix('user')->group(function () {
    Route::prefix('merge')->group(function () {
        Route::get('/token', [MergeUserController::class, 'createToken'])->middleware('lti');
        Route::get('/intersection', [MergeUserController::class, 'getCourseIntersection'])->middleware('lti');
        Route::get('/perform', [MergeUserController::class, 'mergeUser'])->middleware('lti');
    });
});


Route::get('course/{courseId}/pages', 'MatomoController@getMatomoData');
Route::get('user/{userId}/history', 'HistoryController@getUserHistoryData');
Route::get('user/{userId}/context/{contextId}/history', 'HistoryController@getUserContextHistoryData');
Route::get('context/{contextId}/history', 'HistoryController@getContextHistoryData');
Route::get('statistics/{courseId}', 'GroupEnrollmentController@getGroupEnrollmentCount');
Route::get('course/{courseId}/count', 'CourseController@getStudentCount');

Route::get('school/orgnr/{orgNr}', 'NxrController@getSchool');
Route::get('kindergarten/orgnr/{orgNr}', 'NxrController@getKindergarten');
Route::get('/modules/{moduleId}/per_date', 'ModuleController@moduleStatisticsPerDate');