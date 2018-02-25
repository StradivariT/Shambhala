<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Validator;
use App\EducPlan;

class EducPlansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $educPlans = EducPlan::all('id', 'name');

        if($educPlans->isEmpty())
            return response()->json(['message' => 'Educ plans not found.'], 404);

        return response()->json(['resources' => $educPlans], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}