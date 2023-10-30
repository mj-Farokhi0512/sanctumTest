<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function show(): JsonResponse
    {
        $roles = Role::all();

        foreach ($roles as $role) {
            if ($role->name === "MANAGER") {
                $role->changable = false;
            } else {
                $role->changable = true;
            }
        }
        return response()->json(['roles' => $roles]);
    }

    public function create(Request $request): JsonResponse
    {
        $validate = $request->validate(['role' => ['required', 'regex:/^[a-zA-Z]+$/', Rule::unique('roles', 'name')]], ['role.regex' => 'نقش وارد شده معتبر نمیباشد', 'role.unique' => 'این نقش قبلا ثبت شده']);

        $role = Role::create(['name' => $validate['role'], 'guard_name' => 'api']);
        $role->changable = true;

        return response()->json(['message' => 'نقش باموفقیت ایجاد شد', 'role' => $role]);
    }

    public function delete($id): JsonResponse
    {
        $role = Role::find($id);

        $role->delete();

        return response()->json(['message' => 'این نقش با موفقیت حذف شد', 'roleId' => $id]);
    }

    public function syncPermissions(Request $request): JsonResponse
    {

        $permissions = $request->get('permissions');
        $id = $request->get('id');

        $role = Role::find($id);
        $role->syncPermissions($permissions);

        return response()->json(['permissions' => $permissions, 'role' => $role]);
    }
}
