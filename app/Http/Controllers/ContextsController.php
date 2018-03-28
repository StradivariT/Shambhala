<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Context;

class ContextsController extends Controller {
    public function index() {
        $contexts = Context::all('id', 'name');

        if($contexts->isEmpty())
            return response()->json(['error' => 'Contexts not found'], 404);

        return response()->json(['contexts' => $contexts], 200);
    }

    public function update(Request $request, $id) {
        echo $request->input('educPlanName');
        echo '   ';
        echo $request->input('courseName');
        echo '   ';
        echo $request->input('groupName');
    }
}