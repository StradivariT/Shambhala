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

        return response()->json($newEducPlan, 200);
    }

    public function update($newName, $id) {
        $educPlan = EducPlan::where('id', $id);

        $data = ['name' => $newName];

        try {
            $educPlan->update($data);
        } catch(Exception $e) {
            return false;
        }

        return true;
    }
}