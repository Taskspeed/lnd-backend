<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    use ApiResponseTrait;

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

   public function index()
    {

        try {
            $users = $this->userService->listOfUsers();

            return $this->successMessage($users, 'success fetch', 200,);
        } catch (\Throwable $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    
    public function destory(int $userId)
    {

        try {
            $result = $this->userService->delete($userId);

            return $this->successMessage($result, 'success deleted', 200,);
        } catch (\Throwable $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }


}
