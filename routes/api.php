<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    // Authenticated User Information
    Route::get('/user', function (Request $request) {
        $user = $request->user()->load('roles');

        return response()->json($user);
    });

    // User Management
    Route::get('/users', [UserController::class, 'index']);
    Route::put('/users/{user}/roles', [UserController::class, 'updateRoles']);
    Route::get('/roles', [UserController::class, 'getRoles']);

    // Profile Management
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
});
