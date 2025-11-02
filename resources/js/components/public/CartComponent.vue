<template>
    <div class="cart-wrapper">
        <h3 class="text-xl font-bold mb-4">Tu Carrito de Compras ({{ cartStore.totalQuantity }} productos)</h3>

        <div v-if="cartStore.isLoading" class="text-center py-8">
            Cargando carrito...
        </div>

        <div v-else-if="cartStore.items.length === 0" class="text-center py-8">
            <p>Tu carrito está vacío. ¡Añade algunos productos!</p>
        </div>

        <div v-else>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="text-left border-b">
                        <th class="py-2">Producto</th>
                        <th class="py-2">Precio</th>
                        <th class="py-2">Cantidad</th>
                        <th class="py-2">Subtotal</th>
                        <th class="py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in cartStore.items" :key="item.id" class="border-b">
                        <td class="py-4">{{ item.product.name || 'Producto Eliminado' }}</td>
                        <td class="py-4">${{ item.price.toFixed(2) }}</td>
                        <td class="py-4">
                            <input 
                                type="number" 
                                min="1"
                                :value="item.quantity" 
                                @change="updateQuantity(item, $event.target.value)"
                                class="w-16 border rounded text-center"
                            >
                        </td>
                        <td class="py-4">${{ (item.price * item.quantity).toFixed(2) }}</td>
                        <td class="py-4">
                            <button @click="cartStore.removeCartItem(item)" class="text-red-500 hover:text-red-700">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-6 flex justify-end">
                <div class="text-right">
                    <p class="text-xl font-bold">Subtotal: ${{ cartStore.subtotal.toFixed(2) }}</p>
                    <button class="mt-4 bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">
                        Proceder al Pago
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useCartStore } from '../stores/cart';

// ✅ Inicializar el store
const cartStore = useCartStore();

// Función para manejar el cambio de cantidad
const updateQuantity = (item, quantity) => {
    // Aseguramos que la cantidad sea un número entero
    const newQuantity = parseInt(quantity, 10);
    if (newQuantity >= 0) {
        // Llamar a la acción del store
        cartStore.updateCartItemQuantity(item, newQuantity);
    }
};

// Cargar el carrito al montar el componente
onMounted(() => {
    // Solo cargamos si no se ha hecho antes (para evitar llamadas duplicadas)
    if (!cartStore.isInitialized) {
        cartStore.fetchCart();
    }
});
</script>

<style scoped>
/* Estilos muy básicos, asumiendo Tailwind para las clases principales (w-full, text-xl, etc.) */
.cart-wrapper {
    max-width: 900px;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
}
</style>