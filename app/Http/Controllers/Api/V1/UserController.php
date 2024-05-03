<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Hash;

class UserController extends Controller
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function register(Request $request)
    {
        $messages = array(
            'message.required' => 'Message is required',
        );

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'username' => 'required|regex:/^[a-zA-Z0-9]+$/|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->all()
            ], 400);
        }

        $user = $this->user->create($request->all());
        return response()->json($user, 201);
    }

    public function getAuthUser()
    {
        return $this->userResponse(auth()->getToken()->get());
    }

    protected function userResponse(string $jwtToken): array
    {
        return ['user' => ['token' => $jwtToken] + auth()->user()->toArray()];
    }
}
