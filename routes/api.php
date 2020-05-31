<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([

    'prefix' => 'auth'

], function () {

    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

Route::group([
	'prefix' => 'course'
], function(){

	Route::post('/create','CourseController@createCourses');
	Route::get('/list','CourseController@listCourses');
	Route::post('/register','CourseController@courseRegistration');
    Route::get('/export/excel','CourseController@exportCoursesToExcel')->name('course.export.excel');

});
