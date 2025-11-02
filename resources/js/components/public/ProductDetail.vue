<template>
    <div class="product-detail-card">
        <h1 class="text-3xl font-bold">{{ product.name }}</h1>
        <p class="text-xl text-green-600">${{ product.price }}</p>
        
        <div class="flex items-center space-x-2 my-4">
            <label for="quantity-input" class="font-medium">Cantidad:</label>
            <input 
                id="quantity-input"
                type="number"
                v-model.number="quantity" 
                min="1" 
                class="w-16 border rounded text-center p-1"
            />
        </div>

        <button 
            @click="addToCart" 
            :disabled="cartStore.isLoading"
            class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 disabled:opacity-50 transition"
        >
            {{ cartStore.isLoading ? 'Añadiendo...' : 'Añadir al Carrito' }}
        </button>
        
        <p v-if="addedSuccess" class="text-green-500 mt-2">¡Producto añadido!</p>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useCartStore } from '../../stores/cart'; // Asegúrate de que la ruta sea correcta
import { defineProps } from 'vue';

// 1. Props (Asumimos que la vista principal pasa el objeto 'product')
const props = defineProps({
    product: {
        type: Object,
        required: true
    }
});

// 2. Estado local
const cartStore = useCartStore(); // Inicializa el store
const quantity = ref(1);        // Cantidad a comprar
const addedSuccess = ref(false); // Estado para la notificación

/**
 * Llama a la acción de Pinia para añadir el producto.
 */
const addToCart = async () => {
    // 3. Llamar a la acción del store
    await cartStore.addProductToCart(props.product.id, quantity.value);

    // 4. Mostrar feedback al usuario
    addedSuccess.value = true;
    setTimeout(() => {
        addedSuccess.value = false;
    }, 2000); // Ocultar el mensaje después de 2 segundos
};
</script>

<style scoped>
/* Estilos básicos para la tarjeta */
.product-detail-card {
    max-width: 400px;
    margin: 40px auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
</style>