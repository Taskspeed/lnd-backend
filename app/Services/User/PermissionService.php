<?php

namespace App\Services\User;

use Spatie\Permission\Models\Permission;

class PermissionService
{
   

    public function create(array $validated)
    {
    
        $permission = Permission::create(['name' => $validated['name']]);
        return $permission;
    }

    
    public function edit(array $validated,Permission $permission)
    {

        $permission->update(['name' => $validated['name']]);
        return $permission;
    }

}
