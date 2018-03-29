<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Validator;
use App\EducPlan;

class EducPlansController extends Controller {
    public function index() {
        $educPlans = EducPlan::all('id', 'name');

        if($educPlans->isEmpty())
            return response()->json(['message' => 'Educ plans not found.'], 404);

        return response()->json(['resources' => $educPlans], 200);
    }

    public function store(Request $request) {
        $validator = Validator::make(
            ['name' => $request->input('newResource')], 
            ['name' => 'unique:educ_plans']
        );

        if($validator->fails())
            return response()->json(['message' => 'Educ plan already exists'], 400);

        try {
            $newEducPlan = new EducPlan;
            $newEducPlan->name = $request->input('newResource');
            $newEducPlan->save();
        } catch(Exception $e) {
            return response()->json(['message' => 'Unexpected educ plan error', 'error' => $e], 500);
        }

        return response()->json(['newResource' => $newEducPlan], 200);
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

    public function isUniqueName($name) {
        $validator = Validator::make(
            ['name' => $name], 
            ['name' => 'unique:educ_plans']
        );

        if($validator->fails())
            return false;

        return true;
    }
}
