<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\UserLoginResource;
use App\Services\Auth\AuthService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    //
    use ApiResponseTrait;

    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        try {

            $result = $this->authService->register($validated);
            return $this->successMessage($result, 'User registered successfully.', 200);
        } catch (\Throwable $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username'    => 'required|string',
            'password' => 'required|string',
        ]);

        try {

            $result = $this->authService->login($validated);
            return $this->successMessage([
                'user'  => new UserLoginResource($result['user']),
                'token' => $result['token'],
            ], 'User login successfully.', 200);
        } catch (\Throwable $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        // deletes only the token used for this request
        $request->user()->currentAccessToken()->delete();
        return $this->successMessage(null, 'Logged out successfully.', 200);
    }
}
