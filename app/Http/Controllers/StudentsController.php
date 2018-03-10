<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\Group;

class StudentsController extends Controller {
    public function index($id) {
        $group = Group::find($id);

        if(empty($group))
            return response()->json(['message' => 'Group not found, invalid ID'], 400);

        $students = Student::where('group_id', '=', $group->id)->get();

        if($students->isEmpty())
            return response()->json(['message' => 'Students not found for group: ' . $id . '.'], 404);

        return response()->json(['students' => $students], 200);
    }

    public function store($id, Request $request) {
        try {
            $newStudent = new Student;
            $newStudent->name = $request->input('newStudentName');
            $newStudent->number = $request->input('newStudentNumber');
            $newStudent->group_id = $id;
            $newStudent->save();
        } catch(Exception $e) {
            return response()->json(['message' => 'Unexpected student error', 'error' => $e], 500);
        }

        return response()->json(['newStudent' => $newStudent], 200);
    }
    
    public function show($id) {
        //
    }

    public function edit($id) {
        //
    }

    public function update(Request $request, $id) {
        //
    }

    public function destroy($id) {
        //
    }
}
