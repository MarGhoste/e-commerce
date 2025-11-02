<template>
    <button @click="goToCart" class="relative p-2 rounded-full hover:bg-gray-100 transition duration-150 ease-in-out">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>

        <span v-if="cartStore.totalQuantity > 0" 
              class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
            {{ cartStore.totalQuantity }}
        </span>
    </button>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useCartStore } from '../../stores/cart'; 
//import { useRouter } from 'vue-router'; // Si usas Vue Router para la navegación

// ✅ Inicializar el store (acceso a todos los datos y acciones)
const cartStore = useCartStore();
// const router = useRouter(); // Descomentar si usas Vue Router

/**
 * Función para navegar a la vista del carrito.
 */
const goToCart = () => {
    // Implementación de navegación:
    // 1. Si usas Vue Router:
    // router.push({ name: 'cart' }); 

    // 2. Si es una aplicación Blade/Laravel simple:
    window.location.href = '/checkout/cart'; // Cambia esto por tu ruta de carrito
};

// Asegurar que el carrito se cargue si la aplicación aún no lo ha hecho.
onMounted(() => {
    // Comprueba si el store ha sido inicializado antes de hacer la llamada API
    if (!cartStore.isInitialized) {
        cartStore.fetchCart();
    }
});
</script>