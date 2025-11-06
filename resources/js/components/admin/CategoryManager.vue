<template>
  <div>
    <h2 class="text-2xl font-bold mb-4">Gestión de Categorías 📚</h2>

    <div class="mb-4 max-w-lg">
        <label for="new-category" class="sr-only">Nombre de la nueva categoría</label>
        <div class="flex rounded-md shadow-sm">
            <input 
                type="text" 
                id="new-category"
                class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Nombre de la nueva categoría" 
                v-model="newCategoryName"
                @keyup.enter="createCategory"
            >
            <button class="px-4 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 disabled:opacity-50" @click="createCategory" :disabled="!newCategoryName">
                Crear
            </button>
        </div>
    </div>
    
    <div v-if="error" class="my-4 p-3 bg-red-100 text-red-700 border border-red-400 rounded">{{ error }}</div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-2 px-4 border-b">ID</th>
                    <th class="py-2 px-4 border-b">Nombre</th>
                    <th class="py-2 px-4 border-b">Slug</th>
                    <th class="py-2 px-4 border-b">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="category in categories" :key="category.id" class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b text-center">{{ category.id }}</td>
                    <td class="py-2 px-4 border-b">
                        <span v-if="editingId !== category.id">{{ category.name }}</span>
                        <input v-else type="text" v-model="editingName" @keyup.enter="updateCategory(category)" class="w-full px-2 py-1 border rounded">
                    </td>
                    <td class="py-2 px-4 border-b">{{ category.slug }}</td>
                    <td class="py-2 px-4 border-b text-center">
                        <div v-if="editingId === category.id" class="space-x-2">
                            <button @click="updateCategory(category)" class="px-2 py-1 bg-green-500 text-white rounded text-sm hover:bg-green-600">Guardar</button>
                            <button @click="cancelEdit" class="px-2 py-1 bg-gray-500 text-white rounded text-sm hover:bg-gray-600">Cancelar</button>
                        </div>
                        <div v-else class="space-x-2">
                            <button @click="editCategory(category)" class="px-2 py-1 bg-yellow-500 text-white rounded text-sm hover:bg-yellow-600">Editar</button>
                            <button @click="deleteCategory(category.id)" class="px-2 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600">Eliminar</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            categories: [],
            newCategoryName: '',
            editingId: null,
            editingName: '',
            error: null,
        };
    },
    mounted() {
        this.fetchCategories(); 
    },
    methods: {
        async fetchCategories() {
            this.error = null;
            try {
                const response = await axios.get('/admin/categories');
                this.categories = response.data;
            } catch (err) {
                this.error = "Error al cargar categorías.";
                console.error(err);
            }
        },

        async createCategory() {
            this.error = null;
            try {
                const response = await axios.post('/admin/categories', { name: this.newCategoryName });
                this.categories.push(response.data);
                this.newCategoryName = '';
            } catch (err) {
                if (err.response && err.response.status === 422) {
                    this.error = err.response.data.errors.name[0];
                } else {
                    this.error = "Error al crear la categoría.";
                    console.error(err);
                }
            }
        },

        editCategory(category) {
            this.editingId = category.id;
            this.editingName = category.name;
        },

        cancelEdit() {
            this.editingId = null;
            this.editingName = '';
        },

        async updateCategory(category) {
            this.error = null;
            if (this.editingName === category.name) {
                this.cancelEdit();
                return;
            }

            try {
                const response = await axios.put(`/admin/categories/${category.id}`, { name: this.editingName });
                category.name = response.data.name;
                category.slug = response.data.slug;
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

        async deleteCategory(categoryId) {
            this.error = null;
            if (!confirm('¿Estás seguro de que quieres eliminar esta categoría? Esto fallará si tiene productos asociados.')) {
                return;
            }

            try {
                await axios.delete(`/admin/categories/${categoryId}`);
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