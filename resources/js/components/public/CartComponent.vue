<template>
    <div class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl mb-8">Mi Carrito</h1>

            <div v-if="cartStore.isLoading" class="text-center py-20">
                <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-cyan-500 mx-auto"></div>
                <p class="mt-4 text-lg text-gray-400">Cargando tu carrito...</p>
            </div>

            <div v-else-if="cartStore.items.length === 0" class="text-center py-20 bg-gray-800 rounded-lg">
                <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                <h3 class="mt-2 text-lg font-medium text-white">Tu carrito está vacío</h3>
                <p class="mt-1 text-sm text-gray-400">Parece que aún no has añadido nada. ¡Explora nuestros productos!</p>
                <div class="mt-6">
                    <a href="/" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-cyan-500">
                        Ver productos
                    </a>
                </div>
            </div>

            <form v-else class="lg:grid lg:grid-cols-12 lg:items-start lg:gap-x-12 xl:gap-x-16">
                <section aria-labelledby="cart-heading" class="lg:col-span-7">
                    <h2 id="cart-heading" class="sr-only">Productos en tu carrito</h2>
                    <ul role="list" class="border-t border-b border-gray-700 divide-y divide-gray-700">
                        <li v-for="item in cartStore.items" :key="item.id" class="flex py-6 sm:py-10">
                            <div class="flex-shrink-0">
                                <img :src="getImageUrl(item.product)" :alt="item.product.name" class="w-24 h-24 rounded-md object-center object-cover sm:w-32 sm:h-32">
                            </div>
                            <div class="ml-4 flex-1 flex flex-col justify-between sm:ml-6">
                                <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                                    <div>
                                        <div class="flex justify-between">
                                            <h3 class="text-sm">
                                                <a :href="`/products/${item.product.slug}`" class="font-medium text-white hover:text-gray-300">{{ item.product.name || 'Producto Eliminado' }}</a>
                                            </h3>
                                        </div>
                                        <p class="mt-1 text-sm font-medium text-gray-300">${{ item.price.toFixed(2) }}</p>
                                    </div>
                                    <div class="mt-4 sm:mt-0 sm:pr-9">
                                        <label :for="`quantity-${item.id}`" class="sr-only">Cantidad, {{ item.product.name }}</label>
                                        <input :id="`quantity-${item.id}`" type="number" min="1" :value="item.quantity" @change="updateQuantity(item, $event.target.value)" class="w-20 bg-gray-800 border border-gray-600 rounded-md py-1.5 text-base leading-5 font-medium text-white text-center focus:outline-none focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500">
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center justify-between text-sm">
                                    <p class="text-gray-400">Subtotal: <span class="font-medium text-white">${{ (item.price * item.quantity).toFixed(2) }}</span></p>
                                    <button @click.prevent="cartStore.removeCartItem(item)" type="button" class="font-medium text-cyan-500 hover:text-cyan-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                        <span>Eliminar</span>
                                    </button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </section>

                <!-- Order summary -->
                <section aria-labelledby="summary-heading" class="mt-16 bg-gray-800 rounded-lg px-4 py-6 sm:p-6 lg:p-8 lg:mt-0 lg:col-span-5">
                    <h2 id="summary-heading" class="text-lg font-medium text-white">Resumen del Pedido</h2>
                    <dl class="mt-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-400">Subtotal</dt>
                            <dd class="text-sm font-medium text-white">${{ cartStore.subtotal.toFixed(2) }}</dd>
                        </div>
                        <div class="border-t border-gray-700 pt-4 flex items-center justify-between">
                            <dt class="text-base font-medium text-white">Total del Pedido</dt>
                            <dd class="text-base font-medium text-white">${{ cartStore.subtotal.toFixed(2) }}</dd>
                        </div>
                    </dl>
                    <div class="mt-6">
                        <button @click="proceedToCheckout" type="button" class="w-full bg-cyan-600 border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-cyan-500">Proceder al Pago</button>
                    </div>
                </section>
            </form>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useCartStore } from '../../stores/cart';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

// ✅ Inicializar el store
const cartStore = useCartStore();

// Función para obtener la URL de la imagen de forma segura
const getImageUrl = (product) => {
    if (!product || !product.image_url) {
        return '/images/placeholder.png';
    }
    const imageUrl = product.image_url;
    // Si ya es una URL completa o ya tiene /storage/, la usamos directamente
    if (imageUrl.startsWith('http') || imageUrl.startsWith('/storage/')) {
        return imageUrl;
    }
    // De lo contrario, añadimos el prefijo /storage/
    return `/storage/${imageUrl}`;
};


// Función para manejar el cambio de cantidad
const updateQuantity = (item, quantity) => {
    // Aseguramos que la cantidad sea un número entero
    const newQuantity = parseInt(quantity, 10);
    if (newQuantity >= 0) {
        // Llamar a la acción del store
        cartStore.updateCartItemQuantity(item, newQuantity);
    }
};

// Función para navegar a la vista de Checkout
const proceedToCheckout = () => {
    // Usamos la ruta nombrada que definimos antes: Route::get('/checkout', ...)
    router.visit(route('checkout.view')); 
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