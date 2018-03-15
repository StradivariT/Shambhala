<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \Validator;
use App\Course;

class CoursesController extends Controller {
    public function index($id) {
        $courses = Course::where('educ_plan_id', '=', $id)->get();

        if($courses->isEmpty())
            return response()->json(['message' => 'Courses not found for educ plan: ' . $id . '.'], 404);

        return response()->json(['resources' => $courses], 200);
    }

    public function store($id, Request $request) {
        $validator = Validator::make(
            ['name' => $request->input('newResource')], 
            ['name' => 'unique:courses']
        );

        if($validator->fails())
            return response()->json(['message' => 'Course already exists'], 400);

        try {
            $newCourse = new Course;
            $newCourse->name = $request->input('newResource');
            $newCourse->educ_plan_id = $id;
            $newCourse->save();
        } catch(Exception $e) {
            return response()->json(['message' => 'Unexpected course error', 'error' => $e], 500);
        }

        return response()->json(['newResource' => $newCourse], 200);
    }

    public function show($id) {
        $course = Course::find($id);

        if(empty($course))
            return response()->json(['message' => 'Course not found, invalid ID'], 400);

        if(empty($course->information))
            return response()->json(['message' => 'Information for course not found'], 404);

        return response()->json(['information' => $course->information], 200);
    }

    public function update(Request $request, $id) {
        $data = $request->except(['token']);

        try {
            Course::where('id', $id)->update($data);
        } catch(Exception $e) {
            return response()->json(['message' => 'Unexpected course error', 'error' => $e], 500);
        }

        return response()->json(['information' => $data['information']], 200);
    }

    public function destroy($id) {
        //
    }
}