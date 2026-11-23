<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProductoResource;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Paginamos de 10 en 10 para no colapsar la memoria en catálogos gigantes
        $productos = Producto::orderBy('id', 'desc')->paginate(10);

        return response()->json([
            'status' => 'success',
            'data'   => ProductoResource::collection($productos),
            'pagination' => [
                'total'        => $productos->total(),
                'per_page'     => $productos->perPage(),
                'current_page' => $productos->currentPage(),
                'last_page'    => $productos->lastPage(),
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
            'codigo_barra' => 'required|string|unique:productos,codigo_barra|max:30',
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
        // Buscamos el producto en MySQL
        $producto = Producto::find($id);

        // Si no existe, devolvemos error semántico 404
        if (!$producto) {
            return response()->json([
                'status'  => 'error',
                'message' => "El producto con ID #{$id} no existe en Ferretería La Bendición"
            ], 404); // 404 Not Found
        }

        return response()->json([
            'status' => 'success',
            'data'   => new ProductoResource($producto)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Producto no encontrado para actualizar'
            ], 404);
        }

        $validados = $request->validate([
            'nombre'       => 'required|string|max:150',
            'precio' => 'required|numeric|min:0.01',
            'stock'        => 'required|integer|min:0',
        ]);

        $producto->update($validados);

        return response()->json([
            'status'  => 'updated',
            'message' => 'Producto actualizado correctamente',
            'data'    => new ProductoResource($producto)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'status'  => 'error',
                'message' => 'El producto a eliminar no existe en bodega'
            ], 404);
        }

        $producto->delete();

        return response()->json([
            'status'  => 'deleted',
            'message' => 'Producto dado de baja exitosamente'
        ], 200);
    }
}
