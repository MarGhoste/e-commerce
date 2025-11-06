<template>
  <div>
    <h2>Gestión de Productos 🛒</h2>
    
    <button @click="openCreateForm" class="my-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
        + Añadir Nuevo Producto
    </button>
    
    <ProductForm 
        v-if="showForm" 
        :product="productToEdit"
        @productSaved="handleProductSaved"
        @close="showForm = false"
    />

    <div v-else> <table class="min-w-full bg-white">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-2 px-4 border-b">ID</th>
                    <th class="py-2 px-4 border-b">Nombre</th>
                    <th class="py-2 px-4 border-b">Categoría</th>
                    <th class="py-2 px-4 border-b">Precio</th>
                    <th class="py-2 px-4 border-b">Stock</th>
                    <th class="py-2 px-4 border-b">Activo</th>
                    <th class="py-2 px-4 border-b">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b text-center">{{ product.id }}</td>
                    <td class="py-2 px-4 border-b">{{ product.name }}</td>
                    <td class="py-2 px-4 border-b">{{ product.category ? product.category.name : 'N/A' }}</td>
                    <td class="py-2 px-4 border-b text-right">${{ product.price }}</td>
                    <td class="py-2 px-4 border-b text-center">{{ product.stock }}</td>
                    <td class="py-2 px-4 border-b text-center">
                        <span :class="{'text-green-600': product.is_active, 'text-red-600': !product.is_active}">
                            {{ product.is_active ? 'Sí' : 'No' }}
                        </span>
                    </td>
                    <td class="py-2 px-4 border-b text-center">
                        <button @click="editProduct(product)" class="px-2 py-1 bg-yellow-500 text-white rounded text-sm hover:bg-yellow-600">Editar</button>
                        <button @click="deleteProduct(product.id)" class="ml-2 px-2 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600">Eliminar</button>
                    </td>
                </tr>
                <tr v-if="products.length === 0">
                    <td colspan="7" class="text-center py-4">No hay productos en el catálogo.</td>
                </tr>
            </tbody>
        </table>

        <div class="mt-4 flex justify-between items-center">
            <button 
                @click="fetchProducts(pagination.current_page - 1)" 
                :disabled="!pagination.prev_page_url"
                class="px-4 py-2 bg-gray-300 rounded disabled:opacity-50"
            >Anterior</button>
            <span>Página {{ pagination.current_page }} de {{ pagination.last_page }}</span>
            <button 
                @click="fetchProducts(pagination.current_page + 1)" 
                :disabled="!pagination.next_page_url"
                class="px-4 py-2 bg-gray-300 rounded disabled:opacity-50"
            >Siguiente</button>
        </div>
    </div> </div>
</template>

<script>
// El resto de tu script está correcto, solo asegúrate de añadir lang="js" o lang="ts"
import axios from 'axios';
import ProductForm from './ProductForm.vue';

export default {
    components: {
        ProductForm
    },
    data() {
        return {
            products: [],
            pagination: {},
            loading: false,
            // showCreateForm no es necesario si usas showForm para controlar el formulario
            // showCreateForm: false, 
            showForm: false, // Controla la visibilidad del ProductForm
            productToEdit: null,
        };
    },
    mounted() {
        this.fetchProducts();
    },
    methods: {
        async fetchProducts(page = 1) {
            this.loading = true;
            try {
                const response = await axios.get(`/admin/products?page=${page}`);
                this.products = response.data.data;
                const { current_page, last_page, next_page_url, prev_page_url } = response.data;
                this.pagination = { current_page, last_page, next_page_url, prev_page_url };
            } catch (error) {
                console.error("Error al cargar productos:", error);
            } finally {
                this.loading = false;
            }
        },

        // Cambié el nombre de showCreateForm a openCreateForm para ser más descriptivo
        openCreateForm() {
            this.productToEdit = null; // Modo creación
            this.showForm = true;
        },

        editProduct(product) {
            this.productToEdit = product; // Modo edición
            this.showForm = true;
        },

        async deleteProduct(productId) {
            if (!confirm('¿Estás seguro de que quieres eliminar este producto?')) {
                return;
            }
            try {
                await axios.delete(`/admin/products/${productId}`);
                this.fetchProducts(this.pagination.current_page); 
                alert('Producto eliminado exitosamente.');
            } catch (error) {
                console.error("Error al eliminar producto:", error);
                alert('Error al eliminar producto. Revisa la consola.');
            }
        },
        
        handleProductSaved() {
            this.showForm = false; // Ocultar el formulario
            this.fetchProducts(this.pagination.current_page); // Recargar la lista
        }
    }
}
</script>
