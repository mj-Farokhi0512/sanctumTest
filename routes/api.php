<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->middleware(['auth:sanctum'])->name('logout');
    Route::get('/check-login', 'checkLogin')->middleware(['auth:sanctum'])->name('checkLogin');

    Route::post('/password/email', 'sendPasswordResetLinkEmail')->name('password.email');
    Route::post('/password/reset', 'resetPassword')->name('password.reset');
});

Route::middleware(['auth:sanctum'])->controller(UserController::class)->group(function () {
    Route::get('/users', 'getUsers')->name('users.getUsers');
    Route::get('/users/user/{id}', 'getUser')->name('users.getUser');
    Route::put('/users/update/{id}', 'updateUser')->name('users.updateUser');
    Route::delete('/users/delete/{id}', 'deleteUser')->name('users.deleteUser');
});


Route::middleware(['auth:sanctum'])->controller(RoleController::class)->group(function () {
    Route::get('/roles', 'show')->name('roles.show');
    Route::post('/roles/create', 'create')->name('roles.create');
    Route::delete('/roles/delete/{id}', 'delete')->name('roles.delete');
    Route::post('/roles/sync-permissions', 'syncPermissions')->name('roles.syncPermissions');
});

Route::middleware(['auth:sanctum'])->controller(PermissionController::class)->group(function () {
    Route::get('/permissions', 'show')->name('permissions.show');
    Route::get('/permissions/{id}', 'rolePermissions')->name('permissions.rolePermissions');
});

Route::prefix('/tags')->middleware(['auth:sanctum'])->controller(TagController::class)->group(function () {
    Route::get('/show', 'show')->middleware(['role:MANAGER|ADMIN'])->name('tags.show');
    Route::post('/create', 'create')->middleware(['role:MANAGER|ADMIN'])->name('tags.create');
    Route::delete('/delete', 'delete')->middleware(['role:MANAGER|ADMIN'])->name('tags.delete');
});

Route::prefix('/categories')->middleware(['auth:sanctum'])->controller(CategoryController::class)->group(function () {
    Route::get('/show', 'show')->middleware(['role:MANAGER|ADMIN'])->name('categories.show');
});
