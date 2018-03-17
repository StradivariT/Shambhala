<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;

class ActivitiesController extends Controller {
    public function index($id) {   
        $activities = Activity::select('id', 'name')->where('student_id', '=', $id)->get();

        if($activities->isEmpty())
            return response()->json(['message' => 'Activities not found for student: ' . $id . '.'], 404);

        return response()->json(['activities' => $activities], 200);
    }

    public function store(Request $request, $id) {
        echo "id: ". $id;
        echo '<br>';
        print_r($request);
    }

    public function show($id) {
        //
    }

    public function update(Request $request, $id) {
        //
    }

    public function destroy($id) {
        //
    }
}
