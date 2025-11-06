<template>
    <div class="min-h-screen bg-gray-900 text-gray-200">
        
        
        <Header />

        <main class="py-6">
            <slot />
        </main>

        <Footer />


    </div>
</template>

<script setup lang="ts">

import Header from '@/Components/Header.vue'; 
import Footer from '@/Components/Footer.vue';

</script>

<style scoped>
/* No se requiere mucho estilo aquí, Tailwind hace la mayor parte */
</style>