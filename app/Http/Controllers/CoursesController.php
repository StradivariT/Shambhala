<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \Validator;
use App\Course;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $courses = Course::where('educ_plan_id', '=', $id)->get();

        if($courses->isEmpty())
            return response()->json(['message' => 'Courses not found for educ plan: ' . $id . '.'], 404);

        return response()->json(['resources' => $courses], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id, Request $request)
    {
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
