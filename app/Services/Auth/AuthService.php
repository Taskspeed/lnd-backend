<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{

    public function register(array $validated)
    {

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Optional: issue a token right away if you're using Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            $user,
            $token, // token Sanctum
        ];
    }

    public function login(array $validated)
    {

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // optional but recommended: wipe old tokens before issuing a new one
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
             $user,
             $token,
        ];
    }
}
