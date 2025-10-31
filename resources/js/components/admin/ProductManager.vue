<template>
  <div>
    <h2>Gestión de Productos 🛒</h2>
    
    <button @click="openCreateForm" class="btn btn-primary">
        + Añadir Nuevo Producto
    </button>
    
    <ProductForm 
        v-if="showForm" 
        :product="productToEdit"
        @productSaved="handleProductSaved"
        @close="showForm = false"
    />

    <div v-else> <table class="table mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in products" :key="product.id">
                    <td>{{ product.id }}</td>
                    <td>{{ product.name }}</td>
                    <td>{{ product.category ? product.category.name : 'N/A' }}</td>
                    <td>${{ product.price }}</td>
                    <td>{{ product.stock }}</td>
                    <td>
                        <span :class="{'text-success': product.is_active, 'text-danger': !product.is_active}">
                            {{ product.is_active ? 'Sí' : 'No' }}
                        </span>
                    </td>
                    <td>
                        <button @click="editProduct(product)" class="btn btn-sm btn-info">Editar</button>
                        <button @click="deleteProduct(product.id)" class="btn btn-sm btn-danger">Eliminar</button>
                    </td>
                </tr>
                <tr v-if="products.length === 0">
                    <td colspan="7" class="text-center">No hay productos en el catálogo.</td>
                </tr>
            </tbody>
        </table>

        <div class="pagination-controls">
            <button 
                @click="fetchProducts(pagination.current_page - 1)" 
                :disabled="!pagination.prev_page_url"
                class="btn btn-sm btn-secondary"
            >Anterior</button>
            <span>Página {{ pagination.current_page }} de {{ pagination.last_page }}</span>
            <button 
                @click="fetchProducts(pagination.current_page + 1)" 
                :disabled="!pagination.next_page_url"
                class="btn btn-sm btn-secondary"
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

<style scoped>
/* Estilos muy básicos para la tabla */
.admin-wrapper { padding: 20px; }
.table th, .table td { border: 1px solid #ccc; padding: 8px; }
.text-success { color: green; font-weight: bold; }
.text-danger { color: red; }
</style>