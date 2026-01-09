<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class RegisterController extends Controller
{
    use ApiResponses;

    public function register(RegisterRequest $request)
    {

        $validator = $request->validated();

        $user = User::create([
            'name' => $validator['name'],
            'username' => $validator['name'],
            'email' => $validator['email'],
            'password' => Hash::make($validator['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        
        return $this->successResponse([
            'user' => $user,
            'token' => $token,
            'token_type'=> "Bearer"
        ], 'You have successfully registered', 200);
    }
}
