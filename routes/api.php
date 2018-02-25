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

Route::post('login', 'UsersController@login');

Route::group(['middleware' => ['jwt.auth']], function() {
    $except = ['except' => ['create', 'show', 'edit']];

    Route::get('context', 'ContextsController@index');

    Route::resource('educPlan/{educPlan}/course', 'CoursesController', $except);
    Route::resource('course/{course}/group', 'GroupsController', $except);
    Route::resource('educPlan', 'EducPlansController', $except);
});