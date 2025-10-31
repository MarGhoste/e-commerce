<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Muestra una lista de recursos (Productos).
     * Vue usará esto para llenar la tabla principal.
     */
    public function index()
    {
        // Obtener productos paginados (ej. 15 por página) con su categoría
        $products = Product::with('category', 'images')->paginate(15);

        // Devolver la lista como JSON (API para Vue)
        return response()->json($products);
    }

    /**
     * Almacena un recurso recién creado (Crear Producto).
     * Vue envía el formulario por POST aquí.
     */
    public function store(Request $request)
    {
        // 1. Validar los datos de entrada (name, price, stock, category_id, etc.)
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name', // Nombre es obligatorio y único
            'category_id' => 'required|exists:categories,id', // Debe existir en la tabla 'categories'
            'price' => 'required|numeric|min:0.01', // Debe ser un número positivo
            'stock' => 'required|integer|min:0', // Debe ser un entero no negativo
            'description' => 'nullable|string', // Opcional
            'is_active' => 'nullable|boolean', // Opcional, si no se envía es 'false' o 'null'
        ]);
        // 2. Crear el Slug a partir del nombre (función de apoyo)
        $request->merge(['slug' => \Illuminate\Support\Str::slug($request->name)]);

        // 3. Crear el producto
        $product = Product::create($request->all());

        // 4. Devolver la respuesta de éxito
        return response()->json($product, 201); // 201 Created
    }

    /**
     * Muestra el recurso especificado (Detalle de Producto).
     */
    public function show(Product $product)
    {
        // Cargar las relaciones necesarias para el detalle (imágenes, categoría)
        $product->load('category', 'images');
        return response()->json($product);
    }

    /**
     * Actualiza el recurso especificado (Editar Producto).
     * Vue envía el formulario por PUT/PATCH aquí.
     */
    public function update(Request $request, Product $product)
    {
        // 1. Validar datos
        // ... (misma validación que en 'store')
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name', // Nombre es obligatorio y único
            'category_id' => 'required|exists:categories,id', // Debe existir en la tabla 'categories'
            'price' => 'required|numeric|min:0.01', // Debe ser un número positivo
            'stock' => 'required|integer|min:0', // Debe ser un entero no negativo
            'description' => 'nullable|string', // Opcional
            'is_active' => 'nullable|boolean', // Opcional, si no se envía es 'false' o 'null'
        ]);

        // 2. Actualizar el producto
        $product->update($request->all());

        // 3. Devolver el producto actualizado
        return response()->json($product);
    }

    /**
     * Elimina el recurso especificado (Eliminar Producto).
     */
    public function destroy(Product $product)
    {
        $product->delete();

        // 204 No Content (Respuesta estándar para una eliminación exitosa sin contenido de retorno)
        return response()->json(null, 204);
    }
}
