<template>
    
        <div class="max-w-3xl mx-auto p-8 bg-white shadow-lg rounded-xl mt-10 text-center">
            
            <svg class="w-20 h-20 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            
            <h1 class="text-3xl font-extrabold text-gray-800 mb-2">¡Tu Compra ha sido Exitosa!</h1>
            
            <p class="text-lg text-gray-600 mb-6">Hemos recibido tu pedido y el pago se ha procesado correctamente.</p>
            
            <div class="border-t pt-4 mt-4">
                <h2 class="text-xl font-semibold text-gray-700 mb-3">Detalles de la Orden #{{ order.id }}</h2>
                <p class="text-gray-700">Total Pagado: <span class="font-bold text-green-600">${{ parseFloat(order.total_amount).toFixed(2) }}</span></p>
                <p class="text-gray-500 mt-1">Estado: <span class="capitalize">{{ order.status }}</span></p>
                
                <div class="mt-4 text-left">
                    <h3 class="font-semibold mb-2">Artículos:</h3>
                    <ul class="space-y-1">
                        <li v-for="item in order.items" :key="item.id" class="flex justify-between text-sm text-gray-600">
                            <span>{{ item.quantity }} x {{ item.product.name }}</span>
                            <span>${{ parseFloat(item.price).toFixed(2) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="mt-8">
                <a href="/" class="text-blue-600 hover:text-blue-800 font-medium">Volver al Catálogo</a>
            </div>
        </div>
   
</template>

<script setup>
//import AppLayout from '../../layouts/AppLayout.vue';

// 💡 Define la prop que Inertia pasará
const props = defineProps({
    order: {
        type: Object,
        required: true,
    },
});

</script>