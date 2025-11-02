// resources/js/bootstrap.ts

import axios from 'axios';

// 1. Configuración Global de Axios
// Esto asegura que Axios esté disponible globalmente para todas las llamadas API
(window as any).axios = axios;

// 2. Configuración de Credenciales
// Esto es VITAL para que Laravel acepte las peticiones POST/PATCH/DELETE
// y para manejar las sesiones y el CSRF (seguridad).
(window as any).axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
(window as any).axios.defaults.withCredentials = true;

// 3. Obtener el Token CSRF
// El token CSRF (Cross-Site Request Forgery) es necesario para las peticiones seguras.
// En Laravel, este token se suele colocar en la etiqueta meta.
const token = document.head.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content;

if (token) {
    (window as any).axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}