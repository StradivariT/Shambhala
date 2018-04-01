<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \Validator;

use App\Helpers\Utils;

use App\EducPlan;

class EducPlansController extends Controller {
    public function index() {
        $educPlans = EducPlan::all('id', 'name');

        if($educPlans->isEmpty())
            return response()->json('Educ plans not found', 404);

        return response()->json($educPlans, 200);
    }

    public function store(Request $request) {
        $newEducPlanName = $request->input('newResource');

        if(!Utils::isUniqueName($newEducPlanName, 'educ_plans'))
            return response()->json('Educ plan already exists', 400);

        try {
            $newEducPlan = new EducPlan;

            $newEducPlan->name = $newEducPlanName;
            
            $newEducPlan->save();
        } catch(Exception $error) {
            //TODO: Log $error
            return response()->json('Unexpected educ plan error', 500);
        }

        $newEducPlanResponse = [
            'id'   => $newEducPlan->id,
            'name' => $newEducPlanName
        ];

        return response()->json($newEducPlanResponse, 200);
    }

    public static function update($newName, $educPlanId) {
        $updatedEducPlanInfo = ['name' => $newName];

        try {
            EducPlan::find($educPlanId)->update($updatedEducPlanInfo);
        } catch(Exception $error) {
            //TODO: Log $error
            return false;
        }

        return true;
    }
}