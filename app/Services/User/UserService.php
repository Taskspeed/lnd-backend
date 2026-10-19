<?php

namespace App\Services\User;

use App\Models\RSP\yOffice;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{


    public function listOfUsers(?string $search = null, int $perPage)
    {
       
        $users = User::with('roles:id,name')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                    ->orWhere('control_no', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('office', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate($perPage);

        // isang query lang para sa office abbreviations ng current page
        $abbrs = yOffice::whereIn(
                'Descriptions',
                $users->getCollection()->pluck('office')->filter()->unique()
            )
            ->pluck('Abbr', 'Descriptions');

        // through() ay binabago ang items pero napapanatili ang pagination meta
        $users->through(function ($user) use ($abbrs) {
            $data = $user->toArray();
            $data['office_abbr'] = $abbrs[$user->office] ?? null;
            $data['role'] = $user->roles->first()?->name;
            return $data;
        });

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
                'isActive' => $validated['isActive'] ?? null,
            ];

            if (!empty($validated['password'])) {
                $data['password'] = Hash::make($validated['password']);
            }

            $user->update($data);

            return $user;
        });
    }
}
