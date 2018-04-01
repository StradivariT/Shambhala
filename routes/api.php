<?php

use Illuminate\Http\Request;

Route::post('login', 'UsersController@login');

Route::group(['middleware' => ['jwt.auth']], function() {
    $dependentExcept = ['except' => ['create', 'edit', 'update', 'destroy', 'show']];
    $independentExcept = ['except' => ['index', 'store', 'create', 'edit', 'destroy']];
    $exceptGroup = ['except' => ['index', 'store', 'create', 'edit', 'update', 'destroy']];
    $exceptStudent = ['except' => ['index', 'store', 'create', 'edit', 'destroy', 'show']];

    //Context routes. The PATCH one is for updating the sub-contexts, only there for managing each one of them.
    Route::get('context',             'ContextsController@index');
    Route::patch('context/{context}', 'ContextsController@update');

    //Routes for file managing
    Route::get('activity/{activity}/file',         'ActivitiesController@download');
    Route::get('group/{activity}/file/{fileType}', 'GroupsController@download');
    Route::post('group/{activity}/file',           'GroupsController@uploadFile');

    //Dependent resource routes
    Route::resource('student/{student}/activity', 'ActivitiesController', $dependentExcept);
    Route::resource('educPlan/{educPlan}/course', 'CoursesController', $dependentExcept);
    Route::resource('course/{course}/group',      'GroupsController', $dependentExcept);
    Route::resource('group/{group}/student',      'StudentsController', $dependentExcept);    
    Route::resource('educPlan',                   'EducPlansController', $dependentExcept);
    
    //Independent routes, plus some exceptions
    Route::resource('course',   'CoursesController', $independentExcept);
    Route::resource('activity', 'ActivitiesController', $independentExcept);
    Route::resource('group',    'GroupsController', $exceptGroup);
    Route::resource('student',  'StudentsController', $exceptStudent);
});