<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \Validator;

use App\Helpers\Utils;

use App\Course;

class CoursesController extends Controller {
    public function index($educPlanId) {
        $courses = Course::select('id', 'name')->where('educ_plan_id', '=', $educPlanId)->get();

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

        $newCourseResponse = [
            'id'   => $newCourse->id,
            'name' => $newCourseName
        ];

        return response()->json($newCourseResponse, 200);
    }

    public function show($courseId) {
        $course = Course::find($courseId);

        if(empty($course))
            return response()->json('Course not found, invalid ID', 400);

        if(empty($course->information))
            return response()->json('Information for course not found', 404);

        return response()->json($course->information, 200);
    }

    public function update(Request $request, $courseId) {
        $updatedCourseInfo = $request->except(['token']);

        try {
            Course::find($courseId)->update($updatedCourseInfo);
        } catch(Exception $error) {
            //TODO: Log $error
            return response()->json('Unexpected course error', 500);
        }

        return response()->json($updatedCourseInfo, 200);
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