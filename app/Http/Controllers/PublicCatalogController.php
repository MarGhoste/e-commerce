<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia; // Asegúrate de tener este import

class PublicCatalogController extends Controller
{
    public function index(Request $request)
    {
        // Lógica de filtrado (búsqueda, categoría, precio, etc.)
        $query = Product::where('is_active', true)
            ->with('category') // Cargar la relación de categoría
            ->orderBy('created_at', 'desc');

        // Filtrar por categoría (ejemplo simple)
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Paginación de los productos
        $products = $query->paginate(12); // 12 productos por página

        // Obtener categorías para el sidebar (solo las que tienen productos activos)
        $categories = Category::has('products')->get(['id', 'name']);

        return response()->json([
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function showCatalogPage(Request $request)
    {
        // Lógica de filtrado (búsqueda, categoría, precio, etc.)
        $query = Product::where('is_active', true)
            ->with('category') // Cargar la relación de categoría
            ->orderBy('created_at', 'desc');

        // Filtrar por categoría (ejemplo simple)
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Paginación de los productos
        $products = $query->paginate(12); // 12 productos por página

        // Obtener categorías para el sidebar (solo las que tienen productos activos)
        $categories = Category::has('products')->get(['id', 'name']);

        // Inertia renderiza pages/CatalogPage.vue y pasa los datos
        return Inertia::render('CatalogPage', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    /**
     * Muestra la página de detalle de un producto (Ruta: /products/{slug}).
     */
    public function showProductDetail(string $slug)
    {
        $product = Product::with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Inertia renderiza pages/ProductDetail.vue y pasa el producto
        return Inertia::render('ProductDetail', [
            'product' => $product,
        ]);
    }

    /**
     * Muestra la página del carrito (Ruta: /cart).
     */
    public function showCartPage()
    {
        // Inertia renderiza pages/CartPage.vue
        return Inertia::render('CartPage');
    }
}
