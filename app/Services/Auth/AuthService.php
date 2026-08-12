<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{

    public function register(array $validated)
    {
        return DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'       => $validated['name'],
                'office'     => $validated['office'],
                'username'   => $validated['username'],
                'control_no' => $validated['control_no'],
                'password'   => Hash::make($validated['password']),
            ]);

            // assign role (isa lang usually, pero pwede array kung multiple)
            if (!empty($validated['role'])) {
                $user->assignRole($validated['role']);
            }

            // direct permissions (hiwalay sa permissions na galing sa role)
            if (!empty($validated['permissions'])) {
                $user->givePermissionTo($validated['permissions']);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return [$user->load('roles', 'permissions'), $token];
        });
    }

    public function login(array $validated)
    {
        $user = User::where('username', $validated['username'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'  => $user->load('roles', 'permissions'),
            'token' => $token,
        ];
    }


    public function update(?array $validated, int $userId)
    {
        return DB::transaction(function () use ($validated, $userId) {

            $user = User::find($userId);

            if (!$user) {
                throw new \Exception('User not found');
            }

            $data = [
                'name'       => $validated['name'],
                'username'   => $validated['username'],
                'office'     => $validated['office'],
                'control_no' => $validated['control_no'],
            ];

            if (!empty($validated['password'])) {
                $data['password'] = Hash::make($validated['password']);
            }

            $user->update($data);

            // Role: syncRoles replaces existing role(s) with the new one
            $user->syncRoles([$validated['role']]);

            // Permissions: optional, direct permissions on top of role
            if (array_key_exists('permissions', $validated)) {
                $user->syncPermissions($validated['permissions'] ?? []);
            }

            return $user->load(['roles', 'permissions']);
        });
    }
}
