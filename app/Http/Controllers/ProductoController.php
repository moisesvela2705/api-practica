<?php
 
namespace App\Http\Controllers;
 
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
 
class ProductoController extends Controller
{
    /**
     * GET /api/productos
     * Retorna todos los productos
     */
    public function index(): JsonResponse
    {
        $productos = Producto::all();
 
        return response()->json([
            'success' => true,
            'data'    => $productos,
            'total'   => $productos->count(),
        ], 200);
    }
 
    /**
     * POST /api/productos
     * Crea un nuevo producto
     */
    public function store(Request $request): JsonResponse
    {
        // Validación de datos de entrada
        $validated = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio'      => 'required|numeric|min:0',
            'stock'       => 'nullable|integer|min:0',
            'categoria'   => 'nullable|string|max:100',
            'activo'      => 'nullable|boolean',
        ]);
 
        $producto = Producto::create($validated);
 
        return response()->json([
            'success' => true,
            'message' => 'Producto creado exitosamente',
            'data'    => $producto,
        ], 201); // 201 = Created
    }
 
    /**
     * GET /api/productos/{id}
     * Retorna un producto específico
     */
    public function show(string $id): JsonResponse
    {
        $producto = Producto::find($id);
 
        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado',
            ], 404);
        }
 
        return response()->json([            'success' => true,
            'data'    => $producto,
        ], 200);
    }
 
    /**
     * PUT /api/productos/{id}
     * Actualiza un producto existente
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $producto = Producto::find($id);
 
        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado',
            ], 404);
        }
 
        $validated = $request->validate([
            'nombre'      => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string',
            'precio'      => 'sometimes|numeric|min:0',
            'stock'       => 'nullable|integer|min:0',
            'categoria'   => 'nullable|string|max:100',
            'activo'      => 'nullable|boolean',
        ]);
 
        $producto->update($validated);
 
        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado exitosamente',
            'data'    => $producto,
        ], 200);
    }
 
    /**
     * DELETE /api/productos/{id}
     * Elimina un producto
     */
    public function destroy(string $id): JsonResponse
    {
        $producto = Producto::find($id);
 
        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado',
            ], 404);
        }
 
        $producto->delete();
 
        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado exitosamente',
        ], 200);
    }
}

