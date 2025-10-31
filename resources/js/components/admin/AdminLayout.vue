<template>
  <div class="admin-wrapper">
    <aside class="sidebar">
        <nav>
            <a href="#" @click.prevent="currentView = 'products'">Productos</a>
            <a href="#" @click.prevent="currentView = 'orders'">Órdenes</a>
            <a href="#" @click.prevent="currentView = 'categories'">Categorías</a>
        </nav>
    </aside>

    <main class="content">
        <h1>Panel de Administración</h1>

        <ProductManager v-if="currentView === 'products'" />
        <OrderManager v-if="currentView === 'orders'" />
        <CategoryManager v-if="currentView === 'categories'" />

    </main>
  </div>
</template>


<script lang="ts">
import ProductManager from './ProductManager.vue';
import OrderManager from './OrderManager.vue';
import CategoryManager from './CategoryManager.vue';
import ProductForm from './ProductForm.vue';

export default {
    components: { ProductManager, OrderManager, CategoryManager, ProductForm },
    data() {
        return {
            // Controla qué componente se muestra
            currentView: 'products' 
        }
    }
}
</script>