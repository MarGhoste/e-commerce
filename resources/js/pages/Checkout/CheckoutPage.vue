<template>
    <AppLayout>
        <form @submit.prevent="submit" class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="md:col-span-2 bg-white p-6 shadow rounded-lg">
                <h2 class="text-xl font-semibold mb-4">Información de Envío</h2>
                
                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" id="address" v-model="form.address" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="mb-4">
                        <label for="city" class="block text-sm font-medium text-gray-700">Ciudad</label>
                        <input type="text" id="city" v-model="form.city" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="zip" class="block text-sm font-medium text-gray-700">Código Postal</label>
                        <input type="text" id="zip" v-model="form.zip" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
                
                <h2 class="text-xl font-semibold mb-4">Detalles de Pago</h2>
                
                <div id="card-element" class="border border-gray-300 p-3 rounded-md">
                    </div>

                <p v-if="paymentError" class="mt-4 text-red-600 font-medium">{{ paymentError }}</p>
            </div>

            <div class="md:col-span-1 bg-gray-50 p-6 shadow rounded-lg flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Resumen del Pedido</h3>
                    
                    <div v-for="item in cartStore.items" :key="item.id" class="flex justify-between text-sm mb-1">
                        <span>{{ item.quantity }} x {{ item.product.name }}</span>
                        <span>${{ (item.price * item.quantity).toFixed(2) }}</span>
                    </div>

                    <hr class="my-3">
                    
                    <div class="flex justify-between text-base font-semibold">
                        <span>Subtotal:</span>
                        <span>${{ totalAmount.toFixed(2) }}</span>
                    </div>

                    <hr class="my-3 border-t-2 border-gray-300">

                    <p class="text-xl font-bold text-blue-600">Total a pagar: ${{ totalAmount.toFixed(2) }}</p> 
                </div>
                
                <button type="submit" :disabled="form.processing"
                        class="mt-6 w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                    Pagar Ahora
                </button>
            </div>
        </form>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useCartStore } from '@/stores/cart';
import { computed, ref, onMounted } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';

// --- Datos de Componente ---
const cartStore = useCartStore();
const page = usePage();
const totalAmount = computed(() => cartStore.subtotal);

const form = useForm({
    address: '',
    city: 'Lima', 
    zip: '15001',
    payment_method_id: null,
});

const paymentError = ref(null);
let stripe = null;
let cardElement = null;

// --- Funciones de Stripe ---
onMounted(async () => {
    // 1. Obtener la clave de Inertia. Asegúrate de que se pase en Laravel
    const stripeKey = page.props.stripeKey; 
    
    if (!stripeKey) {
        paymentError.value = "Error: La clave pública de Stripe no está configurada.";
        return;
    }

    // 2. Inicializar Stripe
    stripe = Stripe(stripeKey);
    
    // 3. Crear el Elemento de Tarjeta (UI seguro de Stripe)
    const elements = stripe.elements();
    cardElement = elements.create('card', {
        style: { // Opcional: Estilos para que se vea bien
            base: { fontSize: '16px' }
        }
    });

    // 4. Montar el elemento en el contenedor
    cardElement.mount('#card-element');
});


// --- Lógica de Envío del Formulario ---
const submit = async () => {
    paymentError.value = null;
    form.processing = true;

    // 1. Crear el PaymentMethod a partir del elemento de tarjeta
    const { paymentMethod, error } = await stripe.createPaymentMethod({
        type: 'card',
        card: cardElement,
        billing_details: {
            // Puedes añadir email/nombre del usuario logueado aquí
        }
    });

    if (error) {
        // Mostrar errores de tarjeta
        paymentError.value = error.message;
        form.processing = false;
        return;
    }

    // 2. Si es exitoso, adjuntar el ID al formulario
    form.payment_method_id = paymentMethod.id;

    // 3. Enviar el formulario a Laravel
    form.post(route('checkout.process'), {
        onSuccess: () => {
            // Redirige al usuario al éxito o confirmación
            console.log('Pago exitoso, redirigiendo...');
        },
        onError: (errors) => {
            // Manejar errores devueltos por el CheckoutController
            paymentError.value = errors.payment || "Hubo un error al procesar la orden.";
        },
        onFinish: () => {
            form.processing = false;
        }
    });
};
</script>