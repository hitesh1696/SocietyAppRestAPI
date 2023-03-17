<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{
    public function store_user(Request $request)
    {
        // return $request->input('params.mobile');
        $users = User::where('phone_number',$request->input('params.mobile'))->get();
        if(isEmpty($users))
        {
            $user = User::create([
                "name" => $request->input('params.name'),
                "phone_number_country_code" => $request->input('params.country_code'),
                "phone_number" => $request->input('params.mobile'),
                "email" => $request->input('params.email'),
                "terms_conditions" => $request->input('params.terms_conditions'),
            ]);
            // $msg = 
            return response()->json([
                'flag' => 1,
                'msg' => "Welcome To Dwebpixel Club " . $user->name,
            ]);
        }
        else {
            return response()->json([
                'flag' => 2,
                'msg' => "Mobile Number already exists, you can login with this number or try different number",
            ]);
            
        }
        // $email = $request->input('params.email');
        // return $request->params;
    }
}
