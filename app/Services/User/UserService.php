<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{


    public function listOfUsers()
    {

        $users = User::all();

        return $users;
    }


    public function delete(int $userId)
    {

        $user = User::find($userId);

        if (!$user) {
            throw new \Exception('User not found');
        }

        $user->delete();

        return $user;
    }

    public function edit(?array $validated, int $userId)
    {


        return DB::transaction(function () use ($validated, $userId) {
            $user = User::find($userId);

            if (!$user) {
                throw new \Exception('User not found');
            }

            $data = [
                'name'       => $validated['name'],
                'username'   => $validated['username'],
                'control_no' => $validated['control_no'],
            ];

            if (!empty($validated['password'])) {
                $data['password'] = Hash::make($validated['password']);
            }

            $user->update($data);

            return $user;
        });
    }
}
