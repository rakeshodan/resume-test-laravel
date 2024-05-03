<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request){

        $messages = array(
            'message.required' => 'Message is required',
        );

        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required"
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->all()
            ], 400);
        }
    
        $token = auth()->attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);

        if(!$token){

            return response()->json([
                "status" => false,
                "message" => "Invalid login details"
            ], 400);
        }

        return response()->json([
            "status" => true,
            "message" => "User logged in",
            "token" => $token,
            "expires_in" => auth()->factory()->getTTL() * 60
        ]);

    }
}
