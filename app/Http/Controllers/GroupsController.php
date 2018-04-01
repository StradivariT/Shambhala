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

    public function show($id) {
        $groupFileNames = Group::find($id, ['participants_file_name as participantsFileName', 'incidents_file_name as incidentsFileName', 'evaluations_file_name as evaluationsFileName']);
        
        if(empty($groupFileNames))
            return response()->json('Group not found, invalid ID', 400); 
        
        return response()->json($groupFileNames, 200); 
    }

    public function uploadFile(Request $request, $groupId) {
        $fileType = $request->input('fileType');
        $group = Group::find($groupId, [$fileType . '_file_storage']);

        //If there is already a file, we will replace it be deleting the existing one and then uploading the new one.
        if(!empty($group[$fileType . '_file_storage'])) {
            try {
                $path = 'storage/groupFiles/' . $fileType . '/' . $groupId . '/' . $group[$fileType . '_file_storage'];
                unlink(public_path($path));
            } catch(Exception $error) {
                //TODO: Log $error
                return response()->json('Unexpected group error', 500);
            }
        }

        $groupRequestFile = $request->file('groupFile');

        $groupFileName = $groupRequestFile->getClientOriginalName();
        $groupFileExtension = $groupRequestFile->getClientOriginalExtension();

        $groupStorageName = Utils::randomString(50) . '.' . $groupFileExtension;

        try {
            $groupRequestFile->storeAs('public/groupFiles/' . $fileType . '/' . $groupId, $groupStorageName);

            $groupFileInfo = [
                $fileType . '_file_name'    => $groupFileName,
                $fileType . '_file_storage' => $groupStorageName
            ];

            Group::find($groupId)->update($groupFileInfo);
        } catch(Exception $error) {
            //TODO: Log $error
            return response()->json('Unexpected group error', 500);
        }

        return response()->json($groupFileName, 200);  
    }

    public function download($groupId, $fileType) {
        $group = Group::find($groupId, [$fileType . '_file_storage']);

        $file = public_path() . '/storage/groupFiles/' . $fileType . '/' . $groupId . '/' . $group[$fileType . '_file_storage'];

        return response()->download($file);
    }

    public static function update($newName, $groupId) {
        $updatedGroupInfo = ['name' => $newName];

        try {
            Group::find($groupId)->update($updatedGroupInfo);
        } catch(Exception $error) {
            //TODO: Log $error
            return false;
        }

        return true;
    }
}