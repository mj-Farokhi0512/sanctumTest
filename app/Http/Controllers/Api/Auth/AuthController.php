<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function register(UserCreateRequest $request): array
    {
        $validated = $request->validated();

        // return ['validated' => $validated];
        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        $role = Role::where('name', 'USER')->first();
        $user->assignRole($role);

        $token = $user->createToken($validated['email'], [], now()->addHours(2));

        return ['data' => $request->all(), 'token' => ['data' => $token->plainTextToken, 'expires' => $token->accessToken->expires_at]];
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'regex: /^([a-zA-Z0-9._%+-]+)@([a-zA-Z0-9.-]+)\.([a-zA-Z]{2,})$/', Rule::exists('users')],
            'password' => ['required', 'string', 'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/'],
        ], [
            'email.regex' => 'ایمیل وارد شده معتبر نیست',
            'password.regex' => 'پسورد وارد شده نامعتبر است!',
            'email.exists' => 'کاربری با این ایمیل پیدا نشد'
        ]);

        $validated = $validator->validated();


        if (!$validator->fails()) {
            if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
                $user = User::where('email', $validated['email'])->first();
                $token = $user->createToken($validated['email'], [], now()->addHours(2));

                return response()->json(['user' => $user, 'token' => ['data' => $token->plainTextToken, 'expires' => $token->accessToken->expires_at]]);
            } else {
                return abort(401, 'اطلاعات وارد شده اشتباه است');
            }
        } else {
            abort(401, 'اطلاعات وارد شده نامعتبر می باشد');
        }
    }

    public function logout(): JsonResponse
    {
        $user = Auth::user();

        $user->tokens()->delete();

        return response()->json(['message' => 'شما خارج شدید', 'user' => $user]);
    }

    public function checkLogin(): JsonResponse
    {
        return response()->json(['message' => 'شما احرازهویت شده اید']);
    }
    public function sendPasswordResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => __($status)], 200);
        } else {
            throw ValidationException::withMessages([
                'email' => __($status)
            ]);
        }
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',
        ], [
            'password.regex' => 'پسورد وارد شده معتبر نمی باشد',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['message' => __($status)], 200);
        } else {
            throw ValidationException::withMessages([
                'email' => __($status)
            ]);
        }
    }
}