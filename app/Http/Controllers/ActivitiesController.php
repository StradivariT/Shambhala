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
        $activity = Activity::where('id', $id)->get(['id', 'name', 'turned_in_date as turnedInDate', 'feedback', 'incidents', 'file_name as fileName', 'grade']);
     
        if($activity->isEmpty()) 
            return response()->json(['message' => 'Activity not found for id: ' . $id . '.'], 404);

        return response()->json(['activity' => $activity->first()], 200);
    }

    public function update(Request $request, $id) {
        $activity = Activity::where('id', $id);

        $data = $request->except(['token', 'id']);

        $data['turned_in_date'] = $data['turnedInDate'];
        unset($data['turnedInDate']);

        try {
            $activity->update($data);
        } catch(Exception $e) {
            return response()->json(['message' => 'Unexpected activity error', 'error' => $e], 500);
        }

        $activity = $activity->get(['id', 'name', 'turned_in_date as turnedInDate', 'feedback', 'incidents', 'file_name as fileName', 'grade'])->first();

        return response()->json(['activity' => $activity], 200);
    }

    public function destroy($id) {
        //
    }

    public function download($id) {
        $activity = $activity = Activity::where('id', $id)->get(['student_id', 'file_storage'])->first();

        $file = public_path() . '/storage/activityFiles/' . $activity['student_id'] . '/' . $activity['file_storage'];

        return response()->download($file);
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
