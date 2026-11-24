<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\EmpleadoController;

Route::get('/hola', function () {
    return 'hola mundo programacion wweb';
});

Route::get('/hola2', function () {
    return view('prueba', [
        'empresa' => 'Ferretería La Bendición24'
    ]);
});

Route::resource('productos', ProductoController::class);
Route::resource('empleados', EmpleadoController::class);

Route::get('/', function () {
    return redirect()->route('productos.index');
});
