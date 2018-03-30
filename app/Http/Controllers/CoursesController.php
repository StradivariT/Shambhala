<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \Validator;

use App\Helpers\Utils;

use App\Course;

class CoursesController extends Controller {
    public function index($educPlanId) {
        $courses = Course::where('educ_plan_id', '=', $educPlanId)->get();

        if($courses->isEmpty())
            return response()->json('Courses not found for educ plan: ' . $educPlanId, 404);

        return response()->json($courses, 200);
    }

    public function store(Request $request, $educPlanId) {
        $newCourseName = $request->input('newResource');

        if(!Utils::isUniqueName($newCourseName, 'courses'))
            return response()->json('Course already exists', 400);

        try {
            $newCourse = new Course;
            $newCourse->name = $newCourseName;
            $newCourse->educ_plan_id = $educPlanId;
            $newCourse->save();
        } catch(Exception $error) {
            //TODO: Log $error
            return response()->json('Unexpected course error', 500);
        }

        return response()->json($newCourse, 200);
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

    public function updateName($newName, $id) {
        $course = Course::where('id', $id);

        $data = ['name' => $newName];

        try {
            $course->update($data);
        } catch(Exception $e) {
            return false;
        }

        return true;
    }
}