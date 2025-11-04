<template>
    <div class="container mx-auto p-4 md:p-8">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Catálogo de Productos 🚀</h2>
        
        <div class="flex flex-col md:flex-row gap-8">
            
            <aside class="md:w-1/4 bg-white p-4 rounded-lg shadow-lg h-full sticky top-4">
                <h3 class="text-xl font-bold mb-4 border-b pb-2">Filtros</h3>

                <div class="mb-6">
                    <h4 class="font-semibold mb-2 text-gray-700">Categorías</h4>
                    <ul>
                        <li class="cursor-pointer py-1 hover:text-blue-600"
                            :class="{'font-bold text-blue-600': !catalogStore.categoryId}"
                            @click="catalogStore.setCategoryFilter(null)">
                            Todas ({{ catalogStore.pagination.total }})
                        </li>
                        
                        <li v-for="category in catalogStore.categories" :key="category.id"
                            class="cursor-pointer py-1 hover:text-blue-600"
                            :class="{'font-bold text-blue-600': catalogStore.categoryId === category.id}"
                            @click="catalogStore.setCategoryFilter(category.id)">
                            {{ category.name }}
                        </li>
                    </ul>
                </div>
                
                <h4 class="font-semibold mb-2 text-gray-700 pt-4 border-t">Rango de Precios</h4>
            </aside>

            <main class="md:w-3/4">
                <div v-if="catalogStore.loading" class="text-center py-10">Cargando productos...</div>
                
                <div v-else-if="catalogStore.products.length === 0" class="text-center py-10 text-gray-500">
                    No se encontraron productos para los filtros seleccionados.
                </div>

                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="product in catalogStore.products" :key="product.id"
                         class="bg-white rounded-lg shadow-lg overflow-hidden border hover:shadow-xl transition duration-300">
                        
                        <div class="h-40 bg-gray-200 flex items-center justify-center text-gray-500">
                            [Imagen de Producto]
                        </div>

                        <div class="p-4">
                            <h3 class="text-lg font-semibold h-12 overflow-hidden hover:text-blue-600 cursor-pointer">
                                <a :href="`/products/${product.slug}`">{{ product.name }}</a>
                            </h3>
                            <p class="text-sm text-gray-500 mb-2">{{ product.category ? product.category.name : 'Varios' }}</p>
                            
                            <p class="text-2xl font-bold text-red-600 mt-2">${{ product.price.toFixed(2) }}</p>
                            
                            <div class="mt-4 flex justify-between items-center">
                                <button 
                                    @click="addToCart(product.id)"
                                    :disabled="isAdding"
                                    class="flex items-center justify-center bg-green-500 text-white py-2 px-3 text-sm rounded-lg hover:bg-green-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    Añadir 🛒
                                </button>
                                <a :href="`/products/${product.slug}`" class="text-blue-600 hover:underline text-sm">Ver Detalle</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-center space-x-4" v-if="catalogStore.products.length > 0">
                    <button 
                        @click="catalogStore.fetchProducts(catalogStore.pagination.current_page - 1)" 
                        :disabled="catalogStore.pagination.current_page === 1"
                        class="px-4 py-2 border rounded disabled:opacity-50"
                    >Anterior</button>
                    <span class="py-2">Página {{ catalogStore.pagination.current_page }} de {{ catalogStore.pagination.last_page }}</span>
                    <button 
                        @click="catalogStore.fetchProducts(catalogStore.pagination.current_page + 1)" 
                        :disabled="catalogStore.pagination.current_page === catalogStore.pagination.last_page"
                        class="px-4 py-2 border rounded disabled:opacity-50"
                    >Siguiente</button>
                </div>

            </main>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useCartStore } from '../../stores/cart';
import { useCatalogStore } from '../../stores/catalog'; // Importar el store del catálogo

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