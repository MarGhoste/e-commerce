# Plataforma de E-commerce

Una plataforma de e-commerce moderna y completa construida con el frontend del stack VILT: Laravel, Inertia.js y Vue.js. Este proyecto proporciona una base sólida para una tienda en línea, con un catálogo de productos, carrito de compras, autenticación de usuarios y un panel de administración.

## Acerca del Proyecto

Esta aplicación es una demostración de un flujo de trabajo de desarrollo web moderno, integrando un potente backend en PHP con un frontend reactivo de tipo Single-Page Application (SPA). Utiliza las mejores herramientas de su clase para proporcionar una experiencia fluida tanto para desarrolladores como para usuarios.

### Tecnologías Utilizadas

**Backend:**

- [Laravel 12](https://laravel.com)
- [PHP 8.2](https://www.php.net)
- [Inertia.js](https://inertiajs.com) (Adaptador del lado del servidor)
- [Laravel Fortify](https://laravel.com/docs/fortify) (Autenticación)
- [Laravel Cashier Stripe](https://laravel.com/docs/cashier) (Procesamiento de Pagos)
- [Laravel Sanctum](https://laravel.com/docs/sanctum) (Autenticación de API)

**Frontend:**

- [Vue.js 3](https://vuejs.org)
- [Vite](https://vitejs.dev)
- [TypeScript](https://www.typescriptlang.org)
- [Pinia](https://pinia.vuejs.org) (Gestión de Estado)
- [Tailwind CSS](https://tailwindcss.com)

## Características

- **Catálogo de Productos**: Navega por productos y categorías.
- **Carrito de Compras**: Añade/elimina artículos y actualiza cantidades.
- **Checkout con Stripe**: Procesamiento de pagos seguro impulsado por Laravel Cashier.
- **Autenticación de Usuarios**: Funcionalidad de registro e inicio de sesión para clientes a través de Laravel Fortify.
- **Panel de Administración**: Un área dedicada para que los administradores gestionen:
    - Productos (CRUD)
    - Categorías (CRUD)
    - Órdenes
- **Seeders de Base de Datos**: Incluye seeders para categorías, productos y un usuario administrador por defecto para empezar rápidamente.

## Cómo Empezar

Sigue estas instrucciones para obtener una copia del proyecto en funcionamiento en tu máquina local para fines de desarrollo y pruebas.

### Prerrequisitos

- PHP >= 8.2
- Composer
- Node.js & npm
- Un servidor de base de datos (ej. MySQL, PostgreSQL, SQLite)

### Instalación

1.  **Clona el repositorio:**

    ```sh
    git clone https://github.com/tu-usuario/tu-repositorio.git
    cd tu-repositorio
    ```

2.  **Instala las dependencias de PHP:**

    ```sh
    composer install
    ```

3.  **Configura tu archivo de entorno:**

    ```sh
    cp .env.example .env
    ```

    Luego, genera la clave de tu aplicación:

    ```sh
    php artisan key:generate
    ```

4.  **Configura tu archivo `.env`:**
    Actualiza las siguientes variables con los detalles de tu entorno local:

    ```dotenv
    APP_URL=http://localhost:8000

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=ecommerce
    DB_USERNAME=root
    DB_PASSWORD=

    STRIPE_KEY=pk_test_...
    STRIPE_SECRET=sk_test_...
    ```

5.  **Ejecuta las migraciones y los seeders de la base de datos:**
    Esto creará las tablas necesarias y las poblará con datos de ejemplo, incluyendo un usuario administrador (`test@example.com`), ¡PROCURAR MODIFICAR EL USUARIO EN SU BD DANDOLE LOS PRIVILEGIOS DE ADMIN!

    Para acceder al panel de administrador usar la ruta Localhost:8000/admin

    ```sh
    php artisan migrate --seed
    ```

6.  **Instala las dependencias de JavaScript:**
    ```sh
    npm install
    ```
7.  **Crea el Enlace de Almacenamiento Público:**

    php artisan storage:link

## Uso (Ejecutar el Servidor de Desarrollo)

Este proyecto incluye un script conveniente para ejecutar todos los servidores de desarrollo necesarios de forma concurrente.

```sh
composer run dev
```

Este comando iniciará simultáneamente:

- El servidor de desarrollo de Laravel (`php artisan serve`)
- El servidor de Vite para la compilación de assets del frontend (`npm run dev`)
- El trabajador de colas de Laravel (`php artisan queue:listen`)

Ahora puedes acceder a la aplicación en la URL proporcionada por `php artisan serve` (normalmente `http://127.0.0.1:8000`).

## Ejecución de Pruebas

Para ejecutar la suite de pruebas de PHPUnit:

```sh
composer run test
```

## Estilo de Código

Este proyecto utiliza **ESLint** para el linting de JavaScript/TypeScript y **Prettier** para el formateo de código.

- **Verificar formato:**
    ```sh
    npm run format:check
    ```
- **Arreglar formato automáticamente:**
    ```sh
    npm run format
    ```
- **Analizar el código con el linter:**
    ```sh
    npm run lint
    ```

CUALQUIER DUDA PUEDES COMUNICARSE CONMIGO :D
