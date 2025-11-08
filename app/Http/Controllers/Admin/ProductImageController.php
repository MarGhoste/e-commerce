<?php

namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;

use App\Models\ProductImage;

use App\Models\Product; // Para asociar la imagen al producto

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage; // Para manejar el almacenamiento de archivos



class ProductImageController extends Controller

{

    /**

     * Almacena una nueva imagen para un producto.

     */

    public function store(Request $request, Product $product)

    {

        // 1. VALIDACIÓN

        $request->validate([

            // 'image' es el nombre del campo en el formulario de Vue

            'image' => 'required|image|max:2048', // 2MB máximo

        ]);



        // 2. PROCESAMIENTO Y ALMACENAMIENTO DE LA IMAGEN



        // Guarda el archivo en el disco 'public'

        // Y devuelve la ruta al archivo guardado.

        $path = $request->file('image')->store('products/' . $product->id, 'public');



        // 3. CREAR EL REGISTRO EN LA BD

        $image = ProductImage::create([
            'product_id' => $product->id,
            'url' => $path, // Guardamos la RUTA INTERNA: 'products/1/archivo.jpg'
            'is_main' => false,
        ]);



        // 4. RESPUESTA

        return response()->json($image, 201); // 201 Created

    }



    /**

     * Establece una imagen como la principal del producto.

     */

    public function setMain(ProductImage $image)

    {

        // 1. Desactivar todas las demás imágenes principales para este producto

        ProductImage::where('product_id', $image->product_id)

            ->update(['is_main' => false]);



        // 2. Establecer la imagen actual como principal

        $image->update(['is_main' => true]);



        return response()->json(['message' => 'Imagen principal actualizada.', 'image' => $image]);
    }



    /**

     * Elimina el registro de la imagen de la BD y el archivo físico.

     */

    public function destroy(ProductImage $image)

    {

        // 1. Eliminar el archivo físico (reemplazar 'storage/' por 'public/' si es necesario)

        $path = str_replace('/storage', 'public', $image->url);

        Storage::delete($path);



        // 2. Eliminar el registro de la base de datos

        $image->delete();



        return response()->json(null, 204); // 204 No Content

    }
}
