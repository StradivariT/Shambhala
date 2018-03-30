<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Context;

use App\Helpers\Utils;

use App\Http\Controllers\EducPlansController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\GroupsController;

class ContextsController extends Controller {
    public function index() {
        $contexts = Context::all('id', 'name');

        if($contexts->isEmpty())
            return response()->json('Contexts not found', 404);

        return response()->json($contexts, 200);
    }

    public function update(Request $request, $id) {
        $educPlanName = $request->input('educPlanName');
        $educPlanId = $request->input('educPlanId');
        $courseName = $request->input('courseName');
        $courseId = $request->input('courseId');
        $groupName = $request->input('groupName');
        $groupId = $request->input('groupId');

        if(!Utils::isUniqueName($educPlanName, 'educ_plans'))
            return response()->json(['error' => 'Ya existe un plan educativo con ese nombre.'], 400);
        
        if(!Utils::isUniqueName($courseName, 'courses'))
            return response()->json(['error' => 'Ya existe un curso con ese nombre.'], 400);
        
        if(!Utils::isUniqueName($groupName, 'groups'))
            return response()->json(['error' => 'Ya existe un grupo con ese nombre.'], 400);

        if($educPlanName) {
            $educPlanUpdated = app('App\Http\Controllers\EducPlansController')->update($educPlanName, $educPlanId);

            if(!$educPlanUpdated)
                return response()->json(['error' => 'Error when updating educ plan: ', $educPlanId], 500);
        }

        if($courseName) {
            $courseUpdated = app('App\Http\Controllers\CoursesController')->updateName($courseName, $courseId);

            if(!$courseUpdated)
                return response()->json(['error' => 'Error when updating course: ', $educPlanId], 500);
        }

        if($groupName) {
            $courseUpdated = app('App\Http\Controllers\GroupsController')->update($groupName, $groupId);

            if(!$courseUpdated)
                return response()->json(['error' => 'Error when updating group: ', $groupId], 500); 
        }

        return response()->json(['educPlanName' => $educPlanName, 'courseName' => $courseName, 'groupName' => $groupName], 200);
    }
}