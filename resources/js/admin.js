// resources/js/admin.js

import '../css/app.css';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import AdminLayout from './components/admin/AdminLayout.vue';
import ProductManager from './components/admin/ProductManager.vue';
import OrderManager from './components/admin/OrderManager.vue';
import CategoryManager from './components/admin/CategoryManager.vue'; 
// Importa todos los componentes que usarás

// 1. Inicializar la aplicación Vue
const app = createApp({});

const pinia = createPinia();
app.use(pinia);

// 2. Registrar componentes (para que puedan usarse en Blade)
app.component('AdminLayout', AdminLayout);
app.component('ProductManager', ProductManager);
app.component('OrderManager', OrderManager);
app.component('CategoryManager', CategoryManager);


// 3. Montar la aplicación en el contenedor con el ID 'admin-app'
// Solo se monta si el elemento existe (para evitar errores en otras páginas)
if (document.getElementById('admin-app')) {
    app.mount('#admin-app');
}