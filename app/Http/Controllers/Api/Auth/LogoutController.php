<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    use ApiResponses;

    public function Logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(message: ' User successfully logged out from current device', statusCode: 200);
    }

    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->successResponse(message: ' User successfully logged out from all devices', statusCode: 200);
    }

    public function logoutSpecificToken(Request $request , string $id)
    {
        $deleted = $request->user()->tokens()->where('id', $id)->delete();
        if ($deleted) {
            return $this->successResponse(message: ' User successfully logged out from this device', statusCode: 200);
        }
        return $this->errorResponse(message: 'Token not found', statusCode: 404);
    }
}
