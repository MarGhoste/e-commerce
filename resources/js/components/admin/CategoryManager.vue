<template>
  <div>
    <h2>Gestión de Categorías 📚</h2>

    <div class="input-group mb-3">
        <input 
            type="text" 
            class="form-control" 
            placeholder="Nombre de la nueva categoría" 
            v-model="newCategoryName"
        >
        <div class="input-group-append">
            <button class="btn btn-primary" @click="createCategory" :disabled="!newCategoryName">
                Crear Categoría
            </button>
        </div>
    </div>
    
    <div v-if="error" class="alert-danger">{{ error }}</div>
    
    <table class="table mt-4">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Slug</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="category in categories" :key="category.id">
          <td>{{ category.id }}</td>
          <td>
            <span v-if="editingId !== category.id">{{ category.name }}</span>
            <input v-else type="text" v-model="editingName" @keyup.enter="updateCategory(category)">
          </td>
          <td>{{ category.slug }}</td>
          <td>
            <div v-if="editingId === category.id">
                <button @click="updateCategory(category)" class="btn btn-sm btn-success">Guardar</button>
                <button @click="cancelEdit" class="btn btn-sm btn-secondary">Cancelar</button>
            </div>
            <div v-else>
                <button @click="editCategory(category)" class="btn btn-sm btn-info">Editar</button>
                <button @click="deleteCategory(category.id)" class="btn btn-sm btn-danger">Eliminar</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            categories: [],
            newCategoryName: '', // Campo para la nueva categoría
            editingId: null,      // ID de la categoría que se está editando
            editingName: '',    // Nombre temporal durante la edición
            error: null,        // Mensaje de error para el usuario
        };
    },
    mounted() {
        this.fetchCategories(); 
    },
    methods: {
        async fetchCategories() {
            this.error = null;
            try {
                // Llama al Admin\CategoryController@index
                const response = await axios.get('/admin/categories');
                this.categories = response.data;
            } catch (err) {
                this.error = "Error al cargar categorías.";
                console.error(err);
            }
        },

        // --- CREAR ---
        async createCategory() {
            this.error = null;
            try {
                // Llama al Admin\CategoryController@store
                const response = await axios.post('/admin/categories', { name: this.newCategoryName });
                
                // Añade la nueva categoría al array
                this.categories.push(response.data);
                this.newCategoryName = ''; // Limpia el campo

            } catch (err) {
                // Manejar errores de validación 422
                if (err.response && err.response.status === 422) {
                    this.error = err.response.data.errors.name[0];
                } else {
                    this.error = "Error al crear la categoría.";
                    console.error(err);
                }
            }
        },

        // --- EDITAR (Preparación) ---
        editCategory(category) {
            this.editingId = category.id;
            this.editingName = category.name;
        },

        cancelEdit() {
            this.editingId = null;
            this.editingName = '';
        },

        // --- EDITAR (Guardar) ---
        async updateCategory(category) {
            this.error = null;
            if (this.editingName === category.name) {
                this.cancelEdit();
                return;
            }

            try {
                // Llama al Admin\CategoryController@update
                const response = await axios.put(`/admin/categories/${category.id}`, { name: this.editingName });
                
                // Actualiza el objeto en el array para que Vue lo refresque
                category.name = response.data.name;
                category.slug = response.data.slug; // El slug también se actualiza en el backend
                
                this.cancelEdit();

            } catch (err) {
                if (err.response && err.response.status === 422) {
                    this.error = err.response.data.errors.name[0];
                } else {
                    this.error = "Error al actualizar la categoría.";
                    console.error(err);
                }
            }
        },

        // --- ELIMINAR ---
        async deleteCategory(categoryId) {
            this.error = null;
            if (!confirm('¿Estás seguro de que quieres eliminar esta categoría? Esto fallará si tiene productos asociados.')) {
                return;
            }

            try {
                // Llama al Admin\CategoryController@destroy
                await axios.delete(`/admin/categories/${categoryId}`);
                
                // Eliminar la categoría del array de Vue para refrescar la tabla
                this.categories = this.categories.filter(c => c.id !== categoryId);

            } catch (err) {
                if (err.response && err.response.status === 409) {
                    this.error = "ERROR: No se puede eliminar. Existen productos asociados a esta categoría.";
                } else {
                    this.error = "Error al eliminar la categoría.";
                    console.error(err);
                }
            }
        }
    }
}

</script>

<style scoped>
.input-group { max-width: 500px; margin: 0 auto; }
.alert-danger { padding: 10px; background: #fdd; color: #a00; border: 1px solid #a00; margin-top: 15px; text-align: center; }
.table { width: 100%; border-collapse: collapse; }
.table th, .table td { border: 1px solid #ccc; padding: 8px; }
.table td input { width: 100%; box-sizing: border-box; }
.btn-secondary { margin-left: 5px; }
</style>