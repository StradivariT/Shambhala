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
        $educPlanId = $request->input('educPlanId');
        $educPlanName = $request->input('educPlanName');

        $courseId = $request->input('courseId');
        $courseName = $request->input('courseName');

        $groupId = $request->input('groupId');
        $groupName = $request->input('groupName');

        if(!Utils::isUniqueName($educPlanName, 'educ_plans'))
            return response()->json('Ya existe un plan educativo con ese nombre.', 400);
        
        if(!Utils::isUniqueName($courseName, 'courses'))
            return response()->json('Ya existe un curso con ese nombre.', 400);
        
        if(!Utils::isUniqueName($groupName, 'groups'))
            return response()->json('Ya existe un grupo con ese nombre.', 400);
            
        if($educPlanName) 
            if(!EducPlansController::update($educPlanName, $educPlanId))
                return response()->json('Unexpected educ plan error', 500);

        if($courseName)
            if(!CoursesController::updateName($courseName, $courseId))
                return response()->json('Unexpected course error', 500);

        if($groupName)
            if(!GroupsController::updateName($groupName, $groupId))
                return response()->json('Unexpected group error', 500); 

        $updatedContextResponse = [];

        return response()->json(['educPlanName' => $educPlanName, 'courseName' => $courseName, 'groupName' => $groupName], 200);
    }
}