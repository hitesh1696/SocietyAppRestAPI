<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store_user(Request $request)
    {
        
        $email = $request->input('params.email');
        return $email;
    }
}
