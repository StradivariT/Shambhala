<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \Validator;
use App\Group;
use Illuminate\Support\Facades\Storage;

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

        $groupStorageName = $this->random_string(50) . '.' . $groupFileExtension;

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

    public function update(Request $request, $id) {
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
