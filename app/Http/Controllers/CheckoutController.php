<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Inertia\Inertia;
use Laravel\Cashier\Cashier; // Si necesitas acceder a la Facade de Cashier

class CheckoutController extends Controller
{
    public function index()
    {
        return Inertia::render('Checkout/CheckoutPage', [
            'stripeKey' => config('services.stripe.key')
        ]);
    }

    // Método para obtener el carrito actual (puedes reutilizar la lógica de CartController)
    protected function getCurrentCart()
    {
        // En checkout, asumimos que el usuario está autenticado
        return Cart::where('user_id', Auth::id())
            ->where('status', 'active')
            ->with('items.product') // Cargar items y sus productos
            ->first();
    }

    /**
     * Procesa el pago y crea la orden de compra.
     */
    public function process(Request $request)
    {
        // 1. Validar la dirección de envío y el método de pago
        $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip' => 'required|string|max:10',
            // Este ID es la clave de pago generada por el frontend de Stripe
            'payment_method_id' => 'required|string',
        ]);

        $user = Auth::user();
        $cart = $this->getCurrentCart();

        if (!$cart || $cart->items->isEmpty()) {
            return back()->withErrors('El carrito está vacío o no se encontró.');
        }

        // 2. Calcular el total (¡IMPORTANTE! Siempre calcula el total en el backend)
        $totalCents = $cart->items->sum(function ($item) {
            // Convierte el total a centavos, el formato que usa Stripe (100 = $1.00)
            return $item->quantity * ($item->product->price * 100);
        });

        // 3. Procesar el Pago con Laravel Cashier
        try {
            DB::beginTransaction();

            // 1. Definir la URL de Retorno (para métodos de pago que redirigen)
            $returnUrl = route('order.confirmation', [
                // Stripe necesita el ID de un objeto ya existente.
                // Como la orden no existe aún, usamos un placeholder o la URL base.
                // Usaremos la URL base por simplicidad.
                'order' => 'RETURN_ID_PLACEHOLDER'
            ], true);
            // ^^^ El 'true' al final convierte la ruta a URL absoluta (https://...)

            // 1. Procesar el cargo con Laravel Cashier (Esto sigue igual)
            $charge = $user->charge(
                $totalCents,
                $request->payment_method_id,
                ['description' => 'Orden de compra del E-commerce', 'return_url' => $returnUrl,]
            );

            // 2. Crear la Orden de Compra (Usando TUS NOMBRES de columna)
            $order = Order::create([
                'user_id' => $user->id,

                // ✅ TUS CAMPOS DE TOTAL Y ESTADO
                'total_amount' => $totalCents / 100, // Usar total_amount
                'status' => 'paid', // Usar 'paid' ya que el pago fue exitoso
                'order_number' => 'ORD-' . time(), // Generar un número de orden simple

                // ✅ TUS CAMPOS DE DIRECCIÓN (Se asume que vienen del request)
                'shipping_address_line_1' => $request->address, // Asumiendo 'address' del form
                'shipping_city' => $request->city,
                'shipping_zip' => $request->zip,

                //CAMPO DE MÉTODO DE PAGO
                'payment_method' => 'Stripe',
            ]);

            // 3. Mover los Artículos del Carrito a la Orden
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    // TUS CAMPOS DE PRECIO
                    'price' => $item->product->price, // Usar 'price' en lugar de 'price_at_purchase'
                ]);
            }

            // 6. Limpiar el Carrito (cambiar status a 'checkout' o 'completed')
            $cart->status = 'ordered';
            $cart->save();

            DB::commit();

            return redirect()->route('order.confirmation', ['order' => $order->id])
                ->with('success', 'Pago procesado y orden creada con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Retorna un error de validación o un mensaje amigable al usuario
            return back()->withErrors(['payment' => 'Error en el pago: ' . $e->getMessage()]);
        }
    }
}
