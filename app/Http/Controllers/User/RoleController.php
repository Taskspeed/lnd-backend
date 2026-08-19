<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\RoleService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller

{
    use ApiResponseTrait;

    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        $role = Role::all();

        return $this->successMessage($role, 'Role fetch successfully', 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        $role = $this->roleService->create($validated);

        return $this->successMessage($role, 'Role created successfully', 201);
    }

    public function show(int $roleId)
    {
        $role = Role::findOrFail($roleId);
        return $this->successMessage($role, 'Role show successfully', 201);
    }

    public function update(Request $request, int $roleId)
    {
        $role = Role::find($roleId);

        if (!$role) {
            return $this->errorMessage('Role not found', 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
        ]);

        $role = $this->roleService->update($validated, $role);

        return $this->successMessage($role, 'Role updated successfully', 200);
    }

    public function destroy(int $roleId)
    {
        $role = Role::findOrFail($roleId);
        $role->delete();

        return $this->successMessage($role, 'Role deleted successfully', 200);
    }
}
