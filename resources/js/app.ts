// resources/js/app.ts (CORREGIDO PARA INERTIA.JS)

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { createPinia } from 'pinia';
import { initializeTheme } from './composables/useAppearance';

import '../css/app.css';
import './bootstrap';

// ✅ MANTENEMOS la inicialización de Pinia, pero como plugin
const pinia = createPinia();

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';


createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    
    // Rutas de Inertia (Pages)
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
        
    // Configuración de montaje
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin) // Plugin de Inertia
            .use(pinia)  // ✅ Usar Pinia aquí
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();

// ❌ Eliminamos: 
// import MainLayout from './components/public/MainLayout.vue'; 
// const app = createApp({});
// app.use(pinia);
// import Catalog from './components/public/Catalog.vue';
// app.component('Catalog', Catalog); 
// app.mount('#app');