<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage; // Para gestionar las imágenes
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Para manejar el almacenamiento de archivos
use Illuminate\Support\Str; // Para generar el slug

class ProductController extends Controller
{
    /**
     * Muestra una lista de recursos (Productos).
     */
    public function index()
    {
        // Precargar 'category' y 'mainImage' (usando la relación que definiste en Product.php)
        $products = Product::with('category', 'mainImage')->paginate(15);

        return response()->json($products);
    }

    /**
     * Almacena un recurso recién creado (Crear Producto).
     */
    public function store(Request $request)
    {
        // 1. Validar los datos de entrada
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            // ✅ VALIDACIÓN DE IMAGEN: Es obligatoria al crear un producto
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        // 2. Crear el Slug y guardar el producto
        $request->merge(['slug' => Str::slug($request->name)]);

        // Excluimos 'image' antes de crear el producto
        $product = Product::create($request->except('image'));

        // 3. GUARDAR LA IMAGEN Y CREAR EL REGISTRO
        if ($request->hasFile('image')) {
            // Guardar el archivo en el disco 'public' dentro de la carpeta 'products'
            // Esto devuelve la ruta relativa: 'products/hash_del_archivo.webp'
            $imagePath = $request->file('image')->store('products', 'public');

            // Crear el registro en la tabla product_images
            ProductImage::create([
                'product_id' => $product->id,
                'url' => $imagePath, // Guardamos la ruta relativa
                'is_main' => true, // La imagen subida es la principal
            ]);
        }

        // 4. Devolver la respuesta de éxito
        return response()->json($product->load('mainImage'), 201);
    }

    /**
     * Muestra el recurso especificado (Detalle de Producto).
     */
    public function show(Product $product)
    {
        // Precargar la imagen principal (mainImage) y todas las imágenes (images)
        $product->load('category', 'mainImage', 'images');
        return response()->json($product);
    }

    /**
     * Actualiza el recurso especificado (Editar Producto).
     */
    public function update(Request $request, Product $product)
    {
        // 1. Validar datos
        $request->validate([
            // unique:products,name,[ID],id (ignora el ID actual)
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            // ✅ IMAGEN EN UPDATE: La imagen es OPCIONAL, si se envía, se valida
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        // 2. Actualizar campos de producto
        // Usamos $request->except('image') porque la imagen no es un campo directo de la tabla products
        $product->update($request->except('image'));

        // 3. LÓGICA DE ACTUALIZACIÓN DE IMAGEN (Si se subió una nueva)
        if ($request->hasFile('image')) {

            // a) Eliminar imágenes viejas (principal)
            $oldMainImage = $product->mainImage;

            if ($oldMainImage) {
                // Borrar el archivo físico del storage
                Storage::disk('public')->delete($oldMainImage->url);
                // Borrar el registro de la base de datos
                $oldMainImage->delete();
            }

            // b) Subir y registrar la nueva imagen
            $imagePath = $request->file('image')->store('products', 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'url' => $imagePath,
                'is_main' => true,
            ]);
        }

        // 4. Devolver el producto actualizado con sus nuevas imágenes
        return response()->json($product->load('mainImage'));
    }

    /**
     * Elimina el recurso especificado (Eliminar Producto).
     */
    public function destroy(Product $product)
    {
        // Opcional: Eliminar archivos de imagen antes de borrar el producto
        // (Aunque onDelete('cascade') en la migración borrará los registros de la DB)

        // Eliminar archivos físicos asociados
        $product->images->each(function (ProductImage $image) {
            Storage::disk('public')->delete($image->url);
        });

        $product->delete();

        return response()->json(null, 204);
    }
}
