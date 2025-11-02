// resources/js/stores/catalog.ts

import { defineStore } from 'pinia';
import axios from 'axios';

// Definiciones de tipos (ayudan a TypeScript a entender los datos)
interface Product {
    id: number;
    name: string;
    slug: string;
    price: number;
    stock: number;
    description: string;
    category?: { id: number, name: string };
    // Asegúrate de incluir todos los campos relevantes de tu modelo Product
}

interface Category {
    id: number;
    name: string;
}

interface Pagination {
    current_page: number;
    last_page: number;
    total: number;
    per_page: number;
    // ... otros campos de paginación de Laravel
}

// ----------------------------------------------------

export const useCatalogStore = defineStore('catalog', {
    state: () => ({
        products: [] as Product[],
        categories: [] as Category[],
        pagination: {} as Pagination,
        loading: false,
        categoryId: null as number | null,
    }),

    getters: {
        // Getter simple para saber si hay datos para mostrar
        hasProducts: (state) => state.products.length > 0,
    },

    actions: {
        /**
         * Llama a la API para obtener productos y categorías.
         */
        async fetchProducts(page: number = 1) {
            this.loading = true;
            try {
                // Construye la URL de la API usando los filtros actuales
                const url = `/api/products-catalog?page=${page}` + 
                            (this.categoryId ? `&category_id=${this.categoryId}` : '');

                // ✅ Tu controlador devuelve el JSON de productos y categorías
                const response = await axios.get(url); 
                
                this.products = response.data.products.data;
                this.pagination = response.data.products;
                this.categories = response.data.categories;

            } catch (error) {
                console.error('Error al cargar productos y catálogo:', error);
            } finally {
                this.loading = false;
            }
        },

        /**
         * Cambia el filtro de categoría y recarga los productos en la página 1.
         */
        setCategoryFilter(categoryId: number | null) {
            this.categoryId = categoryId;
            this.fetchProducts(1); // Siempre vuelve a la primera página al cambiar el filtro
        }
    }
});