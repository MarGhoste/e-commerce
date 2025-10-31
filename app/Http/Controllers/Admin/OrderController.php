<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Muestra una lista de recursos (Órdenes).
     * Vue usará esto para el listado de pedidos.
     */
    public function index()
    {
        // Obtener órdenes, ordenadas por la más reciente, con información clave.
        $orders = Order::with('user', 'items') // Cargar el cliente y los ítems de la orden
            ->latest() // Ordenar por la más reciente
            ->paginate(20);

        return response()->json($orders);
    }

    /**
     * Muestra el recurso especificado (Detalle de la Orden).
     */
    public function show(Order $order)
    {
        // Cargar todos los detalles necesarios para la vista de la orden
        $order->load('user', 'items.product');

        return response()->json($order);
    }

    /**
     * Actualiza el estado de la orden.
     * El administrador cambia el estado (ej. de 'paid' a 'shipped').
     */
    public function update(Request $request, Order $order)
    {
        // 1. VALIDACIÓN
        $request->validate([
            // La única información que el admin debería cambiar aquí es el estado.
            'status' => 'required|in:pending,paid,shipped,delivered,cancelled',
        ]);

        // 2. LÓGICA DE NEGOCIO (Actualización)
        // Ejemplo: Si el estado es 'shipped' (enviado), podrías registrar la fecha.

        $order->update($request->only('status'));

        // 3. DEVOLVER LA RESPUESTA
        return response()->json($order->load('user', 'items'));
    }

    // El método 'store' (crear orden) no es necesario aquí, ya que el cliente lo maneja.
    // El método 'destroy' (eliminar) generalmente solo cambia el estado a 'cancelled', rara vez elimina el registro.
}
