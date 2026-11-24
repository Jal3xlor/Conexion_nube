<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductoController;
//librerias
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Route::apiResource('producto', ProductoController::class);
//Route::apiResource('empleado', EmpleadoController::class);

Route::post('/login', [Authcontroller::class, 'login']);
Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('producto', ProductoController::class)->names('api.productos');
});