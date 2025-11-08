<template>
    <Header />
    <div class="bg-gray-900 py-12 sm:py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow-2xl rounded-2xl p-8 sm:p-12 text-center overflow-hidden">
                
                <div class="relative">
                    <svg class="w-24 h-24 text-green-400 mx-auto mb-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h1 class="text-4xl font-extrabold text-white tracking-tight mb-3">¡Tu compra ha sido exitosa!</h1>
                    <p class="text-lg text-gray-300 max-w-2xl mx-auto">
                        Hemos recibido tu pedido y el pago se ha procesado correctamente. Recibirás una confirmación por correo electrónico en breve.
                    </p>
                </div>
                
                <div class="border-t border-gray-700 pt-6 mt-8 text-left">
                    <h2 class="text-xl font-bold text-cyan-400 mb-4">Resumen del Pedido #{{ order.id }}</h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-300">Monto Total:</span>
                            <span class="font-bold text-2xl text-green-400">${{ parseFloat(order.total_amount).toFixed(2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-300">Estado del Pedido:</span>
                            <span class="px-3 py-1 text-sm font-bold text-cyan-900 bg-cyan-400 rounded-full capitalize">{{ order.status }}</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 border-t border-gray-700 pt-6">
                        <h3 class="font-semibold text-cyan-400 mb-3">Artículos Comprados:</h3>
                        <ul class="space-y-3">
                            <li v-for="item in order.items" :key="item.id" class="flex justify-between items-center text-gray-400">
                                <span>
                                    <span class="font-semibold text-gray-300">{{ item.product.name }}</span>
                                    <span class="text-sm"> x {{ item.quantity }}</span>
                                </span>
                                <span class="font-medium text-gray-300">${{ parseFloat(item.price).toFixed(2) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="mt-10">
                    <a href="/" class="inline-block w-full sm:w-auto px-8 py-3 font-semibold text-white bg-cyan-600 rounded-lg shadow-md hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-cyan-500 transition-transform transform hover:scale-105">
                        Seguir Comprando
                    </a>
                </div>
            </div>
        </div>
    </div>
    <Footer />
</template>

<script setup>
import Header from '@/components/Header.vue'; 
import Footer from '@/components/Footer.vue';

const props = defineProps({
    order: {
        type: Object,
        required: true,
    },
});
</script>