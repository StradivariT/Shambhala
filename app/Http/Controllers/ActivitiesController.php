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
        $activityRequestFile = $request->file('newActivityFile');

        $activityFileName = $activityRequestFile->getClientOriginalName();
        $activityFileExtension = $activityRequestFile->getClientOriginalExtension();

        $activityStorageName = $this->random_string(50) . '.' . $activityFileExtension;

        try {
            $activityRequestFile->storeAs('public/activityFiles/' . $id, $activityStorageName);

            $newActivity = new Activity;
            $newActivity->name = $request->input('newActivityName');
            $newActivity->turned_in_date = $request->input('turnedInDate');
            $newActivity->file_name = $activityFileName;
            $newActivity->file_storage = $activityStorageName;
            $newActivity->student_id = $id;
            $newActivity->save();
        } catch(Exception $e) {
            return response()->json(['message' => 'Unexpected activity error', 'error' => $e], 500);
        }

        return response()->json(['newActivity' => ['id' => $newActivity->id, 'name' => $newActivity->name]], 200);        
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

    public function download($id) {
        //
    }

    private function random_string($length) {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));
    
        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
    
        return $key;
    }
}
