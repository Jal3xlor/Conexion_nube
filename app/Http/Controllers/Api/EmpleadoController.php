<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProductoResource;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $empleados = Empleado::orderBy('id', 'desc')->paginate(10);

        return response()->json([
            'status' => 'success',
            'data'   => EmpleadoResource::collection($productos),
            'pagination' => [
                'total'        => $empleados->total(),
                'per_page'     => $empleados->perPage(),
                'current_page' => $empleados->currentPage(),
                'last_page'    => $empleados->lastPage(),
            ]
        ], 200); // 200 OK
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // 1. Validamos los datos entrantes del payload JSON
        $validados = $request->validate([
            'dni' => 'required|string|unique:productos,codigo_barra|max:30',
            'nombre'       => 'required|string|max:150',
            'precio_venta' => 'required|numeric|min:0.01',
            'stock'        => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        // 2. Insertamos el producto utilizando Eloquent ORM
        $producto = Producto::create($validados);

        // 3. Respondemos con código semántico 201 Created y el recurso transformado
        return response()->json([
            'status'  => 'created',
            'message' => 'Artículo ingresado exitosamente al inventario de Ferretería La Bendición',
            'data'    => new ProductoResource($producto)
        ], 201); // 201 Created
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
