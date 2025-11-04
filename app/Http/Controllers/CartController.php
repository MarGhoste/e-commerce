<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Encuentra o crea el carrito activo actual, ya sea para un usuario o una sesión.
     */
    protected function getCurrentCart()
    {
        if (Auth::check()) {
            // 1. Si está logueado: busca su carrito activo o crea uno
            return Cart::firstOrCreate(
                ['user_id' => Auth::id(), 'status' => 'active'],
                ['session_id' => null] // No necesitamos session_id si hay user_id
            );
        }

        // 2. Si es invitado: usa el session_id
        $sessionId = session()->get('cart_session_id');

        if (!$sessionId) {
            $sessionId = Str::uuid();
            session()->put('cart_session_id', $sessionId);
        }

        return Cart::firstOrCreate(
            ['session_id' => $sessionId, 'status' => 'active', 'user_id' => null]
        );
    }

    /**
     * Muestra el contenido del carrito actual.
     */
    public function index()
    {
        $cart = $this->getCurrentCart();
        $cart->load('items.product'); // Eager load relationships

        // Manually transform the items to create a safe and explicit data structure
        $items = $cart->items->map(function ($item) {
            // The product might be null if it was deleted, handle this case
            $productData = $item->product ? [
                'id' => $item->product->id,
                'name' => $item->product->name,
                'slug' => $item->product->slug,
            ] : null;

            return [
                'id' => $item->id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'product' => $productData,
            ];
        });

        // Return the transformed data
        return response()->json([
            'items' => $items,
            'subtotal' => $cart->subtotal,
        ]);
    }

    /**
     * Añade un producto al carrito o actualiza la cantidad.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getCurrentCart();
        $product = Product::findOrFail($request->product_id);

        // 1. Intentar encontrar el artículo existente
        $cartItem = $cart->items()
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // 2. Si existe: Aumentar la cantidad
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // 3. Si no existe: Crear nuevo artículo
            $cartItem = $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price, // Almacenar el precio actual
            ]);
            $cartItem->load('product'); // Cargar la relación para el JSON de respuesta
        }

        return response()->json([
            'message' => 'Producto añadido al carrito.',
            'cart_item' => $cartItem,
            'cart_subtotal' => $cart->subtotal, // Usar el accessor
        ], 201);
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0', // Permite 0 para eliminar
        ]);

        // 🛑 Seguridad: Asegurar que el artículo pertenece al carrito actual
        if ($cartItem->cart_id !== $this->getCurrentCart()->id) {
            return response()->json(['message' => 'Acceso denegado.'], 403);
        }

        $newQuantity = $request->quantity;

        if ($newQuantity <= 0) {
            // Si la cantidad es 0, elimina el artículo del carrito
            $cartItem->delete();

            $cart = $this->getCurrentCart(); // Recargar el carrito para el subtotal
            return response()->json([
                'message' => 'Artículo eliminado del carrito.',
                'action' => 'deleted',
                'cart_subtotal' => $cart->subtotal,
            ], 200);
        }

        // Si la cantidad es mayor a 0, actualiza
        $cartItem->quantity = $newQuantity;
        $cartItem->save();
        $cart = $this->getCurrentCart(); // Recargar el carrito para el subtotal

        return response()->json([
            'message' => 'Cantidad actualizada.',
            'action' => 'updated',
            'cart_item' => $cartItem,
            'cart_subtotal' => $cart->subtotal,
        ], 200);
    }

    /**
     * Elimina un artículo específico del carrito.
     */
    public function destroy(CartItem $cartItem)
    {
        // 🛑 Seguridad: Asegurar que el artículo pertenece al carrito actual
        if ($cartItem->cart_id !== $this->getCurrentCart()->id) {
            return response()->json(['message' => 'Acceso denegado.'], 403);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Artículo eliminado del carrito.'], 200);
    }

    // Nota: Deberíamos agregar una función 'update' para cambiar solo la cantidad.
}
