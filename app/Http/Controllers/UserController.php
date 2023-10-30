<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function getUsers(): JsonResponse
    {
        $users = DB::table('users')->leftJoin('model_has_roles', 'users.id', '=', 'model_id')->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')->whereNull('deleted_at')->select(['users.username', 'users.profile', 'users.email', 'users.id', 'roles.name'])->get();

        return response()->json(['users' => $users]);
    }

    public function getUser($id): JsonResponse
    {
        $user = User::select(['id', 'username', 'email'])->find($id);

        return response()->json(['user' => $user]);
    }

    public function updateUser(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|regex:/^[a-zA-Z0-9_-]{3,16}$/',
            'password' => 'required|string|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',
        ], [
            'username.regex' => 'نام کاربری وارد شده نامعتبر است',
            'password.regex' => 'رمزعبور وارد شده نامعتبر است'
        ]);

        $user = User::find($id);
        $user->update([
            'username' => $validated['username'],
            'password' => $validated['password'],
        ]);

        return response()->json(['message' => 'کاربر با موفقیت آپدیت شد', 'user' => ['id' => $user->id, 'username' => $user->username, 'email' => $user->email]]);
    }

    public function deleteUser($id): JsonResponse
    {
        $user = User::find($id);
        $user->delete();

        return response()->json(['message' => 'کاربر باموفقیت حذف شد', 'userEmail' => $user->email]);
    }
}
