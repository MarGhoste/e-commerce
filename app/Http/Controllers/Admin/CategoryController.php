<?php
// app/Http/Controllers/Admin/CategoryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Muestra una lista de recursos (Categorías).
     */
    public function index()
    {
        // Obtener todas las categorías, simple y sin paginar, para listados
        $categories = Category::all();
        return response()->json($categories);
    }

    /**
     * Almacena un recurso recién creado (Crear Categoría).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return response()->json($category, 201); // 201 Created
    }

    /**
     * Muestra el recurso especificado.
     */
    public function show(Category $category)
    {
        return response()->json($category);
    }

    /**
     * Actualiza el recurso especificado (Editar Categoría).
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            // Excluye la categoría actual de la verificación de unicidad
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return response()->json($category);
    }

    /**
     * Elimina el recurso especificado (Eliminar Categoría).
     */
    public function destroy(Category $category)
    {
        // BUENA PRÁCTICA: Antes de eliminar, asegúrate de que no haya productos asociados.
        if ($category->products()->count() > 0) {
            return response()->json(['message' => 'No se puede eliminar la categoría porque tiene productos asociados.'], 409); // 409 Conflict
        }

        $category->delete();

        return response()->json(null, 204);
    }
}
