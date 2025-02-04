<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Controllers\UserController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::get('/', function (Request $request) {
    return response()->json("LARAVEL RESTFUL API");
});

Route::apiResource('/users', UserController::class);
Route::post('/login', [UserController::class, 'login']);
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::put('/users-update', [UserController::class, 'updateOwnData']);
});
