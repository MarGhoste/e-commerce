<template>
    <header class="bg-gray-800 text-white shadow sticky top-0 z-10"> 
        
        <div class="bg-gray-900 text-gray-300 text-xs py-2 hidden md:block">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between">
                
                <div class="flex items-center space-x-6">
                    <span>
                        <i class="fas fa-clock mr-1"></i> 
                        Lunes a Sábado 9am a 6pm - Domingo 10am a 4pm
                    </span>
                    <span class="text-green-400 font-semibold"> 
                        <i class="fab fa-whatsapp mr-1"></i> 
                        902 079 944
                    </span>
                </div>

                <div class="flex items-center space-x-6">
                    <span>
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        Pasaje, Los Alamos Ventanilla 123 - Callao
                    </span>
                    <span>Métodos de pago Yape - Plin</span>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center py-3">
            
            <Link href="/" class="flex items-center space-x-2 text-indigo-400 hover:text-indigo-300 transition">
                <img :src="logoUrl" alt="Logo E-commerce Simple" class="h-22 w-60 object-cover"> 
            </Link>
            
            <div class="hidden md:block w-full max-w-lg mx-auto px-6">
                <input type="text" placeholder="Buscar productos, marcas, categorías..."
                       class="w-full border border-gray-600 bg-gray-700 p-2 rounded-full text-white placeholder-gray-400 focus:ring-indigo-400 focus:border-indigo-400 transition duration-150 ease-in-out">
            </div>
            
            <div class="flex items-center space-x-6">
                
                <div v-if="user" class="flex items-center space-x-2 text-sm">
                    <span class="text-white font-medium hidden sm:inline">{{ user.name }}</span>
                    <Link :href="logout.url()" method="post" as="button" type="button" 
                          class="text-red-400 hover:text-red-300 text-sm font-medium transition">
                        Salir
                    </Link>
                </div>
                <Link v-else href="/login" class="text-gray-300 hover:text-indigo-200 transition flex items-center space-x-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3v-1m5-13V6a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    <span class="hidden sm:inline">Iniciar Sesión</span>
                </Link>
                
                <CartIcon class="text-white hover:text-indigo-200" />
                
            </div>
        </div>
        <!--
        <div class="bg-red-700 shadow-lg hidden lg:block">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="flex justify-center items-center h-10 space-x-6 text-sm font-semibold">
                    
                    <Link href="/" class="text-white hover:bg-red-600 px-3 py-2 rounded transition duration-150">
                        Inicio
                    </Link>
                    
                    <Link href="/categorias/pc-gamer" class="text-white hover:bg-red-600 px-3 py-2 rounded transition duration-150">
                        PC Gamer
                    </Link>
                    
                    <Link href="/categorias/punto-de-venta" class="text-white hover:bg-red-600 px-3 py-2 rounded transition duration-150">
                        Punto de Venta
                    </Link>
                    
                    <Link href="/categorias/todo-en-uno" class="text-white hover:bg-red-600 px-3 py-2 rounded transition duration-150">
                        Todo en uno
                    </Link>
                    
                    <Link href="/categorias/laptops" class="text-white hover:bg-red-600 px-3 py-2 rounded transition duration-150">
                        Laptops
                    </Link>
                    
                    <Link href="/categorias/tablets" class="text-white hover:bg-red-600 px-3 py-2 rounded transition duration-150">
                        Tablets
                    </Link>
                    
                    <Link href="/categorias/pantallas" class="text-white hover:bg-red-600 px-3 py-2 rounded transition duration-150">
                        Pantallas
                    </Link>
                    
                    <Link href="/categorias/parlantes" class="text-white hover:bg-red-600 px-3 py-2 rounded transition duration-150">
                        Parlantes
                    </Link>

                    <Link href="/categorias/impresoras" class="text-white hover:bg-red-600 px-3 py-2 rounded transition duration-150">
                        Impresoras
                    </Link>
                    
                    <Link href="/categorias/proyectores" class="text-white hover:bg-red-600 px-3 py-2 rounded transition duration-150">
                        Proyectores
                    </Link>

                    <Link href="/categorias/accesorios" class="text-white hover:bg-red-600 px-3 py-2 rounded transition duration-150">
                        Accesorios
                    </Link>
                    
                </nav>
            </div>
        </div>
        -->
        <!-- New Horizontal Filter Bar from Catalog -->
        <div class="bg-red-700 p-4 shadow-md">
            <div class="container mx-auto flex flex-wrap justify-center items-center gap-x-6 gap-y-2">
                <Link href="/" class="cursor-pointer py-2 px-4 rounded-lg transition duration-300 text-sm font-semibold text-white hover:bg-red-600">
                    Inicio
                </Link>
                <span 
                    class="cursor-pointer py-2 px-4 rounded-lg transition duration-300 text-sm font-semibold"
                    :class="!catalogStore.categoryId ? 'bg-white text-red-700' : 'text-white hover:bg-red-600'"
                    @click="catalogStore.setCategoryFilter(null)">
                    Todas
                </span>
                <span v-for="category in catalogStore.categories" :key="category.id"
                    class="cursor-pointer py-2 px-4 rounded-lg transition duration-300 text-sm font-semibold"
                    :class="catalogStore.categoryId === category.id ? 'bg-white text-red-700' : 'text-white hover:bg-red-600'"
                    @click="catalogStore.setCategoryFilter(category.id)">
                    {{ category.name }}
                </span>
            </div>
        </div>
    </header>
</template>

<script setup lang="ts">
// Lógica y dependencias del header
import { computed, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import CartIcon from './public/CartIcon.vue';
import { logout } from '@/routes';
import logoUrl from '../assets/Logo.jpg';
import { useCatalogStore } from '../stores/catalog';



const page = usePage();
const user = computed(() => page.props.auth.user);

const catalogStore = useCatalogStore();

onMounted(() => {
    // Cargar categorías si no están ya en el store
    if (catalogStore.categories.length === 0) {
        catalogStore.fetchProducts(); // Asumimos que esto carga las categorías también
    }
});
</script>