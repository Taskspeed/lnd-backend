<?php

namespace App\Services\User;

use Spatie\Permission\Models\Role;

class RoleService
{
    public function create(array $validated)
    {

        $role = Role::create(['name' => $validated['name']]);
        return $role;
    }

    public function update(array $validated, Role $role)
    {


        $role->update(['name' => $validated['name']]);

        return $role;
    }
}
