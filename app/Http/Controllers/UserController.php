<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Vonage\Client\Credentials\Basic;
use Vonage\Client;

use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{
    public function check_mobile_number_is_valid(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'params.mobile' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:11',
        ],[
            'params.mobile' => 'The mobile number is required.',
            'params.mobile.regex' => 'The mobile field should contain only numbers,and it should be in-between 10-11',            
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $users = User::where('phone_number', $request->input('params.mobile'))->get();
            if(!$users)
            {
                return response()->json([
                    'flag' => 1,
                    'errors' =>  [
                        'mobile' => "Mobile number is already exists ",
                     ]
                ]);
            }
            else {
                return response()->json([
                    'flag' => 1,
                    'msg' => "Mobile number is correct, move forward to next step ",
                ]);
            }
        }
    }
    public function store_user(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'params.email' => 'required|email',
            'params.name' => 'required|string',
            'params.terms_conditions' => 'required|boolean',
        ],[
            'params.email' => 'The email is required',
            'params.email.email' => 'The email should be valid email-id',
            'params.name' => 'The name is required',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $users = User::where('phone_number', $request->input('params.mobile'))->get();
            if(isEmpty($users))
            {
                $apiKey = "59ca944c";
                $apiSecret = "kxptfKxsbQa2WHhr";
                $otp = rand(000000, 999999);
                $mobile = '91'.$request->input('params.mobile');
                $client = new  Client(new Basic($apiKey, $apiSecret));
                $response = $client->sms()->send(
                    new \Vonage\SMS\Message\SMS($mobile, 'YOUR_VONAGE_PHONE_NUMBER', "Your OTP is: $otp")
                );
                $message = $response->current();

                if ($message->getStatus() == 0) {
                    $user = User::create([
                        "name" =>$request->input('params.name'),
                        "phone_number_country_code" => $request->input('params.country_code'),
                        "phone_number" => $request->input('params.mobile'),
                        "email" =>$request->input('params.email'),
                        "terms_conditions" =>$request->input('params.terms_conditions'),
                        "otp" => $otp
                    ]);
    
                    if($user)
                    {
                        return response()->json([
                            'flag' => 1,
                            'msg' => "Welcome To Dwebpixel Club, OTP sent successfully",
                            'errors' => null
                        ]);
                    } 
                    else {
                        return response()->json([
                            'flag' => 1,
                            'msg' => "Something went wrong ",
                        ]);
                    }
                } else {
                    return response()->json([
                        'flag' => 1,
                        'msg' => "The message failed with status: " . $message->getStatus() . "\n",
                    ]);
                }
                
                
            }
            else {
                return response()->json([
                    'flag' => 2,
                    'msg' => "Mobile Number already exists, you can login with this number or try different number",
                ]);
                
            }
        }
    }

}
