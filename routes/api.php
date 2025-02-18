<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');
Route::middleware(['auth:sanctum'])->group(function() {
    Route::get('/debug-user', function(Request $request) {
        return [
            'user' => $request->user(),
            'roles' => $request->user()->roles->pluck('name'),
            'permissions' => $request->user()->getPermissionsViaRoles()
        ];
    });
    Route::apiResource('/posts', PostController::class);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
