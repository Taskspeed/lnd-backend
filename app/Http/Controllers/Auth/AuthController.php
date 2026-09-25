<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Resources\User\UserLoginResource;
use App\Models\RSP\vwActive;
use App\Models\RSP\xPersonal;
use App\Services\Auth\AuthService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;


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

    public function update(UpdateUserRequest $request, int $userId)
    {
        $validated = $request->validated();

        try {
            $result = $this->authService->update($validated, $userId);
            return $this->successMessage($result, 'User updated successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function profileUpdate(Request $request)
    {
        $auth = Auth::user();

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:25',
                Rule::unique('users', 'username')->ignore($auth->id), // palitan ang table/column kung iba
            ],
            'password' => ['nullable', 'confirmed', Password::min(5)],
        ]);

        try {
            $result = $this->authService->updateProfile($validated, $auth);

            return $this->successMessage($result, 'User updated successfully.', 200);
        } catch (\Throwable $e) {

            return $this->errorMessage('Unable to update profile.', 500);
        }
    }

        public function profileView()
        {
            $userAuth = Auth::user();

            $profile = vwActive::select('ControlNo', 'Surname', 'Firstname', 'sex', 'Office', 'Status', 'MIddlename', 'Designation')
                ->where('ControlNo', $userAuth->control_no)
                ->first();

            if (!$profile) {
                return $this->errorMessage('Profile not found.', 404);
            }
  

            $data = $profile->toArray();
            $data['username']= $userAuth->username;
            $data['photo_url'] = url("/api/event/employee/{$profile->ControlNo}/photo");

            return $this->successMessage($data, 'User profile successfully.', 200);
        }
}
