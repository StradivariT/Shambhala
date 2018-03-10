<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \Validator;
use App\Group;

class GroupsController extends Controller {
    public function index($id) {
        $groups = Group::where('course_id', '=', $id)->get();

        if($groups->isEmpty())
            return response()->json(['message' => 'Groups not found for course: ' . $id . '.'], 404);

        return response()->json(['resources' => $groups], 200);
    }

    public function store($id, Request $request) {
        $validator = Validator::make(
            ['name' => $request->input('newResource')], 
            ['name' => 'unique:groups']
        );

        if($validator->fails())
            return response()->json(['message' => 'Group already exists'], 400);

        try {
            $newGroup = new Group;
            $newGroup->name = $request->input('newResource');
            $newGroup->course_id = $id;
            $newGroup->save();
        } catch(Exception $e) {
            return response()->json(['message' => 'Unexpected group error', 'error' => $e], 500);
        }

        return response()->json(['newResource' => $newGroup], 200);
    }

    public function update(Request $request, $id) {
        //
    }

    public function destroy($id) {
        //
    }
}
