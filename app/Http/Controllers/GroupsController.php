<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use \Validator;

use App\Helpers\Utils;

use App\Group;

class GroupsController extends Controller {
    public function index($courseId) {
        $groups = Group::select('id', 'name')->where('course_id', '=', $courseId)->get();

        if($groups->isEmpty())
            return response()->json('Groups not found for course: ' . $courseId, 404);

        return response()->json($groups, 200);
    }

    public function store(Request $request, $courseId) {
        $newGroupName = $request->input('newResource');

        if(!Utils::isUniqueName($newGroupName, 'groups'))
            return response()->json('Group already exists', 400);

        try {
            $newGroup = new Group;

            $newGroup->name = $newGroupName;
            $newGroup->course_id = $courseId;
            
            $newGroup->save();
        } catch(Exception $error) {
            //TODO: Log $error
            return response()->json('Unexpected group error', 500);
        }

        $newGroupResponse = [
            'id'   => $newGroup->id,
            'name' => $newGroupName
        ];

        return response()->json($newGroupResponse, 200);
    }

    public function uploadFile(Request $request, $id) {
        $fileType = $request->input('fileType');       
        $groupFile = Group::where('id', $id)->get([$fileType . '_file_storage'])->first();

        if(!empty($groupFile[$fileType . '_file_storage'])) {
            try {
                $path = 'storage/groupFiles/' . $fileType . '/' . $id . '/' . $groupFile[$fileType . '_file_storage'];
                unlink(public_path($path));
            } catch(Exception $e) {
                return response()->json(['message' => 'Unexpected group error', 'error' => $e], 500);
            }
        }

        $groupRequestFile = $request->file('groupFile');

        $groupFileName = $groupRequestFile->getClientOriginalName();
        $groupFileExtension = $groupRequestFile->getClientOriginalExtension();

        $groupStorageName = Utils::randomString(50) . '.' . $groupFileExtension;

        $data = array();

        try {
            $groupRequestFile->storeAs('public/groupFiles/' . $fileType . '/' . $id, $groupStorageName);

            $group = Group::where('id', $id)->first();

            $data[$fileType . '_file_name'] = $groupFileName;
            $data[$fileType . '_file_storage'] = $groupStorageName;
            $group->update($data);
        } catch(Exception $e) {
            return response()->json(['message' => 'Unexpected group error', 'error' => $e], 500);
        }

        return response()->json(['groupFileName' => $groupFileName], 200);  
    }

    public function download($id, $fileType) {
        $group = $group = Group::where('id', $id)->get([$fileType . '_file_storage'])->first();

        $file = public_path() . '/storage/groupFiles/' . $fileType . '/' . $id . '/' . $group[$fileType . '_file_storage'];

        return response()->download($file);
    }

    public function show($id) {
        $groupFileNames = Group::where('id', $id)->get(['participants_file_name as participantsFileName', 'incidents_file_name as incidentsFileName', 'evaluations_file_name as evaluationsFileName']);
        
        if($groupFileNames->isEmpty())
            return response()->json(['message' => 'Group not found, invalid ID'], 400); 
        
        return response()->json(['groupFileNames' => $groupFileNames->first()], 200); 
    }

    public function update($newName, $id) {
        $group = Group::where('id', $id);

        $data = ['name' => $newName];

        try {
            $group->update($data);
        } catch(Exception $e) {
            return false;
        }

        return true;
    }
}