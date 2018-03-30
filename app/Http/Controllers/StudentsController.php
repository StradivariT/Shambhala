<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Student;
use App\Group;

class StudentsController extends Controller {
    public function index($groupId) {
        $group = Group::find($groupId);

        if(empty($group))
            return response()->json('Group not found, invalid ID', 400);

        $students = Student::select('id', 'name', 'number')->where('group_id', '=', $group->id)->orderBy('number', 'ASC')->get();

        if($students->isEmpty())
            return response()->json('Students not found for group: ' . $groupId, 404);

        return response()->json($students, 200);
    }

    public function store(Request $request, $groupId) {
        $newStudentInfo = $request->except(['token']);

        try {
            $newStudent = new Student;

            $newStudent->group_id = $groupId;
            $newStudent->name = $newStudentInfo['name'];
            $newStudent->number = $newStudentInfo['number'];

            $newStudent->save();

            $newStudentInfo['id'] = $newStudent->id;
        } catch(Exception $error) {
            //TODO: Log $error.
            return response()->json('Unexpected student error', 500);
        }

        return response()->json($newStudentInfo, 200);
    }

    public function update(Request $request, $studentId) {
        $data = $request->except(['token']);

        try {
            Student::where('id', $studentId)->update($data);
        } catch(Exception $error) {
            //TODO: Log $error
            return response()->json('Unexpected student error', 500);
        }

        return response()->json(['name' => $data['name'], 'number' => $data['number']], 200);
    }
}
