<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store_user(Request $request)
    {
        // $d = $request->();
        //  $d = json_decode($request);
        return $request->params;
    }
}
