<template>
    <Header />
    <div class="bg-gray-900 text-white py-12">
         <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
             <h1 class="text-3xl font-extrabold text-white mb-8 tracking-tight">Finalizar Compra</h1>
 
             <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
                 
                 <div class="lg:col-span-2 bg-gray-800 p-8 rounded-xl shadow-lg">
                     <h2 class="text-2xl font-bold mb-6 text-cyan-400">Información de Envío</h2>
                     
                     <div class="space-y-6">
                         <div>
                             <label for="address" class="block text-sm font-medium text-gray-300 mb-1">Dirección</label>
                             <input type="text" id="address" v-model="form.address" required class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 p-3">
                         </div>
 
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                             <div>
                                 <label for="city" class="block text-sm font-medium text-gray-300 mb-1">Ciudad</label>
                                 <input type="text" id="city" v-model="form.city" required class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 p-3">
                             </div>
                             
                             <div>
                                 <label for="zip" class="block text-sm font-medium text-gray-300 mb-1">Código Postal</label>
                                 <input type="text" id="zip" v-model="form.zip" required
                                     class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-cyan-500 focus:ring-cyan-500 p-3">
                             </div>
                         </div>
                     </div>
                     
                     <h2 class="text-2xl font-bold mt-10 mb-6 text-cyan-400">Detalles de Pago</h2>
                     
                     <div id="card-element" class="border border-gray-600 p-4 rounded-md bg-gray-700">
                         <!-- Stripe Card Element will be inserted here -->
                     </div>
 
                     <p v-if="paymentError" class="mt-4 text-red-500 font-medium text-sm">{{ paymentError }}</p>
                 </div>
 
                 <div class="lg:col-span-1 bg-gray-800 p-8 rounded-xl shadow-lg flex flex-col">
                     <h3 class="text-2xl font-bold mb-6 text-cyan-400">Resumen del Pedido</h3>
                     
                     <div class="space-y-4 flex-grow">
                         <div v-for="item in cartStore.items" :key="item.id" class="flex justify-between items-center text-gray-300">
                             <span class="font-medium">{{ item.product.name }} <span class="text-gray-400">x {{ item.quantity }}</span></span>
                             <span class="font-semibold">${{ (item.price * item.quantity).toFixed(2) }}</span>
                         </div>
                     </div>
 
                     <div class="mt-6 border-t border-gray-700 pt-6">
                         <div class="flex justify-between text-gray-300 font-medium">
                             <span>Subtotal:</span>
                             <span>${{ totalAmount.toFixed(2) }}</span>
                         </div>
                         <div class="flex justify-between text-gray-300 font-medium mt-2">
                             <span>Envío:</span>
                             <span>Gratis</span>
                         </div>
 
                         <div class="flex justify-between text-white font-bold text-xl mt-4 pt-4 border-t border-gray-700">
                             <span>Total:</span>
                             <span>${{ totalAmount.toFixed(2) }}</span>
                         </div>
                     </div>
                     
                     <button type="submit" :disabled="form.processing"
                             class="mt-8 w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-cyan-500 disabled:opacity-60 disabled:cursor-not-allowed transition-colors duration-300">
                         <span v-if="form.processing">Procesando...</span>
                         <span v-else>Pagar Ahora</span>
                     </button>
                 </div>
             </form>
         </div>
    </div>
     <Footer />
 </template>
 
 <script setup>
 //import AppLayout from '@/Layouts/AppLayout.vue';
 import { useCartStore } from '@/stores/cart';
 import { computed, ref, onMounted } from 'vue';
 import { useForm, usePage } from '@inertiajs/vue3';
 import Header from '@/components/Header.vue'; 
 import Footer from '@/components/Footer.vue';
 
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
     // 1. Obtener la clave de Inertia.
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
         style: {
             base: {
                 color: '#fff',
                 fontFamily: 'Inter, sans-serif',
                 fontSmoothing: 'antialiased',
                 fontSize: '16px',
                 '::placeholder': {
                     color: '#aab7c4'
                 },
             },
             invalid: {
                 color: '#ef4444',
                 iconColor: '#ef4444'
             }
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
 