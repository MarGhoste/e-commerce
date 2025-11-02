<template>
    <MainLayout>
        <ProductDetail :product="product" />
    </MainLayout>
</template>

<script setup lang="ts">
import MainLayout from '../components/public/MainLayout.vue'; 
// Este es el componente de la UI que contiene el diseño y botón de añadir
import ProductDetail from '../components/public/ProductDetail.vue'; 
import { defineProps } from '@inertiajs/vue3';

// Recibe el prop 'product' enviado desde el controlador
const props = defineProps({
    product: {
        type: Object,
        required: true,
    }
});
</script>