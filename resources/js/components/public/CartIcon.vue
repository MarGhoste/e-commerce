<template>
    <button @click="goToCart" 
            class="relative p-2 rounded-full hover:bg-gray-100 transition duration-150 ease-in-out">
        
        <svg xmlns="http://www.w3.org/2000/svg" 
             :class="{ 'text-blue-600': cartStore.totalQuantity > 0, 'text-gray-700': cartStore.totalQuantity === 0 }"
             class="h-7 w-7 transition-colors duration-200" 
             fill="none" 
             viewBox="0 0 24 24" 
             stroke="currentColor" 
             stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>

        <span v-if="cartStore.totalQuantity > 0" 
              class="absolute top-0 right-0 inline-flex items-center justify-center 
                     bg-blue-500 text-white rounded-full 
                     h-4 w-4 text-[9px] font-bold 
                     transform translate-x-1/3 -translate-y-1/3 
                     ring-2 ring-white animate-pulse-once" 
              title="Carrito con artículos">
            </span>

    </button>
</template>

<script setup lang="ts">
import { useCartStore } from '../../stores/cart'; 
import { onMounted } from 'vue'; 

const cartStore = useCartStore();

const goToCart = () => {
    window.location.href = '/checkout/cart'; 
};

onMounted(() => {
    if (!cartStore.isInitialized) {
        cartStore.fetchCart();
    }
});
</script>

<style scoped>
/* Define una animación simple para el "pop" */
@keyframes pulse-once {
  0% {
    transform: translate(-33%, -33%) scale(0.8);
    opacity: 0.7;
  }
  50% {
    transform: translate(-33%, -33%) scale(1.1);
    opacity: 1;
  }
  100% {
    transform: translate(-33%, -33%) scale(1);
    opacity: 1;
  }
}

.animate-pulse-once {
  animation: pulse-once 0.3s ease-out forwards;
}
</style>