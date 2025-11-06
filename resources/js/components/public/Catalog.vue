<template>
    <div>
        <PromoCarousel />
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h2 class="text-4xl font-extrabold text-center mb-10 tracking-tight">😊Nuestros Productos😊</h2>

            <!-- Products Grid (now full width) -->
            <main>
                <div v-if="catalogStore.loading" class="flex justify-center items-center py-20">
                    <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-cyan-500"></div>
                </div>
                
                <div v-else-if="catalogStore.products.length === 0" class="text-center py-20 bg-gray-800 rounded-xl">
                    <p class="text-2xl font-bold text-gray-400">Sin Resultados</p>
                    <p class="text-gray-500 mt-2">No se encontraron productos para los filtros seleccionados.</p>
                </div>

                <div v-else class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-8">
                    <div v-for="product in catalogStore.products" :key="product.id"
                         class="bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-700 group transform hover:-translate-y-2 transition-transform duration-300">
                        
                        <a :href="`/products/${product.slug}`" class="block h-56 overflow-hidden">
                            <img :src="product.image_url || '/images/placeholder.png'" 
                                 :alt="product.name" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-in-out"
                                 loading="lazy">
                        </a>
                        
                        <div class="p-5">
                            <p class="text-sm text-cyan-400 mb-1">{{ product.category ? product.category.name : 'Varios' }}</p>
                            <h3 class="text-xl font-bold h-16 overflow-hidden group-hover:text-cyan-300 transition-colors duration-300">
                                <a :href="`/products/${product.slug}`">{{ product.name }}</a>
                            </h3>
                            
                            <div class="mt-4 flex justify-between items-baseline">
                                <p class="text-3xl font-black tracking-tighter text-white">${{ product.price.toFixed(2) }}</p>
                                <a :href="`/products/${product.slug}`" class="text-gray-400 hover:text-white text-sm">Ver más</a>
                            </div>
                            
                            <div class="mt-6">
                                <button 
                                    @click="addToCart(product.id)"
                                    :disabled="isAdding"
                                    class="w-full flex items-center justify-center bg-cyan-500 text-gray-900 py-3 px-4 font-bold rounded-lg hover:bg-cyan-400 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.922.778h9.446a1 1 0 00.922-.778L16.78 3H17a1 1 0 000-2H3zM6.143 6l.895 3.582A1 1 0 008 11h6a1 1 0 00.962-.741L16.857 6H6.143zM5 12a2 2 0 100 4 2 2 0 000-4zm10 0a2 2 0 100 4 2 2 0 000-4z" /></svg>
                                    Añadir al Carrito
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-12 flex justify-center space-x-3" v-if="catalogStore.products.length > 0">
                    <button 
                        @click="catalogStore.fetchProducts(catalogStore.pagination.current_page - 1)" 
                        :disabled="catalogStore.pagination.current_page === 1"
                        class="px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    >Anterior</button>
                    <span class="py-2 px-4 bg-gray-700 rounded-lg">Página {{ catalogStore.pagination.current_page }} de {{ catalogStore.pagination.last_page }}</span>
                    <button 
                        @click="catalogStore.fetchProducts(catalogStore.pagination.current_page + 1)" 
                        :disabled="catalogStore.pagination.current_page === catalogStore.pagination.last_page"
                        class="px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    >Siguiente</button>
                </div>

                </main>
            </div>
        </div>
        
        <LocationInfo />

        <BenefitsBar />
    
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useCartStore } from '../../stores/cart';
import { useCatalogStore } from '../../stores/catalog';
import PromoCarousel from '@/components/PromoCarousel.vue';
import LocationInfo from '@/components/public/LocationInfo.vue';
import BenefitsBar from '@/components/BenefitsBar.vue';
// 1. Inicialización de Stores
const catalogStore = useCatalogStore(); // Contiene productos, categorías y loading
const cartStore = useCartStore();

const isAdding = ref(false);

/**
 * Añade el producto al carrito usando la acción de Pinia.
 */
const addToCart = async (productId) => {
    if (isAdding.value) return; // Evitar clics múltiples
    isAdding.value = true;
    try {
        await cartStore.addProductToCart(productId, 1);
    } finally {
        isAdding.value = false;
    }
};


onMounted(() => {
    // 2. Lógica para el Catálogo
    // Llamamos a la acción del store si no hay productos cargados
    if (!catalogStore.products.length) {
        catalogStore.fetchProducts();
    }
    
    // 3. Lógica para el Carrito
    // También inicializar el carrito (esto está bien)
    if (!cartStore.isInitialized) {
        cartStore.fetchCart();
    }
});
</script>
<style scoped>
/* Estilo para que el sidebar flote en la parte superior en scroll */
.sticky {
    position: sticky;
    top: 20px; /* Ajusta la distancia desde el borde superior */
    /* El height: full es para que ocupe todo el espacio disponible */
}
</style>