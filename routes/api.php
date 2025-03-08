<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// User Registration Route
Route::post('/register', [RegisteredUserController::class, 'store']);

// Login and Logout Routeshvcx
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');

// Protected Routes (Require Authentication)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/debug-user', function (Request $request) {
        return [
            'user' => $request->user(),
            'roles' => $request->user()->roles->pluck('name'),
            'permissions' => $request->user()->getPermissionsViaRoles(),
        ];
    });

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});