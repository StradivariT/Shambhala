<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Context;

class ContextsController extends Controller
{
    public function index() {
        $contexts = Context::all('id', 'name');

        if(empty($contexts))
            return response()->json(['error' => 'Contexts not found'], 404);

        return response()->json(['contexts' => $contexts], 200);
    }
}
