<template>
    <div class="min-h-screen bg-gray-50">
        
        <header class="bg-white shadow sticky top-0 z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center py-4">
                <a href="/" class="text-2xl font-extrabold text-blue-600">
                    ECOMMERCE SIMPLE
                </a>
                
                <div class="hidden md:block w-1/2">
                    <input type="text" placeholder="Buscar productos, marcas, categorías..."
                           class="w-full border border-gray-300 p-2 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="/login" class="text-gray-600 hover:text-blue-600">👤 Iniciar Sesión</a>
                    
                    <CartIcon />
                </div>
            </div>
        </header>

        <main class="py-6">
            <slot />
        </main>

        <footer class="bg-gray-800 text-white mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center">
                <p>&copy; 2025 E-commerce Simple. Todos los derechos reservados.</p>
            </div>
        </footer>
    </div>
</template>

<script setup lang="ts">
// ✅ LÍNEA CORREGIDA: Usa './' para referenciar un archivo en el mismo directorio
import CartIcon from './CartIcon.vue'; 
// Importamos el componente de Icono del Carrito
</script>

<style scoped>
/* No se requiere mucho estilo aquí, Tailwind hace la mayor parte */
</style>