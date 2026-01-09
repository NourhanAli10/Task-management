<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    use ApiResponses;

    public function login(Request $request) {

        $request->validate([
            'email' => 'required|email|string|exists:users,email',
            'password' => 'required|string',
        ]);

        $key = strtolower($request->email) . '|' . $request->ip();

        if(RateLimiter::tooManyAttempts($key, 5)) {
        return $this->errorresponse(message: 'Too many login attempts , try again later', statusCode: 429);
        }

        if(! Auth::attempt($request->only('email', 'password'))) {
            RateLimiter::hit($key, 60);
            return $this->errorresponse(message: 'Invalid credentials', statusCode: 401);
        }

        RateLimiter::clear($key);

        $user = Auth::user();

        $token = $user->createToken('login token')->plainTextToken;

        return $this->successResponse([
            'user' => $user,
            'token' => $token,
            'token_type' => "Bearer"
        ], 'you have login successfully', 200);

    }
}
