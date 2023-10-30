<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function show(): JsonResponse
    {
        $permissions = Permission::all();

        $groupedPermissions = $permissions->groupBy(function ($permission) {
            return explode('_', $permission->name)[1];
        });
        return response()->json(['permissions' => $groupedPermissions]);
    }

    public function rolePermissions($id): JsonResponse
    {
        $permissions = Role::find($id)->permissions()->get();
        $groupedPerms = $permissions->groupBy(function ($permission) {
            return explode('_', $permission->name)[1];
        });

        return response()->json(['permissions' => $groupedPerms]);
    }
}