<?php

namespace App\Services\User;

use App\Models\User;

class UserService
{
    

    public function listOfUsers(){

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
}
