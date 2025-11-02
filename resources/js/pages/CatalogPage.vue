<template>
    <MainLayout>
        <Catalog /> 
    </MainLayout>
</template>

<script setup lang="ts">
import MainLayout from '../components/public/MainLayout.vue'; // Asegúrate de la ruta
import Catalog from '../components/public/Catalog.vue';     // El componente de listado de productos
import { Head } from '@inertiajs/vue3';
</script>