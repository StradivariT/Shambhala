<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\Utils;

use App\Activity;

class ActivitiesController extends Controller {
    public function index($studentId) {   
        $activities = Activity::select('id', 'name')->where('student_id', '=', $studentId)->get();

        if($activities->isEmpty())
            return response()->json('Activities not found for student: ' . $studentId, 404);

        return response()->json($activities, 200);
    }

    public function store(Request $request, $studentId) {
        $newActivityFile = $request->file('file');
        $newActivityInfo = $request->except(['token', 'file']);

        try {
            $activityStorageName = Utils::randomString() . '.' . $newActivityFile->getClientOriginalExtension();
            $newActivityFile->storeAs('public/activityFiles/' . $studentId, $activityStorageName);

            $newActivity = new Activity;

            $newActivity->name = $newActivityInfo['name'];
            $newActivity->turned_in_date = $newActivityInfo['turnedInDate'];
            $newActivity->file_name = $newActivityFile->getClientOriginalName();
            $newActivity->file_storage = $activityStorageName;
            $newActivity->student_id = $studentId;
            
            $newActivity->save();
        } catch(Exception $error) {
            //TODO: Log $error
            return response()->json('Unexpected activity error', 500);
        }

        $newActivityResponse = [
            'id'   => $newActivity->id,
            'name' => $newActivityInfo['name']
        ];

        return response()->json($newActivityResponse, 200);        
    }

    public function show($activityId) {
        $activity = Activity::find($activityId, ['id', 'name', 'turned_in_date as turnedInDate', 'feedback', 'incidents', 'file_name as fileName', 'grade']);
     
        if(empty($activity))
            return response()->json('Activity not found for id: ' . $id, 404);

        return response()->json($activity, 200);
    }

    public function update(Request $request, $activityId) {
        $updatedActivityInfo = $request->except(['token', 'id']);

        $updatedActivityInfo['turned_in_date'] = $updatedActivityInfo['turnedInDate'];
        unset($updatedActivityInfo['turnedInDate']);

        $activity = Activity::find($activityId);
        try {
            $activity->update($updatedActivityInfo);
        } catch(Exception $error) {
            //TODO: Log $error
            return response()->json('Unexpected activity error', 500);
        }

        $updatedActivityInfo['id'] = $activity->id;
        $updatedActivityInfo['fileName'] = $activity->file_name;
        $updatedActivityInfo['turnedInDate'] = $updatedActivityInfo['turned_in_date'];
        unset($updatedActivityInfo['turned_in_date']);
        
        return response()->json($updatedActivityInfo, 200);
    }

    public function download($activityId) {
        $activity = $activity = Activity::find($activityId, ['student_id', 'file_storage']);

        $file = public_path() . '/storage/activityFiles/' . $activity['student_id'] . '/' . $activity['file_storage'];

        return response()->download($file);
    }
}