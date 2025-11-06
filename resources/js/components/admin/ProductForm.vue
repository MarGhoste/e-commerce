<template>
  <div class="p-6 bg-gray-50 rounded-lg shadow-inner">
    <h3 class="text-xl font-bold mb-4">{{ isEditing ? 'Editar Producto: ' + form.name : 'Crear Nuevo Producto' }}</h3>

    <div v-if="errors.length" class="mb-4 p-4 bg-red-100 text-red-700 border border-red-400 rounded">
        <p class="font-bold">Por favor, corrija los siguientes errores:</p>
        <ul class="list-disc list-inside">
            <li v-for="error in errors" :key="error">{{ error }}</li>
        </ul>
    </div>

    <form @submit.prevent="saveProduct" class="space-y-4">
      
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nombre:</label>
        <input type="text" id="name" v-model="form.name" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
      </div>

      <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700">Categoría:</label>
        <select id="category_id" v-model="form.category_id" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
          <option value="" disabled>Seleccione una categoría</option>
          <option v-for="cat in categories" :key="cat.id" :value="cat.id">
            {{ cat.name }}
          </option>
        </select>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="price" class="block text-sm font-medium text-gray-700">Precio:</label>
            <input type="number" id="price" v-model.number="form.price" step="0.01" min="0.01" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div>
            <label for="stock" class="block text-sm font-medium text-gray-700">Stock:</label>
            <input type="number" id="stock" v-model.number="form.stock" min="0" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
        </div>
      </div>

      <div>
        <label for="image" class="block text-sm font-medium text-gray-700">Imagen Principal:</label>
        <input type="file" id="image" @change="handleImageUpload" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
      </div>

      <div v-if="currentImagePreview" class="mt-4">
        <label class="block text-sm font-medium text-gray-700">Previsualización:</label>
        <img :src="currentImagePreview" alt="Previsualización de imagen principal" class="mt-1 max-w-xs h-auto border rounded-md shadow-sm">
      </div>
      
      <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Descripción:</label>
        <textarea id="description" v-model="form.description" rows="4" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
      </div>

      <div class="flex items-center">
        <input type="checkbox" id="is_active" v-model="form.is_active" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
        <label for="is_active" class="ml-2 block text-sm text-gray-900">Producto Activo</label>
      </div>

      <div class="flex justify-end space-x-4">
        <button type="button" @click="$emit('close')" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cancelar</button>
        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
            {{ isEditing ? 'Guardar Cambios' : 'Crear Producto' }}
        </button>
      </div>
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
            imageFile: null, // Guardará el objeto File para el envío
            currentImagePreview: null, // Guardará el Blob/URL para la previsualización
        
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

            if (this.product.image_url) {
                this.currentImagePreview = this.product.image_url;
            }
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

       handleImageUpload(event) {
            const file = event.target.files[0];
            this.imageFile = file; // Guardar el objeto File para enviarlo

            // Crear URL temporal para la previsualización
            if (file) {
                this.currentImagePreview = URL.createObjectURL(file);
            } else {
                this.currentImagePreview = this.isEditing && this.product.image_url 
                    ? this.product.image_url 
                    : null;
            }
        },

        async saveProduct() {
           this.errors = [];
            
            // 1. Crear FormData (Obligatorio para enviar archivos)
            const formData = new FormData();
            
            // 2. Añadir los campos de texto al FormData
            for (const key in this.form) {
                // Para PUT/PATCH, necesitas enviar la _method
                if (key !== 'id') { // No enviar el ID como campo de formulario
                    let value = this.form[key];
                    if (typeof value === 'boolean') {
                        value = value ? 1 : 0;
                    }
                    formData.append(key, value === null ? '' : value);
                }
            }

            // 3. Añadir el archivo de imagen
            if (this.imageFile) {
                formData.append('image', this.imageFile);
            }
            try {
                let response;
                
                if (this.isEditing) {
                    // MODO EDICIÓN (PUT/PATCH)
                    // Llama a Admin\ProductController@update
                    formData.append('_method', 'PUT'); // Spoofing for Laravel
                    response = await axios.post(`/admin/products/${this.product.id}`, formData);
                } else {
                    // MODO CREACIÓN (POST)
                    // Llama a Admin\ProductController@store
                    response = await axios.post('/admin/products', formData);
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