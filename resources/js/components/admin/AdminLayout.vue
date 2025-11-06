<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-white flex-shrink-0">
        <div class="p-4 text-2xl font-bold">Admin</div>
        <nav class="mt-4">
            <a href="#" @click.prevent="currentView = 'products'" class="block px-4 py-2 hover:bg-gray-700">Productos</a>
            <a href="#" @click.prevent="currentView = 'orders'" class="block px-4 py-2 hover:bg-gray-700">Órdenes</a>
            <a href="#" @click.prevent="currentView = 'categories'" class="block px-4 py-2 hover:bg-gray-700">Categorías</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <h1 class="text-3xl font-bold mb-8">Panel de Administración</h1>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <ProductManager v-if="currentView === 'products'" />
            <OrderManager v-if="currentView === 'orders'" />
            <CategoryManager v-if="currentView === 'categories'" />
        </div>

    </main>
  </div>
</template>


<script lang="ts">
import ProductManager from './ProductManager.vue';
import OrderManager from './OrderManager.vue';
import CategoryManager from './CategoryManager.vue';
//import ProductForm from './ProductForm.vue';

export default {
    components: { ProductManager, OrderManager, CategoryManager },
    data() {
        return {
            // Controla qué componente se muestra
            currentView: 'products' 
        }
    }
}
</script>