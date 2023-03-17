<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{
    public function store_user(Request $request)
    {
        // return $request;
        $validator = Validator::make($request->all(),[
            'params.mobile' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20',
            'params.email' => 'required|email',
            'params.name' => 'required|string',
            'params.terms_conditions' => 'required|boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $users = User::where('phone_number',$request->input('params.mobile'))->get();
        if(isEmpty($users))
        {
            $user = User::create([
                "name" => $validator['params']['name'],
                "phone_number_country_code" => $request->input('country_code'),
                "phone_number" => $validator['params']['mobile'],
                "email" => $validator['params']['email'],
                "terms_conditions" => $validator['params']['terms_conditions'],
            ]);
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
        }
        
        // $email = $request->input('params.email');
        // return $request->params;
    }
}
