<template>
  <div class="product-form-container">
    <h3>{{ isEditing ? 'Editar Producto: ' + form.name : 'Crear Nuevo Producto' }}</h3>

    <div v-if="errors.length" class="alert-danger">
        <p>Por favor, corrija los siguientes errores:</p>
        <ul>
            <li v-for="error in errors" :key="error">{{ error }}</li>
        </ul>
    </div>

    <form @submit.prevent="saveProduct">
      
      <div class="form-group">
        <label for="name">Nombre:</label>
        <input type="text" id="name" v-model="form.name" required>
      </div>

      <div class="form-group">
        <label for="category_id">Categoría:</label>
        <select id="category_id" v-model="form.category_id" required>
          <option value="" disabled>Seleccione una categoría</option>
          <option v-for="cat in categories" :key="cat.id" :value="cat.id">
            {{ cat.name }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label for="price">Precio:</label>
        <input type="number" id="price" v-model.number="form.price" step="0.01" min="0.01" required>
      </div>

      <div class="form-group">
        <label for="stock">Stock:</label>
        <input type="number" id="stock" v-model.number="form.stock" min="0" required>
      </div>
      
      <div class="form-group">
        <label for="description">Descripción:</label>
        <textarea id="description" v-model="form.description"></textarea>
      </div>

      <div class="form-group-checkbox">
        <input type="checkbox" id="is_active" v-model="form.is_active">
        <label for="is_active">Producto Activo</label>
      </div>

      <button type="submit" class="btn btn-success">
        {{ isEditing ? 'Guardar Cambios' : 'Crear Producto' }}
      </button>
      <button type="button" @click="$emit('close')" class="btn btn-secondary">Cancelar</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    // Propiedades que recibe desde ProductManager.vue
    props: {
        product: { // Objeto de producto para edición
            type: Object,
            default: null,
        }
    },
    emits: ['productSaved', 'close'], // Eventos que emite

    data() {
        return {
            // Inicializa el formulario con datos por defecto o los datos del producto a editar
            form: {
                name: '',
                category_id: '',
                price: 0.01,
                stock: 0,
                description: '',
                is_active: true,
            },
            categories: [], // Lista de categorías cargadas desde la BD
            errors: [], // Para guardar errores de validación de Laravel
        };
    },
    computed: {
        isEditing() {
            // Determina si estamos en modo edición o creación
            return this.product !== null;
        }
    },
    mounted() {
        // Cargar categorías al iniciar el componente (necesario para el select)
        this.fetchCategories();
        
        // Si hay un producto en las props, lo carga para edición
        if (this.product) {
            this.form = { ...this.product }; 
        }
    },
    methods: {
        async fetchCategories() {
            try {
                // Llama al Admin\CategoryController@index
                const response = await axios.get('/admin/categories');
                this.categories = response.data;
            } catch (error) {
                console.error("Error al cargar categorías:", error);
                alert('No se pudieron cargar las categorías.');
            }
        },

        async saveProduct() {
            this.errors = []; // Limpiar errores previos
            
            try {
                let response;
                
                if (this.isEditing) {
                    // MODO EDICIÓN (PUT/PATCH)
                    // Llama a Admin\ProductController@update
                    response = await axios.put(`/admin/products/${this.product.id}`, this.form);
                } else {
                    // MODO CREACIÓN (POST)
                    // Llama a Admin\ProductController@store
                    response = await axios.post('/admin/products', this.form);
                }

                // Emitir evento al componente padre (ProductManager)
                this.$emit('productSaved', response.data);
                
            } catch (error) {
                // Manejar errores de validación (código 422 de Laravel)
                if (error.response && error.response.status === 422) {
                    // Laravel devuelve los errores en el formato data.errors
                    this.errors = Object.values(error.response.data.errors).flat();
                } else {
                    console.error("Error al guardar producto:", error);
                    this.errors = ['Ocurrió un error inesperado al guardar el producto.'];
                }
            }
        }
    }
}
</script>

<style scoped>
/* Estilos mínimos para que se vea decente */
.product-form-container { padding: 20px; border: 1px solid #ddd; margin-top: 20px; }
.form-group { margin-bottom: 15px; }
.form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px; box-sizing: border-box; }
.alert-danger { background: #fdd; color: #a00; border: 1px solid #a00; padding: 10px; margin-bottom: 15px; }
.btn-secondary { margin-left: 10px; }
</style>