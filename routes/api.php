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
    $contextExcept = ['except' => ['create', 'edit', 'update', 'destroy', 'show']];
    $except = ['except' => ['index', 'store', 'create']];

    Route::get('context', 'ContextsController@index');

    Route::resource('educPlan/{educPlan}/course', 'CoursesController', $contextExcept);
    Route::resource('course/{course}/group', 'GroupsController', $contextExcept);
    Route::resource('group/{group}/student', 'StudentsController', $contextExcept);    
    Route::resource('educPlan', 'EducPlansController', $contextExcept);
    
    Route::resource('educPlan', 'EducPlansController', $except);
    Route::resource('course', 'CoursesController', $except);
    Route::resource('group', 'GroupsController', $except);
});