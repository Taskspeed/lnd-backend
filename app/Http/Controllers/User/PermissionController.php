<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\PermissionService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    use ApiResponseTrait;

    protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index()
    {
        $permission = Permission::all();

        return $this->successMessage($permission,'Permission fetch successfully',200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        $result = $this->permissionService->create($validated);
        return $this->successMessage($result, 'Permission created successfully', 201);
    }


    public function update(Request $request, int $permissionId)
    {
        $permission = Permission::find($permissionId);

        if(!$permission){
            return $this->errorMessage('Permission not found',404);
        }

        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $permission->id,
        ]);


        $result = $this->permissionService->edit($validated, $permission);

        return $this->successMessage($result, 'Permission update successfully', 201);
    }

    public function destroy(int $permissionId)
    {
        $permission = Permission::findOrFail($permissionId);
        $permission->delete();

        return $this->successMessage($permission, 'Permission deleted successfully', 200);
    }
}
